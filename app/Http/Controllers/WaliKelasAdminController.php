<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tb_admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WaliKelasAdminController extends Controller
{
    /**
     * Display a listing of wali kelas
     */
    public function index(Request $request)
    {
        // Get all wali kelas (admin with role wali_kelas in users table)
        $waliKelasUsers = User::where('role', 'wali_kelas')
            ->with('admin')
            ->get();

        $waliKelas = $waliKelasUsers->map(function ($user) {
            return $user->admin;
        })->filter();

        // Get unique kelas list for dropdown - sesuaikan dengan format di database (pakai spasi, bukan strip)
        $kelasList = ['XII A', 'XII B', 'XII C', 'XII D', 'XII E', 'XII F', 'XII G'];

        return view('admin.kelola-wali-kelas', compact('waliKelas', 'kelasList'));
    }

    /**
     * Store a newly created wali kelas
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nip' => 'required|unique:tb_admin,nip|unique:tb_users,username',
                'nama_admin' => 'required',
                'no_telpon' => 'required',
                'alamat' => 'required',
                'kelas' => 'required',
            ], [
                'nip.unique' => 'NIP sudah terdaftar! Gunakan NIP yang berbeda.',
                'kelas.required' => 'Kelas wajib dipilih.',
            ]);

            // Buat data wali kelas di tb_admin
            $waliKelas = new tb_admin();
            $waliKelas->nip = $request->nip;
            $waliKelas->nama_admin = $request->nama_admin;
            $waliKelas->no_telpon = $request->no_telpon;
            $waliKelas->alamat = $request->alamat;
            $waliKelas->kelas = $request->kelas;
            $waliKelas->save();

            // Buat akun user untuk wali kelas
            // Username = NIP, Password = dummy@NIP
            $user = new User();
            $user->username = $request->nip;
            $user->password = Hash::make('dummy@' . $request->nip);
            $user->role = 'wali_kelas';
            $user->id_admin = $waliKelas->id;
            $user->id_dudi = null;
            $user->id_siswa = null;
            $user->save();

            // Log activity
            logActivity(
                'create',
                'Wali Kelas Baru Ditambahkan',
                "Wali Kelas {$waliKelas->nama_admin} (NIP: {$waliKelas->nip}) berhasil didaftarkan"
            );

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Wali Kelas baru berhasil ditambahkan!',
                    'default_password' => 'dummy@' . $request->nip
                ]);
            }

            return redirect('/admin/wali-kelas')->with('success', 'Wali Kelas baru berhasil ditambahkan!');

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
     * Update the specified wali kelas
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'nip' => 'required',
                'nama_admin' => 'required',
                'no_telpon' => 'required',
                'alamat' => 'required',
                'kelas' => 'required',
            ], [
                'kelas.required' => 'Kelas wajib dipilih.',
            ]);

            $waliKelas = tb_admin::findOrFail($id);
            $waliKelas->update([
                'nip' => $request->nip,
                'nama_admin' => $request->nama_admin,
                'no_telpon' => $request->no_telpon,
                'alamat' => $request->alamat,
                'kelas' => $request->kelas,
            ]);

            // Update username di tb_users (username = NIP)
            $user = User::where('id_admin', $id)->where('role', 'wali_kelas')->first();
            if ($user) {
                $user->username = $request->nip;
                $user->save();
            }

            // Log activity
            logActivity(
                'update',
                'Data Wali Kelas Diperbarui',
                "Data Wali Kelas {$waliKelas->nama_admin} (NIP: {$waliKelas->nip}) telah diperbarui"
            );

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Wali Kelas berhasil diperbarui!'
                ]);
            }

            return redirect('/admin/wali-kelas')->with('success', 'Wali Kelas berhasil diperbarui!');

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
     * Remove the specified wali kelas
     */
    public function destroy(string $id)
    {
        try {
            // Cari data wali kelas yang mau dihapus
            $waliKelas = tb_admin::findOrFail($id);
            $namaWaliKelas = $waliKelas->nama_admin;
            $nipWaliKelas = $waliKelas->nip;

            // Hapus user yang terkait
            User::where('id_admin', $id)->where('role', 'wali_kelas')->delete();

            // Hapus data wali kelas
            $waliKelas->delete();

            // Log activity
            logActivity(
                'delete',
                'Wali Kelas Dihapus',
                "Wali Kelas {$namaWaliKelas} (NIP: {$nipWaliKelas}) telah dihapus dari sistem"
            );

            // Check if request is AJAX
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Wali Kelas berhasil dihapus!'
                ]);
            }

            return redirect('/admin/wali-kelas')->with('success', 'Wali Kelas berhasil dihapus!');

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
     * Reset password wali kelas to dummy@NIP
     */
    public function resetPassword(string $id)
    {
        try {
            // Cari data wali kelas
            $waliKelas = tb_admin::findOrFail($id);

            if (!$waliKelas->nip) {
                throw new \Exception('NIP wali kelas tidak ditemukan');
            }

            // Generate password baru: dummy@NIP
            $newPassword = 'dummy@' . $waliKelas->nip;

            // Update password di tb_users
            $user = User::where('id_admin', $id)->where('role', 'wali_kelas')->first();
            if (!$user) {
                throw new \Exception('User Wali Kelas tidak ditemukan');
            }

            $user->password = Hash::make($newPassword);
            $user->save();

            // Log activity
            logActivity(
                'update',
                'Password Wali Kelas Direset',
                "Password Wali Kelas {$waliKelas->nama_admin} (NIP: {$waliKelas->nip}) telah direset oleh admin"
            );

            // Return response dengan password baru
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil direset!',
                    'new_password' => $newPassword,
                    'wali_kelas_name' => $waliKelas->nama_admin,
                    'nip' => $waliKelas->nip
                ]);
            }

            return redirect('/admin/wali-kelas')->with([
                'success' => 'Password berhasil direset!',
                'new_password' => $newPassword,
                'wali_kelas_name' => $waliKelas->nama_admin
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
}
