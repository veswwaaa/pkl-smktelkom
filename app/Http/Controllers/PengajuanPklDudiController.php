<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPkl;
use App\Models\tb_siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengajuanPklDudiController extends Controller
{
    // List lamaran PKL yang masuk ke DUDI ini
    public function index()
    {
        $idDudi = Session::get('id');

        if (!$idDudi) {
            return redirect('/login')->with('error', 'Session expired. Silakan login kembali.');
        }

        // Ambil semua pengajuan yang pilihan aktifnya adalah DUDI ini
        $lamaran = PengajuanPkl::with(['siswa', 'dudiPilihan1', 'dudiPilihan2', 'dudiPilihan3'])
            ->where(function ($query) use ($idDudi) {
                $query->where(function ($q) use ($idDudi) {
                    $q->where('pilihan_aktif', '1')
                        ->where('id_dudi_pilihan_1', $idDudi);
                })
                    ->orWhere(function ($q) use ($idDudi) {
                        $q->where('pilihan_aktif', '2')
                            ->where('id_dudi_pilihan_2', $idDudi);
                    })
                    ->orWhere(function ($q) use ($idDudi) {
                        $q->where('pilihan_aktif', '3')
                            ->where('id_dudi_pilihan_3', $idDudi);
                    });
            })
            ->where('status', 'pending')
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10);

        return view('dudi.kelola-lamaran', compact('lamaran'));
    }

    // Approve lamaran
    public function approve(Request $request, $id)
    {
        $pengajuan = PengajuanPkl::with('siswa')->find($id);

        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        $idDudi = Session::get('id');
        $pilihanAktif = $pengajuan->pilihan_aktif;

        // Update status pilihan yang aktif
        $statusColumn = 'status_pilihan_' . $pilihanAktif;
        $tanggalColumn = 'tanggal_response_pilihan_' . $pilihanAktif;

        $pengajuan->$statusColumn = 'approved';
        $pengajuan->$tanggalColumn = now();
        $pengajuan->status = 'approved'; // Status keseluruhan juga approved
        $pengajuan->save();

        // Update siswa - assign ke DUDI
        $siswa = tb_siswa::find($pengajuan->id_siswa);
        $siswa->id_dudi = $idDudi;
        $siswa->status_penempatan = 'ditempatkan';
        $siswa->save();

        logActivity(
            'update',
            'Lamaran PKL Disetujui',
            "DUDI menyetujui lamaran PKL dari siswa {$siswa->nama} (NIS: {$siswa->nis})",
            Session::get('loginId')
        );

        return back()->with('success', 'Lamaran berhasil disetujui! Siswa telah ditempatkan di perusahaan Anda.');
    }

    // Reject lamaran - otomatis pindah ke pilihan berikutnya
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500'
        ]);

        $pengajuan = PengajuanPkl::with('siswa')->find($id);

        if (!$pengajuan) {
            return back()->with('error', 'Pengajuan tidak ditemukan.');
        }

        $pilihanAktif = $pengajuan->pilihan_aktif;

        // Update status pilihan yang ditolak
        $statusColumn = 'status_pilihan_' . $pilihanAktif;
        $tanggalColumn = 'tanggal_response_pilihan_' . $pilihanAktif;
        $catatanColumn = 'catatan_pilihan_' . $pilihanAktif;

        $pengajuan->$statusColumn = 'rejected';
        $pengajuan->$tanggalColumn = now();
        $pengajuan->$catatanColumn = $request->catatan;

        // Cek apakah ada pilihan berikutnya
        $nextPilihan = null;

        if ($pilihanAktif == '1') {
            // Cek pilihan 2
            if ($pengajuan->id_dudi_pilihan_2 || $pengajuan->id_dudi_mandiri_pilihan_2) {
                $nextPilihan = '2';
            } elseif ($pengajuan->id_dudi_pilihan_3 || $pengajuan->id_dudi_mandiri_pilihan_3) {
                $nextPilihan = '3';
            }
        } elseif ($pilihanAktif == '2') {
            // Cek pilihan 3
            if ($pengajuan->id_dudi_pilihan_3 || $pengajuan->id_dudi_mandiri_pilihan_3) {
                $nextPilihan = '3';
            }
        }

        if ($nextPilihan) {
            // Ada pilihan berikutnya, pindah ke sana
            $pengajuan->pilihan_aktif = $nextPilihan;
            $pengajuan->save();

            $message = "Lamaran ditolak. Pengajuan otomatis dipindahkan ke pilihan {$nextPilihan}.";
        } else {
            // Tidak ada pilihan berikutnya, set status keseluruhan menjadi rejected
            $pengajuan->status = 'rejected';
            $pengajuan->save();

            $message = "Lamaran ditolak. Tidak ada pilihan lain yang tersedia.";
        }

        $siswa = $pengajuan->siswa;

        logActivity(
            'update',
            'Lamaran PKL Ditolak',
            "DUDI menolak lamaran PKL dari siswa {$siswa->nama} (NIS: {$siswa->nis}). " . ($nextPilihan ? "Dipindah ke pilihan {$nextPilihan}" : "Semua pilihan habis"),
            Session::get('loginId')
        );

        return back()->with('success', $message);
    }
}
