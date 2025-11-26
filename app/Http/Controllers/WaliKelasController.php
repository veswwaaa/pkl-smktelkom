<?php

namespace App\Http\Controllers;

use App\Models\tb_siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WaliKelasController extends Controller
{
    /**
     * Display the wali kelas dashboard with student data
     */
    public function dashboard()
    {
        // Verify wali kelas is logged in
        if (!Session::has('loginId')) {
            return redirect('login')->with('fail', 'Please login first');
        }

        $user = User::find(Session::get('loginId'));

        if (!$user || $user->role !== 'wali_kelas') {
            return redirect('login')->with('fail', 'Unauthorized access');
        }

        // Get wali kelas data
        $data = null;
        $waliKelasKelas = null;
        if ($user->id_admin) {
            $data = \App\Models\tb_admin::find($user->id_admin);
            $waliKelasKelas = $data->kelas ?? null;
        }

        // Fetch students - filter by wali kelas's assigned kelas
        $siswaQuery = tb_siswa::with('dudi')
            ->orderBy('kelas', 'asc')
            ->orderBy('nama', 'asc');

        // If wali kelas has assigned kelas, filter students
        if ($waliKelasKelas) {
            $siswaQuery->where('kelas', $waliKelasKelas);
        }

        $siswa = $siswaQuery->get();

        // Get unique kelas and jurusan for filters (from filtered students)
        $kelasList = $siswa->pluck('kelas')->unique()->sort()->values();
        $jurusanList = $siswa->pluck('jurusan')->unique()->sort()->values();

        return view('dashboardWaliKelas', compact('data', 'siswa', 'kelasList', 'jurusanList'));
    }

    /**
     * Get student data (read-only)
     */
    public function getSiswaData()
    {
        if (!Session::has('loginId')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::find(Session::get('loginId'));

        if (!$user || $user->role !== 'wali_kelas') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get wali kelas's assigned kelas
        $waliKelasKelas = null;
        if ($user->id_admin) {
            $admin = \App\Models\tb_admin::find($user->id_admin);
            $waliKelasKelas = $admin->kelas ?? null;
        }

        // Fetch students - filter by wali kelas's kelas
        $siswaQuery = tb_siswa::with('dudi')
            ->orderBy('kelas', 'asc')
            ->orderBy('nama', 'asc');

        if ($waliKelasKelas) {
            $siswaQuery->where('kelas', $waliKelasKelas);
        }

        $siswa = $siswaQuery->get();

        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    }
}
