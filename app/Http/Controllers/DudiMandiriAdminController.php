<?php

namespace App\Http\Controllers;

use App\Models\DudiMandiri;
use App\Models\tb_dudi;
use App\Models\User;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DudiMandiriAdminController extends Controller
{
    // Tampilkan semua DUDI Mandiri yang diajukan siswa
    public function index(Request $request)
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'admin') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai admin.');
        }

        // Query DUDI Mandiri dengan relasi siswa
        $query = DudiMandiri::with('siswa');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by nama DUDI atau siswa
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_dudi', 'like', "%{$search}%")
                    ->orWhereHas('siswa', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                    });
            });
        }

        $dudiMandiri = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get data admin
        $data = \App\Models\tb_admin::find($user->id_admin);

        return view('admin.kelola-dudi-mandiri', compact('dudiMandiri', 'data'));
    }

    // Approve DUDI Mandiri
    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $dudiMandiri = DudiMandiri::find($id);

            if (!$dudiMandiri) {
                return back()->with('error', 'DUDI Mandiri tidak ditemukan.');
            }

            // Cek apakah sudah pernah di-approve (sudah ada di tb_dudi)
            if ($dudiMandiri->id_dudi) {
                return back()->with('info', 'DUDI Mandiri ini sudah di-approve sebelumnya.');
            }

            // 1. Buat data di tb_dudi
            $dudi = tb_dudi::create([
                'nama_dudi' => $dudiMandiri->nama_dudi,
                'alamat' => $dudiMandiri->alamat,
                'nomor_telpon' => $dudiMandiri->nomor_telepon,
                'person_in_charge' => $dudiMandiri->person_in_charge,
                'jurusan' => null, // optional field
                'jobdesk' => null, // optional field
                'jenis_dudi' => 'mandiri', // DUDI dari pilihan mandiri siswa
            ]);

            // 2. Buat akun User untuk DUDI
            // Username = nama DUDI (bisa pakai spasi, lowercase)
            $username = strtolower(trim($dudiMandiri->nama_dudi));

            // Pastikan username tidak kosong
            if (empty($username)) {
                throw new \Exception('Nama DUDI tidak boleh kosong');
            }

            $defaultPassword = 'dudi123'; // Password default

            // Cek jika username sudah ada, tambahkan angka
            $usernameOriginal = $username;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $usernameOriginal . ' ' . $counter;
                $counter++;
            }

            $user = User::create([
                'username' => $username,
                'password' => Hash::make($defaultPassword),
                'role' => 'dudi',
                'id_dudi' => $dudi->id,
            ]);

            // 3. Update DUDI Mandiri dengan id_dudi yang baru dibuat
            $dudiMandiri->status = 'approved';
            $dudiMandiri->id_dudi = $dudi->id;
            $dudiMandiri->save();

            // 4. PENTING: Update semua pengajuan PKL yang menggunakan DUDI Mandiri ini
            // Cari pengajuan yang menggunakan DUDI Mandiri ini di pilihan 1, 2, atau 3
            $pengajuanList = PengajuanPkl::where('id_dudi_mandiri_pilihan_1', $dudiMandiri->id)
                ->orWhere('id_dudi_mandiri_pilihan_2', $dudiMandiri->id)
                ->orWhere('id_dudi_mandiri_pilihan_3', $dudiMandiri->id)
                ->get();

            foreach ($pengajuanList as $pengajuan) {
                // Pindahkan dari dudi_mandiri ke dudi
                if ($pengajuan->id_dudi_mandiri_pilihan_1 == $dudiMandiri->id) {
                    $pengajuan->id_dudi_pilihan_1 = $dudi->id;
                    $pengajuan->id_dudi_mandiri_pilihan_1 = null;
                }
                if ($pengajuan->id_dudi_mandiri_pilihan_2 == $dudiMandiri->id) {
                    $pengajuan->id_dudi_pilihan_2 = $dudi->id;
                    $pengajuan->id_dudi_mandiri_pilihan_2 = null;
                }
                if ($pengajuan->id_dudi_mandiri_pilihan_3 == $dudiMandiri->id) {
                    $pengajuan->id_dudi_pilihan_3 = $dudi->id;
                    $pengajuan->id_dudi_mandiri_pilihan_3 = null;
                }
                $pengajuan->save();
            }

            // 5. Auto-approve pengajuan PKL jika parameter pengajuan_id dikirim
            $autoApprovedPengajuan = null;
            if ($request->has('pengajuan_id') && $request->pengajuan_id) {
                $autoApprovedPengajuan = PengajuanPkl::find($request->pengajuan_id);
                if ($autoApprovedPengajuan) {
                    // Update status pilihan yang aktif
                    $pilihanAktif = $autoApprovedPengajuan->pilihan_aktif;

                    if ($pilihanAktif == '1') {
                        $autoApprovedPengajuan->status_pilihan_1 = 'approved';
                        $autoApprovedPengajuan->tanggal_response_pilihan_1 = now();
                    } elseif ($pilihanAktif == '2') {
                        $autoApprovedPengajuan->status_pilihan_2 = 'approved';
                        $autoApprovedPengajuan->tanggal_response_pilihan_2 = now();
                    } else {
                        $autoApprovedPengajuan->status_pilihan_3 = 'approved';
                        $autoApprovedPengajuan->tanggal_response_pilihan_3 = now();
                    }

                    // Approve pengajuan overall
                    $autoApprovedPengajuan->status = 'approved';
                    $autoApprovedPengajuan->save();

                    // Update siswa - assign ke DUDI
                    $siswa = $autoApprovedPengajuan->siswa;
                    $siswa->id_dudi = $dudi->id;
                    $siswa->status_penempatan = 'ditempatkan';
                    $siswa->save();

                    logActivity(
                        'update',
                        'Pengajuan PKL Disetujui (Auto)',
                        "Pengajuan PKL dari siswa {$siswa->nama} (NIS: {$siswa->nis}) disetujui otomatis setelah pembuatan akun DUDI {$dudi->nama_dudi}. Siswa ditempatkan di DUDI tersebut.",
                        Session::get('loginId')
                    );
                }
            }

            $siswa = $dudiMandiri->siswa;

            logActivity(
                'create',
                'DUDI Mandiri Disetujui & Akun Dibuat',
                "DUDI Mandiri '{$dudiMandiri->nama_dudi}' dari siswa {$siswa->nama} (NIS: {$siswa->nis}) disetujui. " .
                "Akun DUDI dibuat dengan username: {$username}, password: {$defaultPassword}. " .
                "Total {$pengajuanList->count()} pengajuan PKL telah diupdate." .
                ($autoApprovedPengajuan ? " Pengajuan PKL ID {$autoApprovedPengajuan->id} langsung diapprove." : ""),
                Session::get('loginId')
            );

            DB::commit();

            $successMessage =
                "<div class='alert alert-warning border-warning mb-3'>" .
                "<h5 class='alert-heading'><i class='fas fa-key'></i> SIMPAN KREDENSIAL INI!</h5>" .
                "<hr>" .
                "<div class='row'>" .
                "<div class='col-md-6'>" .
                "<strong>Username:</strong><br>" .
                "<div class='input-group mb-2'>" .
                "<input type='text' class='form-control' value='{$username}' id='copyUsername' readonly>" .
                "<button class='btn btn-outline-secondary' onclick=\"navigator.clipboard.writeText('{$username}'); alert('Username disalin!')\" type='button'>" .
                "<i class='fas fa-copy'></i></button>" .
                "</div>" .
                "</div>" .
                "<div class='col-md-6'>" .
                "<strong>Password:</strong><br>" .
                "<div class='input-group mb-2'>" .
                "<input type='text' class='form-control' value='{$defaultPassword}' id='copyPassword' readonly>" .
                "<button class='btn btn-outline-secondary' onclick=\"navigator.clipboard.writeText('{$defaultPassword}'); alert('Password disalin!')\" type='button'>" .
                "<i class='fas fa-copy'></i></button>" .
                "</div>" .
                "</div>" .
                "</div>" .
                "<small class='text-muted'><i class='fas fa-info-circle'></i> Kirimkan kredensial ini ke DUDI melalui WhatsApp/Email</small>" .
                "</div>" .
                "<div class='alert alert-info'>" .
                "<i class='fas fa-check-circle'></i> <strong>Berhasil!</strong><br>" .
                "• Akun DUDI <strong>{$dudiMandiri->nama_dudi}</strong> telah dibuat<br>" .
                "• {$pengajuanList->count()} pengajuan PKL telah diupdate" .
                ($autoApprovedPengajuan ? "<br>• Pengajuan PKL dari <strong>{$siswa->nama}</strong> telah diapprove dan dikirim ke DUDI" : "") .
                "</div>";

            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Reject DUDI Mandiri
    public function reject($id)
    {
        $dudiMandiri = DudiMandiri::find($id);

        if (!$dudiMandiri) {
            return back()->with('error', 'DUDI Mandiri tidak ditemukan.');
        }

        $dudiMandiri->status = 'rejected';
        $dudiMandiri->save();

        $siswa = $dudiMandiri->siswa;

        logActivity(
            'update',
            'DUDI Mandiri Ditolak',
            "DUDI Mandiri '{$dudiMandiri->nama_dudi}' dari siswa {$siswa->nama} (NIS: {$siswa->nis}) ditolak",
            Session::get('loginId')
        );

        return back()->with('success', 'DUDI Mandiri berhasil ditolak.');
    }

    // Delete DUDI Mandiri
    public function destroy($id)
    {
        $dudiMandiri = DudiMandiri::find($id);

        if (!$dudiMandiri) {
            return back()->with('error', 'DUDI Mandiri tidak ditemukan.');
        }

        $siswa = $dudiMandiri->siswa;
        $namaDudi = $dudiMandiri->nama_dudi;

        $dudiMandiri->delete();

        logActivity(
            'delete',
            'DUDI Mandiri Dihapus',
            "DUDI Mandiri '{$namaDudi}' dari siswa {$siswa->nama} (NIS: {$siswa->nis}) dihapus",
            Session::get('loginId')
        );

        return back()->with('success', 'DUDI Mandiri berhasil dihapus.');
    }
}
