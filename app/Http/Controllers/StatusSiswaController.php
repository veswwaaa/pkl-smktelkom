<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\tb_siswa;

class StatusSiswaController extends Controller
{
    public function index()
    {
        if (!Session::has('loginId')) {
            return redirect('/login');
        }

        $user = User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role !== 'siswa' || !$user->id_siswa) {
            return redirect('/dashboard')->with('error', 'Akses ditolak.');
        }

        $data = tb_siswa::find($user->id_siswa);

        // Ambil pengajuan PKL siswa jika ada
        $pengajuan = \App\Models\PengajuanPkl::with([
            'dudiPilihan1',
            'dudiPilihan2',
            'dudiPilihan3',
            'dudiMandiriPilihan1',
            'dudiMandiriPilihan2',
            'dudiMandiriPilihan3'
        ])
            ->where('id_siswa', $data->id)
            ->first();

        return view('siswa.status-siswa', compact('data', 'pengajuan'));
    }
}
