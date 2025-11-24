<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\tb_siswa;
use App\Models\PengajuanPkl;

class UploadDokumenController extends Controller
{
    public function index()
    {
        $loginId = Session::get('loginId');
        $siswa = tb_siswa::where('id', $loginId)->first();

        if (!$siswa) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Get pengajuan PKL siswa
        $pengajuan = PengajuanPkl::where('id_siswa', $siswa->id)
            ->first();

        return view('siswa.upload-dokumen', compact('siswa', 'pengajuan'));
    }

    public function upload(Request $request)
    {
        try {
            $loginId = Session::get('loginId');
            $siswa = tb_siswa::where('id', $loginId)->first();

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login terlebih dahulu'
                ], 401);
            }

            // Get pengajuan PKL siswa
            $pengajuan = PengajuanPkl::where('id_siswa', $siswa->id)->first();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda belum memiliki pengajuan PKL'
                ], 400);
            }

            if ($pengajuan->status != 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan PKL Anda belum disetujui'
                ], 400);
            }

            $type = $request->input('type');

            // Validate file
            $request->validate([
                'file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
                'type' => 'required|in:cv,surat'
            ]);

            $file = $request->file('file');

            // Delete old file if exists
            if ($type == 'cv' && $pengajuan->cv_file) {
                Storage::delete('public/cv/' . $pengajuan->cv_file);
            } elseif ($type == 'surat' && $pengajuan->surat_balasan) {
                Storage::delete('public/surat_balasan/' . $pengajuan->surat_balasan);
            }

            // Store new file
            $folder = $type == 'cv' ? 'cv' : 'surat_balasan';
            $filename = time() . '_' . $siswa->nis . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/' . $folder, $filename);

            // Update pengajuan
            if ($type == 'cv') {
                $pengajuan->cv_file = $filename;
                $displayName = 'CV';
            } else {
                $pengajuan->surat_balasan = $filename;
                $displayName = 'Surat Balasan';
            }

            $pengajuan->save();

            // Log activity
            logActivity(
                'update',
                'Upload ' . $displayName,
                'Siswa ' . $siswa->nama . ' (NIS: ' . $siswa->nis . ') berhasil mengupload ' . $displayName,
                Session::get('loginId')
            );

            return response()->json([
                'success' => true,
                'message' => $displayName . ' berhasil diupload!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid. Pastikan format PDF/DOC/DOCX dan ukuran maksimal 5MB'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function download($type)
    {
        try {
            $loginId = Session::get('loginId');
            $siswa = tb_siswa::where('id', $loginId)->first();

            if (!$siswa) {
                return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
            }

            $pengajuan = PengajuanPkl::where('id_siswa', $siswa->id)->first();

            if (!$pengajuan) {
                return redirect()->back()->with('error', 'Pengajuan tidak ditemukan');
            }

            $folder = $type == 'cv' ? 'cv' : 'surat_balasan';
            $filename = $type == 'cv' ? $pengajuan->cv_file : $pengajuan->surat_balasan;

            if (!$filename) {
                return redirect()->back()->with('error', 'File tidak ditemukan');
            }

            $path = storage_path('app/public/' . $folder . '/' . $filename);

            if (!file_exists($path)) {
                return redirect()->back()->with('error', 'File tidak tersedia di server');
            }

            return response()->download($path);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
