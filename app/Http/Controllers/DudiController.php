<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_dudi;
use App\Models\User;
use App\Models\SuratDudi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class DudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = tb_dudi::query();

        // Filter by jenis_dudi
        if ($request->has('jenis_dudi') && $request->jenis_dudi != '') {
            $query->where('jenis_dudi', $request->jenis_dudi);
        }

        $dudi = $query->get();

        return view('admin.kelola-dudi', compact('dudi'));
    }    /**
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
            $dudi->jenis_dudi = 'sekolah'; // DUDI yang ditambahkan manual oleh admin = DUDI Sekolah
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

            // Log activity
            logActivity(
                'create',
                'DUDI Baru Ditambahkan',
                "DUDI {$dudi->nama_dudi} berhasil didaftarkan dengan PIC: {$dudi->person_in_charge}"
            );

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'DUDI baru berhasil ditambahkan!'
                ]);
            }

            return redirect('/admin/dudi')->with('success', 'DUDI baru berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

                // Update password only if provided
                if ($request->password) {
                    $request->validate([
                        'password' => 'min:8|max:20',
                    ]);
                    $user->password = Hash::make($request->password);
                }

                $user->save();
            }

            // Log activity
            logActivity(
                'update',
                'Data DUDI Diperbarui',
                "Data DUDI {$dudi->nama_dudi} telah diperbarui"
            );

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'DUDI berhasil diperbarui!'
                ]);
            }

            return redirect('/admin/dudi')->with('success', 'DUDI berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //cari data dudinya yang mau di hapus
            $dudi = tb_dudi::FindOrFail($id);
            $namaDudi = $dudi->nama_dudi;

            //hapus user yang terkait
            User::where('id_dudi', $id)->delete();

            //hapus data dudi
            $dudi->delete();

            // Log activity
            logActivity(
                'delete',
                'DUDI Dihapus',
                "DUDI {$namaDudi} telah dihapus dari sistem"
            );

            // Check if request is AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'DUDI berhasil dihapus!'
                ]);
            }

            // Redirect dengan pesan success
            return redirect('/admin/dudi')->with('success', 'DUDI berhasil dihapus!');

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reset password DUDI
     */
    public function resetPassword(string $id)
    {
        try {
            // Cari data DUDI
            $dudi = tb_dudi::findOrFail($id);

            // Generate password baru
            $newPassword = $this->generatePassword();

            // Update password di tb_users
            $user = User::where('id_dudi', $id)->first();
            if (!$user) {
                throw new \Exception('User DUDI tidak ditemukan');
            }

            $user->password = Hash::make($newPassword);
            $user->save();

            // Log activity
            logActivity(
                'update',
                'Password DUDI Direset',
                "Password DUDI {$dudi->nama_dudi} telah direset oleh admin"
            );

            // Return response dengan password baru
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil direset!',
                    'new_password' => $newPassword,
                    'dudi_name' => $dudi->nama_dudi
                ]);
            }

            return redirect('/admin/dudi')->with([
                'success' => 'Password berhasil direset!',
                'new_password' => $newPassword,
                'dudi_name' => $dudi->nama_dudi
            ]);

        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Generate random password untuk DUDI
     */
    private function generatePassword()
    {
        $prefix = 'DUDI2025';
        $randomChars = strtoupper(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 4));
        return $prefix . $randomChars;
    }

    /**
     * Upload surat pengajuan untuk DUDI
     */
    public function uploadSurat(Request $request)
    {
        try {
            $request->validate([
                'id_dudi' => 'required|exists:tb_dudi,id',
                'file_surat' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
                'catatan_admin' => 'nullable|string'
            ]);

            // Get logged in user
            $user = \App\Models\User::where('id', Session::get('loginId'))->first();

            if (!$user || $user->role != 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Handle file upload
            $file = $request->file('file_surat');
            $fileName = 'surat_pengajuan_dudi_' . $request->id_dudi . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/surat_dudi', $fileName);

            // Cek apakah sudah ada surat untuk DUDI ini
            $suratDudi = SuratDudi::where('id_dudi', $request->id_dudi)->first();

            if ($suratDudi) {
                // Update existing record
                // Delete old file if exists
                if ($suratDudi->file_surat_pengajuan && Storage::exists('public/surat_dudi/' . $suratDudi->file_surat_pengajuan)) {
                    Storage::delete('public/surat_dudi/' . $suratDudi->file_surat_pengajuan);
                }

                $suratDudi->file_surat_pengajuan = $fileName;
                $suratDudi->tanggal_upload_pengajuan = now();
                $suratDudi->uploaded_by_admin = $user->id_admin;
                $suratDudi->catatan_admin = $request->catatan_admin;
                $suratDudi->save();
            } else {
                // Create new record
                $suratDudi = SuratDudi::create([
                    'id_dudi' => $request->id_dudi,
                    'file_surat_pengajuan' => $fileName,
                    'tanggal_upload_pengajuan' => now(),
                    'uploaded_by_admin' => $user->id_admin,
                    'catatan_admin' => $request->catatan_admin
                ]);
            }

            // Get dudi name for logging
            $dudi = tb_dudi::find($request->id_dudi);

            // Log activity
            logActivity(
                'upload',
                'Surat Pengajuan PKL Diupload',
                "Surat pengajuan PKL untuk DUDI {$dudi->nama_dudi} berhasil diupload"
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Surat pengajuan berhasil diupload!'
                ]);
            }

            return redirect('/admin/dudi')->with('success', 'Surat pengajuan berhasil diupload!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
