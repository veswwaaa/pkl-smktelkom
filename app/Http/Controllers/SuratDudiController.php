<?php

namespace App\Http\Controllers;

use App\Models\SuratDudi;
use App\Models\tb_dudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratDudiController extends Controller
{
    // Dashboard DUDI
    public function dashboard()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'dudi') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai DUDI.');
        }

        $dudi = tb_dudi::find($user->id_dudi);

        if (!$dudi) {
            return redirect('/login')->with('fail', 'Data DUDI tidak ditemukan.');
        }

        // Ambil surat untuk DUDI ini
        $surat = SuratDudi::where('id_dudi', $dudi->id)->first();

        // Get pengajuan PKL yang mendaftar ke DUDI ini
        $pengajuanList = collect();
        $pengajuanCount = 0;

        if ($dudi->jenis_dudi == 'mandiri') {
            $dudiMandiriIds = \App\Models\DudiMandiri::where('id_dudi', $dudi->id)
                ->pluck('id')
                ->toArray();

            if (!empty($dudiMandiriIds)) {
                $pengajuanList = \App\Models\PengajuanPkl::where(function ($query) use ($dudiMandiriIds) {
                    foreach ($dudiMandiriIds as $dmId) {
                        $query->orWhere('id_dudi_mandiri_pilihan_1', $dmId)
                            ->orWhere('id_dudi_mandiri_pilihan_2', $dmId)
                            ->orWhere('id_dudi_mandiri_pilihan_3', $dmId);
                    }
                })
                    ->with('siswa')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            }
        } else {
            $pengajuanList = \App\Models\PengajuanPkl::where(function ($query) use ($dudi) {
                $query->where('id_dudi_pilihan_1', $dudi->id)
                    ->orWhere('id_dudi_pilihan_2', $dudi->id)
                    ->orWhere('id_dudi_pilihan_3', $dudi->id);
            })
                ->with('siswa')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        $pengajuanCount = $pengajuanList->count();

        return view('dudi.dashboard', compact('dudi', 'surat', 'pengajuanList', 'pengajuanCount'));
    }

    // Halaman Surat Pengajuan
    public function indexPengajuan()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'dudi') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai DUDI.');
        }

        $dudi = tb_dudi::find($user->id_dudi);

        if (!$dudi) {
            return redirect('/login')->with('fail', 'Data DUDI tidak ditemukan.');
        }

        $surat = SuratDudi::where('id_dudi', $dudi->id)->first();

        $filePengajuanExists = false;
        $fileBalasanPengajuanExists = false;

        if ($surat) {
            if ($surat->file_surat_pengajuan) {
                $filePengajuanExists = Storage::exists('public/surat_dudi/' . $surat->file_surat_pengajuan);
            }
            if ($surat->file_balasan_pengajuan) {
                $fileBalasanPengajuanExists = Storage::exists('public/surat_dudi/' . $surat->file_balasan_pengajuan);
            }
        }

        return view('dudi.surat-pengajuan', compact('dudi', 'surat', 'filePengajuanExists', 'fileBalasanPengajuanExists'));
    }

    // Halaman Surat Permohonan
    public function indexPermohonan()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'dudi') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai DUDI.');
        }

        $dudi = tb_dudi::find($user->id_dudi);

        if (!$dudi) {
            return redirect('/login')->with('fail', 'Data DUDI tidak ditemukan.');
        }

        $surat = SuratDudi::where('id_dudi', $dudi->id)->first();

        $filePermohonanExists = false;
        $fileBalasanPermohonanExists = false;

        if ($surat) {
            if ($surat->file_surat_permohonan) {
                $filePermohonanExists = Storage::exists('public/surat_dudi/' . $surat->file_surat_permohonan);
            }
            if ($surat->file_balasan_permohonan) {
                $fileBalasanPermohonanExists = Storage::exists('public/surat_dudi/' . $surat->file_balasan_permohonan);
            }
        }

        return view('dudi.surat-permohonan', compact('dudi', 'surat', 'filePermohonanExists', 'fileBalasanPermohonanExists'));
    }

    // Halaman surat untuk DUDI (OLD - for compatibility)
    public function indexDudi()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'dudi') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai DUDI.');
        }

        $dudi = tb_dudi::find($user->id_dudi);

        if (!$dudi) {
            return redirect('/login')->with('fail', 'Data DUDI tidak ditemukan.');
        }

        // Ambil surat untuk DUDI ini
        $surat = SuratDudi::where('id_dudi', $dudi->id)
            ->with(['dudi', 'admin'])
            ->first();

        // Check if files exist on disk
        $filePengajuanExists = false;
        $fileBalasanPengajuanExists = false;
        $filePermohonanExists = false;
        $fileBalasanPermohonanExists = false;

        if ($surat) {
            if ($surat->file_surat_pengajuan) {
                $filePengajuanExists = Storage::exists('public/surat_dudi/' . $surat->file_surat_pengajuan);
            }
            if ($surat->file_surat_balasan) {
                $fileBalasanPengajuanExists = Storage::exists('public/surat_dudi/' . $surat->file_surat_balasan);
            }
            if ($surat->file_surat_permohonan) {
                $filePermohonanExists = Storage::exists('public/surat_dudi/' . $surat->file_surat_permohonan);
            }
            if ($surat->file_balasan_permohonan) {
                $fileBalasanPermohonanExists = Storage::exists('public/surat_dudi/' . $surat->file_balasan_permohonan);
            }
        }

        return view('dudi.surat-pkl', compact('dudi', 'surat', 'filePengajuanExists', 'fileBalasanPengajuanExists', 'filePermohonanExists', 'fileBalasanPermohonanExists'));
    }

    // Upload balasan surat dari DUDI
    public function uploadBalasan(Request $request)
    {
        try {
            $user = \App\Models\User::where('id', Session::get('loginId'))->first();

            if (!$user || $user->role != 'dudi') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $request->validate([
                'file_surat_balasan' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
                'catatan_dudi' => 'nullable|string',
                'jenis_surat' => 'required|in:pengajuan,permohonan'
            ]);

            $dudi = tb_dudi::find($user->id_dudi);

            // Cari atau buat record surat
            $surat = SuratDudi::where('id_dudi', $dudi->id)->first();

            if (!$surat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada surat dari admin untuk DUDI ini.'
                ], 404);
            }

            // Determine which type of letter
            $jenisSurat = $request->jenis_surat;

            // Handle file upload
            $file = $request->file('file_surat_balasan');
            $fileName = 'surat_balasan_' . $jenisSurat . '_dudi_' . $dudi->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/surat_dudi', $fileName);

            if ($jenisSurat == 'pengajuan') {
                // Delete old file if exists
                if ($surat->file_balasan_pengajuan && Storage::exists('public/surat_dudi/' . $surat->file_balasan_pengajuan)) {
                    Storage::delete('public/surat_dudi/' . $surat->file_balasan_pengajuan);
                }

                $surat->file_balasan_pengajuan = $fileName;
                $surat->tanggal_upload_balasan_pengajuan = now();
                $surat->catatan_balasan_pengajuan = $request->catatan_dudi;
                $surat->status_balasan_pengajuan = 'terkirim';
            } else {
                // Delete old file if exists
                if ($surat->file_balasan_permohonan && Storage::exists('public/surat_dudi/' . $surat->file_balasan_permohonan)) {
                    Storage::delete('public/surat_dudi/' . $surat->file_balasan_permohonan);
                }

                $surat->file_balasan_permohonan = $fileName;
                $surat->tanggal_upload_balasan_permohonan = now();
                $surat->catatan_balasan_permohonan = $request->catatan_dudi;
                $surat->status_balasan_permohonan = 'terkirim';
            }

            $surat->save();

            // Log activity
            logActivity(
                'upload',
                'Surat Balasan ' . ucfirst($jenisSurat) . ' Diupload',
                "DUDI {$dudi->nama_dudi} mengirim balasan surat " . $jenisSurat,
                Session::get('loginId')
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Surat balasan berhasil dikirim ke admin!'
                ]);
            }

            return redirect('/dudi/surat-pkl')->with('success', 'Surat balasan berhasil dikirim ke admin!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Halaman surat untuk Admin (lihat semua balasan dari DUDI)
    public function indexAdmin()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'admin') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai admin.');
        }

        // Ambil semua surat yang sudah ada balasan
        $suratList = SuratDudi::with(['dudi', 'admin'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Check file existence for each surat
        foreach ($suratList as $surat) {
            // Pengajuan
            $surat->file_pengajuan_exists = $surat->file_surat_pengajuan
                ? Storage::exists('public/surat_dudi/' . $surat->file_surat_pengajuan)
                : false;
            $surat->file_balasan_pengajuan_exists = $surat->file_balasan_pengajuan
                ? Storage::exists('public/surat_dudi/' . $surat->file_balasan_pengajuan)
                : false;

            // Permohonan
            $surat->file_permohonan_exists = $surat->file_surat_permohonan
                ? Storage::exists('public/surat_dudi/' . $surat->file_surat_permohonan)
                : false;
            $surat->file_balasan_permohonan_exists = $surat->file_balasan_permohonan
                ? Storage::exists('public/surat_dudi/' . $surat->file_balasan_permohonan)
                : false;
        }

        // Get data admin
        $data = \App\Models\tb_admin::find($user->id_admin);

        return view('admin.surat-dudi', compact('suratList', 'data'));
    }

    // Download file surat
    public function download($id)
    {
        $surat = SuratDudi::find($id);

        if (!$surat) {
            return back()->with('error', 'Surat tidak ditemukan.');
        }

        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user) {
            return redirect('/login')->with('fail', 'Silakan login terlebih dahulu.');
        }

        // Check authorization
        // Admin bisa download semua surat, DUDI hanya bisa download surat miliknya
        if ($user->role == 'dudi') {
            // Get DUDI from user
            $dudi = tb_dudi::find($user->id_dudi);

            if (!$dudi) {
                abort(403, 'Data DUDI tidak ditemukan.');
            }

            // Allow if surat belongs to this DUDI
            if ($surat->id_dudi && $surat->id_dudi != $dudi->id) {
                abort(403, 'Anda tidak memiliki akses ke surat ini.');
            }
        } elseif ($user->role != 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // Determine which file to download based on request
        $fileType = request('type', 'surat-pengajuan'); // surat-pengajuan, balasan-pengajuan, surat-permohonan, balasan-permohonan

        // Select the correct file based on type
        $fileName = null;
        switch ($fileType) {
            case 'surat-pengajuan':
                $fileName = $surat->file_surat_pengajuan;
                break;
            case 'balasan-pengajuan':
                $fileName = $surat->file_balasan_pengajuan;
                break;
            case 'surat-permohonan':
                $fileName = $surat->file_surat_permohonan;
                break;
            case 'balasan-permohonan':
                $fileName = $surat->file_balasan_permohonan;
                break;
            default:
                return back()->with('error', 'Tipe file tidak valid.');
        }

        if (!$fileName || !Storage::exists('public/surat_dudi/' . $fileName)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::download('public/surat_dudi/' . $fileName);
    }

    // Hapus surat (Admin only)
    public function destroy($id)
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'admin') {
            return back()->with('error', 'Anda tidak memiliki akses.');
        }

        $surat = SuratDudi::find($id);

        if (!$surat) {
            return back()->with('error', 'Surat tidak ditemukan.');
        }

        // Get DUDI name before deletion
        $namaDudi = $surat->dudi ? $surat->dudi->nama_dudi : 'Unknown';

        // Delete files from storage
        if ($surat->file_surat_pengajuan && Storage::exists('public/surat_dudi/' . $surat->file_surat_pengajuan)) {
            Storage::delete('public/surat_dudi/' . $surat->file_surat_pengajuan);
        }
        if ($surat->file_surat_balasan && Storage::exists('public/surat_dudi/' . $surat->file_surat_balasan)) {
            Storage::delete('public/surat_dudi/' . $surat->file_surat_balasan);
        }

        // Delete record
        $surat->delete();

        // Log activity
        logActivity(
            'delete',
            'Surat PKL Dihapus',
            "Surat PKL untuk DUDI {$namaDudi} dihapus",
            Session::get('loginId')
        );

        return back()->with('success', 'Surat berhasil dihapus.');
    }

    // Halaman upload surat pengajuan (Admin)
    public function createPengajuan()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'admin') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai admin.');
        }

        // Get all DUDI
        $dudis = tb_dudi::orderBy('nama_dudi', 'asc')->get();

        $data = \App\Models\tb_admin::find($user->id_admin);

        return view('admin.surat-pengajuan', compact('dudis', 'data'));
    }

    // Upload surat pengajuan (Admin)
    public function storePengajuan(Request $request)
    {
        try {
            $user = \App\Models\User::where('id', Session::get('loginId'))->first();

            if (!$user || $user->role != 'admin') {
                return redirect('/login')->with('fail', 'Anda harus login sebagai admin.');
            }

            $request->validate([
                'id_dudi' => 'required|exists:tb_dudi,id',
                'siswa_ids' => 'required|array|min:1',
                'siswa_ids.*' => 'exists:tb_siswa,id',
                'nomor_surat' => 'required|string|max:100',
                'use_template' => 'nullable|boolean',
                'template_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB
                'catatan' => 'nullable|string'
            ], [
                'id_dudi.required' => 'Pilih DUDI tujuan',
                'siswa_ids.required' => 'Pilih minimal 1 siswa',
                'siswa_ids.min' => 'Pilih minimal 1 siswa',
                'nomor_surat.required' => 'Nomor surat harus diisi',
                'template_file.mimes' => 'Template harus berformat PDF, DOC, atau DOCX',
                'template_file.max' => 'Ukuran file maksimal 5MB'
            ]);

            $dudi = tb_dudi::find($request->id_dudi);

            // Get siswa data
            $siswas = \App\Models\tb_siswa::whereIn('id', $request->siswa_ids)->get();

            // Check if user uploaded custom template
            if ($request->hasFile('template_file') && $request->use_template) {
                // Use uploaded template file
                $file = $request->file('template_file');
                $fileName = 'surat_pengajuan_' . $dudi->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/surat_dudi', $fileName);
            } else {
                // Generate PDF from system template
                $pdfData = [
                    'dudi' => $dudi,
                    'siswas' => $siswas,
                    'tanggal' => now(),
                    'catatan' => $request->catatan,
                    'periode_pkl' => 'Januari - Maret ' . (date('Y') + 1),
                    'nomor_surat' => $request->nomor_surat
                ];

                $pdf = Pdf::loadView('pdf.surat-pengajuan', $pdfData);
                $fileName = 'surat_pengajuan_' . $dudi->id . '_' . time() . '.pdf';

                // Save PDF to storage
                Storage::put('public/surat_dudi/' . $fileName, $pdf->output());
            }

            // Check if surat already exists for this DUDI
            $surat = SuratDudi::where('id_dudi', $dudi->id)->first();
            if ($surat) {
                // Update existing surat
                // Delete old file if exists
                if ($surat->file_surat_pengajuan && Storage::exists('public/surat_dudi/' . $surat->file_surat_pengajuan)) {
                    Storage::delete('public/surat_dudi/' . $surat->file_surat_pengajuan);
                }

                $surat->file_surat_pengajuan = $fileName;
                $surat->nomor_surat_pengajuan = $request->nomor_surat;
                $surat->tanggal_upload_pengajuan = now();
                $surat->catatan_admin_pengajuan = $request->catatan;
                $surat->uploaded_by_admin = $user->id_admin;
                $surat->save();
            } else {
                // Create new surat
                $surat = new SuratDudi();
                $surat->id_dudi = $dudi->id;
                $surat->uploaded_by_admin = $user->id_admin;
                $surat->file_surat_pengajuan = $fileName;
                $surat->nomor_surat_pengajuan = $request->nomor_surat;
                $surat->tanggal_upload_pengajuan = now();
                $surat->catatan_admin_pengajuan = $request->catatan;
                $surat->save();
            }

            // Log activity
            logActivity(
                'upload',
                'Surat Pengajuan PKL Dikirim',
                "Surat pengajuan PKL untuk " . count($request->siswa_ids) . " siswa dikirim ke DUDI {$dudi->nama_dudi}",
                Session::get('loginId')
            );

            return redirect('/admin/surat-dudi')->with(
                'success',
                'Surat pengajuan PKL berhasil dikirim ke ' . $dudi->nama_dudi . ' untuk ' . count($request->siswa_ids) . ' siswa!'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Halaman upload surat permohonan (Admin)
    public function createPermohonan()
    {
        $user = \App\Models\User::where('id', Session::get('loginId'))->first();

        if (!$user || $user->role != 'admin') {
            return redirect('/login')->with('fail', 'Anda harus login sebagai admin.');
        }

        // Get all DUDI
        $dudis = tb_dudi::orderBy('nama_dudi', 'asc')->get();

        $data = \App\Models\tb_admin::find($user->id_admin);

        return view('admin.surat-permohonan', compact('dudis', 'data'));
    }

    // Upload surat permohonan (Admin)
    public function storePermohonan(Request $request)
    {
        try {
            $user = \App\Models\User::where('id', Session::get('loginId'))->first();

            if (!$user || $user->role != 'admin') {
                return redirect('/login')->with('fail', 'Anda harus login sebagai admin.');
            }

            $request->validate([
                'id_dudi' => 'required|exists:tb_dudi,id',
                'nomor_surat' => 'required|string|max:100',
                'use_template' => 'nullable|boolean',
                'template_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'catatan' => 'nullable|string'
            ], [
                'id_dudi.required' => 'Pilih DUDI tujuan',
                'nomor_surat.required' => 'Nomor surat harus diisi',
                'template_file.mimes' => 'Template harus berformat PDF, DOC, atau DOCX',
                'template_file.max' => 'Ukuran file maksimal 5MB'
            ]);

            $dudi = tb_dudi::find($request->id_dudi);

            // Check if user uploaded custom template
            if ($request->hasFile('template_file') && $request->use_template) {
                // Use uploaded template file
                $file = $request->file('template_file');
                $fileName = 'surat_permohonan_' . $dudi->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/surat_dudi', $fileName);
            } else {
                // Generate PDF from system template
                $pdfData = [
                    'dudi' => $dudi,
                    'tanggal' => now(),
                    'catatan' => $request->catatan,
                    'nomor_surat' => $request->nomor_surat
                ];

                $pdf = Pdf::loadView('pdf.surat-permohonan', $pdfData);
                $fileName = 'surat_permohonan_' . $dudi->id . '_' . time() . '.pdf';

                // Save PDF to storage
                Storage::put('public/surat_dudi/' . $fileName, $pdf->output());
            }

            // Check if surat already exists for this DUDI
            $surat = SuratDudi::where('id_dudi', $dudi->id)->first();
            if ($surat) {
                // Update existing surat permohonan
                // Delete old file if exists
                if ($surat->file_surat_permohonan && Storage::exists('public/surat_dudi/' . $surat->file_surat_permohonan)) {
                    Storage::delete('public/surat_dudi/' . $surat->file_surat_permohonan);
                }

                $surat->file_surat_permohonan = $fileName;
                $surat->nomor_surat_permohonan = $request->nomor_surat;
                $surat->tanggal_upload_permohonan = now();
                $surat->catatan_admin_permohonan = $request->catatan;
                $surat->uploaded_by_admin = $user->id_admin;
                $surat->save();
            } else {
                // Create new surat
                $surat = new SuratDudi();
                $surat->id_dudi = $dudi->id;
                $surat->uploaded_by_admin = $user->id_admin;
                $surat->file_surat_permohonan = $fileName;
                $surat->nomor_surat_permohonan = $request->nomor_surat;
                $surat->tanggal_upload_permohonan = now();
                $surat->catatan_admin_permohonan = $request->catatan;
                $surat->save();
            }

            // Log activity
            logActivity(
                'upload',
                'Surat Permohonan Data Dikirim',
                "Surat permohonan data jurusan/jobdesk dikirim ke DUDI {$dudi->nama_dudi}",
                Session::get('loginId')
            );

            return redirect('/admin/surat-dudi')->with(
                'success',
                'Surat permohonan data berhasil dikirim ke ' . $dudi->nama_dudi . '!'
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Get siswa yang mendaftar ke DUDI tertentu (AJAX)
    public function getSiswaDudi($id_dudi)
    {
        try {
            $dudi = tb_dudi::find($id_dudi);

            if (!$dudi) {
                return response()->json([
                    'success' => false,
                    'message' => 'DUDI tidak ditemukan'
                ], 404);
            }

            // Cari siswa yang mendaftar ke DUDI ini dari pengajuan_pkl
            // Untuk DUDI sekolah: cari di id_dudi_pilihan_1/2/3
            // Untuk DUDI mandiri: cari di id_dudi_mandiri_pilihan_1/2/3

            $siswas = collect();

            if ($dudi->jenis_dudi == 'mandiri') {
                // Untuk DUDI mandiri, ambil ID dari tabel dudi_mandiri yang sudah approved
                $dudiMandiriIds = \App\Models\DudiMandiri::where('id_dudi', $id_dudi)
                    ->pluck('id')
                    ->toArray();

                if (!empty($dudiMandiriIds)) {
                    $siswas = \App\Models\PengajuanPkl::where(function ($query) use ($dudiMandiriIds) {
                        foreach ($dudiMandiriIds as $dmId) {
                            $query->orWhere('id_dudi_mandiri_pilihan_1', $dmId)
                                ->orWhere('id_dudi_mandiri_pilihan_2', $dmId)
                                ->orWhere('id_dudi_mandiri_pilihan_3', $dmId);
                        }
                    })
                        ->with('siswa')
                        ->get()
                        ->pluck('siswa')
                        ->filter()
                        ->unique('id')
                        ->values();
                }
            } else {
                // Untuk DUDI sekolah
                $siswas = \App\Models\PengajuanPkl::where(function ($query) use ($id_dudi) {
                    $query->where('id_dudi_pilihan_1', $id_dudi)
                        ->orWhere('id_dudi_pilihan_2', $id_dudi)
                        ->orWhere('id_dudi_pilihan_3', $id_dudi);
                })
                    ->with('siswa')
                    ->get()
                    ->pluck('siswa')
                    ->filter()
                    ->unique('id')
                    ->values();
            }

            return response()->json([
                'success' => true,
                'siswas' => $siswas->map(function ($siswa) {
                    return [
                        'id' => $siswa->id,
                        'nama' => $siswa->nama,
                        'nis' => $siswa->nis,
                        'jurusan' => $siswa->jurusan,
                        'kelas' => $siswa->kelas
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
