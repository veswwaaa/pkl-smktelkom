<?php

namespace App\Http\Controllers;

use App\Models\tb_siswa;
use App\Models\tb_dudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class InfoPklSiswaController extends Controller
{
    public function index()
    {
        $idSiswa = Session::get('id_siswa');

        if (!$idSiswa) {
            return redirect('/login')->with('error', 'Session expired. Silakan login kembali.');
        }

        $siswa = tb_siswa::with('dudi')->find($idSiswa);

        if (!$siswa) {
            return redirect('/dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        return view('siswa.info-pkl', compact('siswa'));
    }
}
