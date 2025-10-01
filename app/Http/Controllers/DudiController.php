<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_dudi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dudi = tb_dudi::all();
        return view('admin.kelola-dudi', compact('dudi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            //validasi inputan data
            'nama_dudi' => 'required|unique:tb_users,username',
            'nomor_telpon' => 'required',
            'alamat' => 'required',
            'person_in_charge' => 'required',
            'password' => 'required|min:8|max:20',
        ], [
            // pesan eror form data
            'nama_dudi.unique' => 'Nama DUDI sudah terdaftar! Gunakan nama yang berbeda.',
        ]);

        //nyimpan data dudinya
        $dudi = new tb_dudi();
        $dudi->nama_dudi = $request->nama_dudi;
        $dudi->nomor_telpon = $request->nomor_telpon;
        $dudi->alamat = $request->alamat;
        $dudi->person_in_charge = $request->person_in_charge;
        $dudi->save();

        //buat akun user untuk dudi
        $user = new User();
        $user->username = $dudi->nama_dudi;
        $user->password = Hash::make($request->password);
        $user->role = 'dudi';
        $user->id_admin = null;
        $user->id_dudi = $dudi->id;
        $user->id_siswa = null;
        $user->save();

        return redirect('/admin/dudi')->with('success', 'DUDI baru berhasil ditambahkan!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            //validasi pas menginputkan datanya
            'nama_dudi' => 'required',
            'nomor_telpon' => 'required',
            'alamat' => 'required',
            'person_in_charge' => 'required',

        ]);

        $dudi = tb_dudi::findOrFail($id);
        $dudi->update([
            'nama_dudi' => $request->nama_dudi,
            'nomor_telpon' => $request->nomor_telpon,
            'alamat' => $request->alamat,
            'person_in_charge' => $request->person_in_charge,
        ]);

        // 4. UPDATE USERNAME DI TB_USERS
        $user = User::where('id_dudi', $id)->first();
        if ($user) {
            $user->username = $request->nama_dudi;
            $user->save();
        }

        return redirect('/admin/dudi')->with('success', 'DUDI berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //cari data dudinya yang mau di hapus

        $dudi = tb_dudi::FindOrFail($id);

        //hapus user yang terkait
        User::where('id_dudi', $id)->delete();

        //hapus data dudi
        $dudi->delete();

        // Redirect dengan pesan success
        return redirect('/admin/dudi')->with('success', 'DUDI berhasil dihapus!');
    }
}
