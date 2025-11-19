<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\tb_siswa;
use App\Models\PengajuanPkl;
use App\Models\User;

class StatusPengajuanController extends Controller
{
    public function index()
    {
        $loginId = Session::get('loginId');

        // Cek user dari tabel users
        $user = User::where('id', $loginId)->first();

        if (!$user || $user->role !== 'siswa') {
            return redirect('/login')->with('error', 'Halaman ini hanya untuk siswa. Silakan login sebagai siswa.');
        }

        // Ambil data siswa dari relasi
        $siswa = tb_siswa::find($user->id_siswa);

        if (!$siswa) {
            return redirect('/dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Get pengajuan PKL siswa dengan relasi ke semua DUDI
        $pengajuan = PengajuanPkl::where('id_siswa', $siswa->id)
            ->with([
                'dudiPilihan1',
                'dudiPilihan2',
                'dudiPilihan3',
                'dudiMandiriPilihan1',
                'dudiMandiriPilihan2',
                'dudiMandiriPilihan3'
            ])
            ->first();

        return view('siswa.status-pengajuan', compact('siswa', 'pengajuan'));
    }
}
