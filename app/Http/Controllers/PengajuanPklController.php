<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPkl;
use App\Models\tb_siswa;
use App\Models\tb_dudi;
use App\Models\DudiMandiri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengajuanPklController extends Controller
{
    // Tampilkan halaman pengajuan PKL untuk siswa
    public function index()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || !$user->id_siswa) {
            return redirect('/login')->with('fail', 'Anda harus login sebagai siswa.');
        }

        $data = tb_siswa::find($user->id_siswa);

        // Ambil DUDI Sekolah (yang jenis_dudi = 'sekolah')
        $dudiSekolah = tb_dudi::where('jenis_dudi', 'sekolah')->get();

        // Ambil DUDI Mandiri milik siswa ini yang SUDAH approved (sudah punya akun)
        // DUDI Mandiri tetap privat, hanya muncul untuk siswa yang membuatnya
        $dudiMandiriApproved = DudiMandiri::where('id_siswa', $user->id_siswa)
            ->whereNotNull('id_dudi') // Yang sudah punya akun DUDI
            ->with('dudi') // Eager load relasi ke tb_dudi
            ->get()
            ->pluck('dudi') // Ambil data DUDI-nya
            ->filter(); // Hapus null values

        // Ambil DUDI Mandiri milik siswa ini saja yang BELUM approved (belum punya akun)
        $dudiMandiri = DudiMandiri::where('id_siswa', $user->id_siswa)
            ->whereNull('id_dudi') // Hanya yang belum punya akun DUDI
            ->get();

        // Cek apakah siswa sudah punya pengajuan PKL
        $pengajuan = PengajuanPkl::where('id_siswa', $user->id_siswa)->first();

        return view('siswa.pengajuan-pkl', compact('data', 'dudiSekolah', 'dudiMandiriApproved', 'dudiMandiri', 'pengajuan'));
    }

    // Simpan pengajuan PKL
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'pilihan_1' => 'required',
            'pilihan_2' => 'required',
            'pilihan_3' => 'required',
        ]);

        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || !$user->id_siswa) {
            return back()->with('error', 'Anda harus login sebagai siswa.');
        }

        // Ambil id_siswa dari user yang login
        $idSiswa = $user->id_siswa;

        // Cek apakah siswa sudah punya pengajuan
        $existingPengajuan = PengajuanPkl::where('id_siswa', $idSiswa)->first();

        if ($existingPengajuan) {
            return back()->with('error', 'Anda sudah mengajukan PKL sebelumnya.');
        }

        // Prepare data
        $data = [
            'id_siswa' => $idSiswa,
            'tanggal_pengajuan' => now()->toDateString(),
            'status' => 'pending',
            'pilihan_aktif' => '1'
        ];

        // Parse pilihan 1 (format: "sekolah-1" atau "mandiri-1")
        list($type1, $id1) = explode('-', $request->pilihan_1);
        if ($type1 == 'sekolah') {
            $data['id_dudi_pilihan_1'] = $id1;
            $data['id_dudi_mandiri_pilihan_1'] = null;
        } else {
            $data['id_dudi_pilihan_1'] = null;
            $data['id_dudi_mandiri_pilihan_1'] = $id1;
        }

        // Parse pilihan 2
        list($type2, $id2) = explode('-', $request->pilihan_2);
        if ($type2 == 'sekolah') {
            $data['id_dudi_pilihan_2'] = $id2;
            $data['id_dudi_mandiri_pilihan_2'] = null;
        } else {
            $data['id_dudi_pilihan_2'] = null;
            $data['id_dudi_mandiri_pilihan_2'] = $id2;
        }

        // Parse pilihan 3
        list($type3, $id3) = explode('-', $request->pilihan_3);
        if ($type3 == 'sekolah') {
            $data['id_dudi_pilihan_3'] = $id3;
            $data['id_dudi_mandiri_pilihan_3'] = null;
        } else {
            $data['id_dudi_pilihan_3'] = null;
            $data['id_dudi_mandiri_pilihan_3'] = $id3;
        }

        // Simpan
        $pengajuan = PengajuanPkl::create($data);

        $siswa = tb_siswa::find($idSiswa);

        logActivity(
            'create',
            'Pengajuan PKL Dibuat',
            "Siswa {$siswa->nama} (NIS: {$siswa->nis}) mengajukan PKL",
            $user->id
        );

        return back()->with('success', 'Pengajuan PKL berhasil dikirim! Menunggu proses dari Admin.');
    }
}
