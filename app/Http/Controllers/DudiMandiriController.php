<?php

namespace App\Http\Controllers;

use App\Models\DudiMandiri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DudiMandiriController extends Controller
{
    // Simpan DUDI Mandiri yang diinput oleh siswa
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_dudi' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:20',
            'person_in_charge' => 'required|string|max:255',
            'alamat' => 'required|string'
        ]);

        // Ambil id_siswa dari session
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || !$user->id_siswa) {
            return back()->with('error', 'Anda harus login sebagai siswa untuk menambahkan DUDI mandiri.');
        }

        // Simpan data DUDI Mandiri
        $dudiMandiri = DudiMandiri::create([
            'id_siswa' => $user->id_siswa,
            'nama_dudi' => $request->nama_dudi,
            'nomor_telepon' => $request->nomor_telepon,
            'person_in_charge' => $request->person_in_charge,
            'alamat' => $request->alamat,
            'status' => 'pending'
        ]);

        // Log activity
        logActivity(
            'create',
            'DUDI Mandiri Ditambahkan',
            "Siswa menambahkan DUDI Mandiri: {$dudiMandiri->nama_dudi}",
            $user->id
        );

        return back()->with('success', 'DUDI Mandiri berhasil ditambahkan! Data akan muncul di dropdown pilihan.');
    }

    // Ambil DUDI Mandiri milik siswa yang login (untuk dropdown)
    public function getByCurrentSiswa()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || !$user->id_siswa) {
            return response()->json([]);
        }

        $dudiMandiri = DudiMandiri::where('id_siswa', $user->id_siswa)->get();

        return response()->json($dudiMandiri);
    }

    // Hapus DUDI Mandiri
    public function destroy($id)
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || !$user->id_siswa) {
            return back()->with('error', 'Unauthorized');
        }

        $dudiMandiri = DudiMandiri::where('id', $id)
            ->where('id_siswa', $user->id_siswa)
            ->first();

        if (!$dudiMandiri) {
            return back()->with('error', 'DUDI Mandiri tidak ditemukan.');
        }

        $namaContent = $dudiMandiri->nama_dudi;
        $dudiMandiri->delete();

        logActivity(
            'delete',
            'DUDI Mandiri Dihapus',
            "Siswa menghapus DUDI Mandiri: {$namaContent}",
            $user->id
        );

        return back()->with('success', 'DUDI Mandiri berhasil dihapus.');
    }
}
