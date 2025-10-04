<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\tb_siswa;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $siswa = tb_siswa::all();
        return view('admin.kelola-siswa', compact('siswa'));

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
        try {
            //validasi input
            $request->validate([
                'nis' => 'required|numeric|digits:9|unique:tb_siswa,nis|unique:tb_users,username',
                'nama' => 'required|string|max:255',
                'kelas' => 'required|string|max:50',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'angkatan' => 'required|string|max:4',
                'jurusan' => 'required|string|max:20'
            ], [
                // pesan eror form data
                'nis.required' => 'NIS wajib diisi',
                'nis.numeric' => 'NIS harus berupa angka saja',
                'nis.digits' => 'NIS harus tepat 9 digit angka',
                'nis.unique' => 'NIS sudah terdaftar di sistem',
                'kelas.required' => 'Kelas wajib diisi',
            ]);


            // simpan data siswa
            $siswa = new tb_siswa();
            $siswa->nis = $request->nis;
            $siswa->nama = $request->nama;
            $siswa->kelas = $request->kelas;
            $siswa->jenis_kelamin = $request->jenis_kelamin;
            $siswa->angkatan = $request->angkatan;
            $siswa->jurusan = $request->jurusan;
            $siswa->save();


            // Generate password otomatis
            $password = 'dummy@' . $request->nis;


            //ngebuat akun usernya di tb_users untuk siswa
            $user = new User();
            $user->username = $request->nis;
            $user->password = Hash::make($password);
            $user->role = 'siswa';
            $user->id_admin = null;
            $user->id_dudi = null;
            $user->id_siswa = $siswa->id;
            $user->save();

            //ngebuat log untuk di tampilan activity nya
            logActivity(
                'create',
                'Siswa Baru Ditambahkan',
                "Siswa {$siswa->nama} (NIS : {$siswa->nis}) berhasil ditambahkan"
            );

            return redirect('/admin/siswa')->with('success', 'Siswa berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', ' Terjadi kesalahan! ' . $e->getMessage());

        }
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
        try {

            //valkidasi input
            $request->validate([
                'nis' => 'required|numeric|digits:9|unique:tb_siswa,nis,' . $id,
                'nama' => 'required|string|max:255',
                'kelas' => 'required|string|max:10',
                'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                'angkatan' => 'required|string|max:20',
                'jurusan' => 'required|string|max:20'

            ], [
                'nis.required' => 'NIS wajib diisi',
                'nis.numeric' => 'NIS harus berupa angka saja',
                'nis.digits' => 'NIS Tidak boleh lebih dari 9 digit',
                'nis.unique' => 'NIS sudah terdaftar di sistem',
                'nama.required' => 'Nama wajib diisi',
                'kelas.required' => 'Kelas wajib diisi',
            ]);


            //untuk updet data siswa
            $siswa = tb_siswa::find($id);
            $siswa->nis = $request->nis;
            $siswa->nama = $request->nama;
            $siswa->kelas = $request->kelas;
            $siswa->jenis_kelamin = $request->jenis_kelamin;
            $siswa->angkatan = $request->angkatan;
            $siswa->jurusan = $request->jurusan;
            $siswa->update();


            //update juga usernamenya dan passwordnya di tb_users jika nis nya di ubah
            $user = User::where('id_siswa', $id)->first();
            if ($user && $user->username != $request->nis) {
                $oldNis = $user->username;
                $newNis = $request->nis;

                // Update username
                $user->username = $newNis;

                // Update password juga dengan format dummy@NIS_BARU
                $newPassword = 'dummy@' . $newNis;
                $user->password = Hash::make($newPassword);

                $user->save();

                // Log perubahan NIS & Password
                logActivity(
                    'update',
                    'NIS & Password Diperbarui',
                    "NIS siswa {$siswa->nama} diubah dari {$oldNis} menjadi {$newNis}. Password otomatis diperbarui."
                );
            }


            //nyatat log di actibity
            logActivity(
                'update',
                'Data Siswa Diperbarui',
                "Data siswa {$siswa->nama} (NIS : {$siswa->nis}) berhasil diperbarui"
            );

            return redirect('/admin/siswa')->with('success', 'Data siswa berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', ' Terjadi kesalahan! ' . $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Cari data siswa berdasarkan ID
            $siswa = tb_siswa::findOrFail($id);

            // Simpan data untuk log sebelum dihapus
            $nama = $siswa->nama;
            $nis = $siswa->nis;

            // Hapus user account yang terkait
            $user = User::where('id_siswa', $id)->first();
            if ($user) {
                $user->delete();
            }

            // Hapus data siswa
            $siswa->delete();

            // Log activity penghapusan
            logActivity(
                'delete',
                'Siswa Dihapus',
                "Siswa {$nama} (NIS: {$nis}) berhasil dihapus dari sistem"
            );

            return redirect('/admin/siswa')->with('success', 'Siswa berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    //fungsi untuk import data siswa dari excel

    public function import(Request $request)
    {
        try {
            //validasi file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv|max:2048'
            ], [
                'file.required' => 'File Excel wajib diupload!',
                'file.mimes' => 'File harus berformat Excel (.xlsx, .xls, atau .csv)',
                'file.max' => 'Ukuran file maksimal 2MB'
            ]);

            //nge import filenya dari Excel
            $file = $request->file('file');

            Excel::import(new SiswaImport, $file);

            //nyatat log nya ke activity
            logActivity(
                'create',
                'Import Data Siswa',
                "Data siswa berhasil diimport dari file Excel"
            );

            return redirect('/admin/siswa')->with('success', 'Data siswa berhasil diimport dari Excel!');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            foreach ($failures as $failure) {
                $errorMessages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            $errorText = "❌ VALIDASI GAGAL!\n\n" . implode("\n", $errorMessages);
            $errorText .= "\n\n💡 TIP: Pastikan format Excel sesuai dengan contoh yang diberikan.";

            return redirect()->back()->with('error', $errorText);

        } catch (\Exception $e) {
            // Pesan error yang lebih user-friendly
            $errorMessage = $e->getMessage();

            // Jika error tentang header/format
            if (strpos($errorMessage, 'header') !== false || strpos($errorMessage, 'FORMAT') !== false) {
                $errorText = $errorMessage;
                $errorText .= "\n\n📋 FORMAT YANG BENAR:\n";
                $errorText .= "Baris 1 (Header): nis | nama | kelas | jenis_kelamin | angkatan | jurusan\n";
                $errorText .= "Baris 2 dst: Data siswa\n\n";
                $errorText .= "💡 TIP: Ketik header manual, jangan copy-paste!";
            } else {
                $errorText = $errorMessage;
            }

            return redirect()->back()->with('error', $errorText);
        }
    }
}
