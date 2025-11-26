<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPkl;
use App\Models\tb_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PengajuanApprovedExport;

class PengajuanPklAdminController extends Controller
{
    // Tampilkan semua pengajuan PKL
    public function index(Request $request)
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'admin') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai admin.');
        }

        // Query pengajuan dengan relasi
        $query = PengajuanPkl::with([
            'siswa',
            'dudiPilihan1',
            'dudiPilihan2',
            'dudiPilihan3',
            'dudiMandiriPilihan1',
            'dudiMandiriPilihan2',
            'dudiMandiriPilihan3'
        ]);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by kelas
        if ($request->has('kelas') && $request->kelas != '') {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Search by nama/nis
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->orderBy('tanggal_pengajuan', 'desc')->paginate(20);

        // Get data admin
        $data = \App\Models\tb_admin::find($user->id_admin);

        return view('admin.kelola-pengajuan', compact('pengajuan', 'data'));
    }

    // Tampilkan detail pengajuan (untuk AJAX)
    public function detail($id)
    {
        $pengajuan = PengajuanPkl::with([
            'siswa',
            'dudiPilihan1',
            'dudiPilihan2',
            'dudiPilihan3',
            'dudiMandiriPilihan1',
            'dudiMandiriPilihan2',
            'dudiMandiriPilihan3'
        ])->find($id);

        if (!$pengajuan) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pengajuan
        ]);
    }

    // Ubah status pengajuan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,approved,rejected'
        ]);

        $pengajuan = PengajuanPkl::find($id);

        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        $pengajuan->status = $request->status;
        $pengajuan->save();

        $siswa = $pengajuan->siswa;

        logActivity(
            'update',
            'Status Pengajuan PKL Diubah',
            "Status pengajuan PKL siswa {$siswa->nama} (NIS: {$siswa->nis}) diubah menjadi {$request->status}",
            Session::get('loginId')
        );

        return back()->with('success', 'Status pengajuan berhasil diubah.');
    }

    // Ubah pilihan aktif
    public function changePilihan(Request $request, $id)
    {
        $request->validate([
            'pilihan_aktif' => 'required|string|max:100'
        ]);

        $pengajuan = PengajuanPkl::with('siswa')->find($id);

        if (!$pengajuan) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan tidak ditemukan.'
                ], 404);
            }
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        $oldPilihan = $pengajuan->pilihan_aktif;
        $newPilihan = $request->pilihan_aktif;

        $pengajuan->pilihan_aktif = $newPilihan;

        // Jika diubah ke SMK Telkom Banjarbaru, otomatis approve
        if ($newPilihan === 'SMK Telkom Banjarbaru') {
            $pengajuan->status = 'approved';

            logActivity(
                'update',
                'Siswa Ditempatkan PKL di Sekolah',
                "Siswa {$pengajuan->siswa->nama} (NIS: {$pengajuan->siswa->nis}) ditempatkan untuk PKL di SMK Telkom Banjarbaru (Grade {$pengajuan->siswa->grade_kurikulum})",
                Session::get('loginId')
            );
        }

        $pengajuan->save();

        $siswa = $pengajuan->siswa;
        $pilihanText = ($newPilihan === 'SMK Telkom Banjarbaru') ? 'SMK Telkom Banjarbaru (PKL di Sekolah)' : "pilihan {$newPilihan}";

        logActivity(
            'update',
            'Pilihan PKL Diubah',
            "Pilihan aktif PKL siswa {$siswa->nama} (NIS: {$siswa->nis}) diubah dari {$oldPilihan} ke {$pilihanText}",
            Session::get('loginId')
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pilihan aktif berhasil diubah.' . ($newPilihan === 'SMK Telkom Banjarbaru' ? ' Status otomatis di-approve.' : '')
            ]);
        }

        return back()->with('success', 'Pilihan aktif berhasil diubah.' . ($newPilihan === 'SMK Telkom Banjarbaru' ? ' Status otomatis di-approve.' : ''));
    }

    // Approve pengajuan dan assign ke DUDI
    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanPkl::find($id);

        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        // Tentukan DUDI mana yang dipilih
        $pilihanAktif = $pengajuan->pilihan_aktif;
        $idDudi = null;

        if ($pilihanAktif == '1') {
            $idDudi = $pengajuan->id_dudi_pilihan_1 ?? $pengajuan->id_dudi_mandiri_pilihan_1;
            // Update status pilihan 1 menjadi approved
            $pengajuan->status_pilihan_1 = 'approved';
            $pengajuan->tanggal_response_pilihan_1 = now();
            $pengajuan->catatan_pilihan_1 = $request->input('catatan', 'Disetujui oleh admin');
        } elseif ($pilihanAktif == '2') {
            $idDudi = $pengajuan->id_dudi_pilihan_2 ?? $pengajuan->id_dudi_mandiri_pilihan_2;
            // Update status pilihan 2 menjadi approved
            $pengajuan->status_pilihan_2 = 'approved';
            $pengajuan->tanggal_response_pilihan_2 = now();
            $pengajuan->catatan_pilihan_2 = $request->input('catatan', 'Disetujui oleh admin');
        } else {
            $idDudi = $pengajuan->id_dudi_pilihan_3 ?? $pengajuan->id_dudi_mandiri_pilihan_3;
            // Update status pilihan 3 menjadi approved
            $pengajuan->status_pilihan_3 = 'approved';
            $pengajuan->tanggal_response_pilihan_3 = now();
            $pengajuan->catatan_pilihan_3 = $request->input('catatan', 'Disetujui oleh admin');
        }

        if (!$idDudi) {
            return back()->with('error', 'DUDI pilihan tidak valid.');
        }

        // Update pengajuan status menjadi approved
        $pengajuan->status = 'approved';
        $pengajuan->save();

        // Update siswa
        $siswa = tb_siswa::find($pengajuan->id_siswa);
        $siswa->id_dudi = $idDudi;
        $siswa->status_penempatan = 'ditempatkan';
        $siswa->save();

        logActivity(
            'update',
            'Pengajuan PKL Disetujui',
            "Pengajuan PKL siswa {$siswa->nama} (NIS: {$siswa->nis}) Pilihan {$pilihanAktif} disetujui dan ditempatkan",
            Session::get('loginId')
        );

        return back()->with('success', 'Pengajuan berhasil disetujui dan siswa telah ditempatkan!');
    }

    // Reject pengajuan
    public function reject(Request $request, $id)
    {
        $pengajuan = PengajuanPkl::find($id);

        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        $catatan = $request->input('catatan', 'Ditolak oleh admin');
        $pilihanAktif = $pengajuan->pilihan_aktif;

        // Update status pilihan yang aktif menjadi rejected
        if ($pilihanAktif == '1') {
            $pengajuan->status_pilihan_1 = 'rejected';
            $pengajuan->tanggal_response_pilihan_1 = now();
            $pengajuan->catatan_pilihan_1 = $catatan;
        } elseif ($pilihanAktif == '2') {
            $pengajuan->status_pilihan_2 = 'rejected';
            $pengajuan->tanggal_response_pilihan_2 = now();
            $pengajuan->catatan_pilihan_2 = $catatan;
        } else {
            $pengajuan->status_pilihan_3 = 'rejected';
            $pengajuan->tanggal_response_pilihan_3 = now();
            $pengajuan->catatan_pilihan_3 = $catatan;
        }

        // Cek apakah ada pilihan berikutnya untuk cascade
        $cascadeMessage = '';
        if ($pilihanAktif == '1') {
            // Cek pilihan 2
            if ($pengajuan->id_dudi_pilihan_2 || $pengajuan->id_dudi_mandiri_pilihan_2) {
                $pengajuan->pilihan_aktif = '2';
                $pengajuan->status = 'pending';
                $cascadeMessage = ' Otomatis dialihkan ke Pilihan 2.';
            } elseif ($pengajuan->id_dudi_pilihan_3 || $pengajuan->id_dudi_mandiri_pilihan_3) {
                $pengajuan->pilihan_aktif = '3';
                $pengajuan->status = 'pending';
                $cascadeMessage = ' Otomatis dialihkan ke Pilihan 3.';
            } else {
                $pengajuan->status = 'rejected';
                $cascadeMessage = ' Tidak ada pilihan lain tersedia.';
            }
        } elseif ($pilihanAktif == '2') {
            // Cek pilihan 3
            if ($pengajuan->id_dudi_pilihan_3 || $pengajuan->id_dudi_mandiri_pilihan_3) {
                $pengajuan->pilihan_aktif = '3';
                $pengajuan->status = 'pending';
                $cascadeMessage = ' Otomatis dialihkan ke Pilihan 3.';
            } else {
                $pengajuan->status = 'rejected';
                $cascadeMessage = ' Tidak ada pilihan lain tersedia.';
            }
        } else {
            // Pilihan 3 ditolak, tidak ada pilihan lain
            $pengajuan->status = 'rejected';
            $cascadeMessage = ' Semua pilihan telah ditolak.';
        }

        $pengajuan->save();

        $siswa = $pengajuan->siswa;

        logActivity(
            'update',
            'Pengajuan PKL Ditolak',
            "Pengajuan PKL siswa {$siswa->nama} (NIS: {$siswa->nis}) Pilihan {$pilihanAktif} ditolak. Catatan: {$catatan}.{$cascadeMessage}",
            Session::get('loginId')
        );

        return back()->with('success', 'Pengajuan berhasil ditolak.' . $cascadeMessage);
    }

    // Delete pengajuan
    public function destroy($id)
    {
        $pengajuan = PengajuanPkl::find($id);

        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        $siswa = $pengajuan->siswa;
        $siswaNama = $siswa->nama;
        $siswaNis = $siswa->nis;

        $pengajuan->delete();

        logActivity(
            'delete',
            'Pengajuan PKL Dihapus',
            "Pengajuan PKL siswa {$siswaNama} (NIS: {$siswaNis}) dihapus",
            Session::get('loginId')
        );

        return back()->with('success', 'Pengajuan berhasil dihapus.');
    }

    // Export data pengajuan yang sudah approved ke Excel
    public function exportApproved()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'admin') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai admin.');
        }

        logActivity(
            'info',
            'Export Data Siswa PKL Approved',
            'Admin melakukan export data siswa yang sudah diterima PKL ke Excel',
            Session::get('loginId')
        );

        $filename = 'Data_Siswa_PKL_Approved_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new PengajuanApprovedExport, $filename);
    }
}
