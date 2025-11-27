<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DokumenSiswa;
use App\Models\tb_siswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class DokumenSiswaController extends Controller
{
    // Halaman upload dokumen untuk siswa
    public function index()
    {
        // Ambil id_siswa dari session (bukan loginId yang merupakan user id)
        $idSiswa = session('id_siswa');

        if (!$idSiswa) {
            return redirect('/login')->with('fail', 'Silakan login terlebih dahulu');
        }

        $siswa = tb_siswa::find($idSiswa);

        if (!$siswa) {
            return redirect('/login')->with('fail', 'Data siswa tidak ditemukan');
        }

        // Ambil atau buat record dokumen siswa
        $dokumen = DokumenSiswa::firstOrCreate(
            ['id_siswa' => $idSiswa],
            [
                'status_cv_portofolio' => 'belum',
                'status_surat_pernyataan' => 'belum',
                'status_eviden' => 'belum',
                'status_surat_tugas' => 'belum'
            ]
        );

        return view('siswa.dokumen-pkl', compact('siswa', 'dokumen'));
    }

    // Upload CV dan Portofolio
    public function uploadCvPortofolio(Request $request)
    {
        $request->validate([
            'file_cv' => 'required|mimes:pdf,doc,docx|max:5120', // 5MB
            'file_portofolio' => 'required|mimes:pdf,doc,docx|max:5120'
        ], [
            'file_cv.required' => 'File CV wajib diupload',
            'file_cv.mimes' => 'File CV harus berformat PDF, DOC, atau DOCX',
            'file_cv.max' => 'Ukuran file CV maksimal 5MB',
            'file_portofolio.required' => 'File Portofolio wajib diupload',
            'file_portofolio.mimes' => 'File Portofolio harus berformat PDF, DOC, atau DOCX',
            'file_portofolio.max' => 'Ukuran file Portofolio maksimal 5MB'
        ]);

        $idSiswa = session('id_siswa');
        $dokumen = DokumenSiswa::where('id_siswa', $idSiswa)->first();

        // Hapus file lama jika ada
        if ($dokumen && $dokumen->file_cv) {
            Storage::disk('public')->delete($dokumen->file_cv);
        }
        if ($dokumen && $dokumen->file_portofolio) {
            Storage::disk('public')->delete($dokumen->file_portofolio);
        }

        // Upload file baru
        $cvPath = $request->file('file_cv')->store('dokumen-siswa/cv', 'public');
        $portofolioPath = $request->file('file_portofolio')->store('dokumen-siswa/portofolio', 'public');

        // Update atau create record
        DokumenSiswa::updateOrCreate(
            ['id_siswa' => $idSiswa],
            [
                'file_cv' => $cvPath,
                'file_portofolio' => $portofolioPath,
                'tanggal_upload_cv_portofolio' => now(),
                'status_cv_portofolio' => 'sudah'
            ]
        );

        // Log activity
        logActivity('success', 'Upload CV & Portofolio', 'Siswa ' . session('nama') . ' mengupload CV dan Portofolio');

        return response()->json([
            'success' => true,
            'message' => 'CV dan Portofolio berhasil diupload!'
        ]);
    }

    // Halaman admin untuk melihat dokumen siswa
    public function adminIndex()
    {
        $dokumenList = DokumenSiswa::with('siswa')->get();
        return view('admin.dokumen-siswa', compact('dokumenList'));
    }

    // Download file dokumen
    public function download($id, $type)
    {
        $dokumen = DokumenSiswa::findOrFail($id);

        $filePath = null;
        $fileName = '';

        switch ($type) {
            case 'cv':
                $filePath = $dokumen->file_cv;
                $fileName = 'CV_' . $dokumen->siswa->nama . '_' . $dokumen->siswa->nis;
                break;
            case 'portofolio':
                $filePath = $dokumen->file_portofolio;
                $fileName = 'Portofolio_' . $dokumen->siswa->nama . '_' . $dokumen->siswa->nis;
                break;
            case 'surat_pernyataan':
                $filePath = $dokumen->file_surat_pernyataan;
                $fileName = 'Surat_Pernyataan_' . $dokumen->siswa->nama . '_' . $dokumen->siswa->nis;
                break;
            case 'surat_pernyataan_siswa':
                $filePath = $dokumen->file_surat_pernyataan_siswa;
                $fileName = 'Surat_Pernyataan_Siswa_' . $dokumen->siswa->nama . '_' . $dokumen->siswa->nis;
                break;
            case 'foto_ortu':
                $filePath = $dokumen->file_foto_dengan_ortu;
                $fileName = 'Foto_Dengan_Ortu_' . $dokumen->siswa->nama . '_' . $dokumen->siswa->nis;
                break;
            case 'surat_tugas':
                $filePath = $dokumen->file_surat_tugas;
                $fileName = 'Surat_Tugas_' . $dokumen->siswa->nama . '_' . $dokumen->siswa->nis;
                break;
            default:
                abort(404, 'Tipe file tidak valid');
        }

        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan di storage: ' . ($filePath ?? 'null'));
        }

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $fullPath = Storage::disk('public')->path($filePath);

        return response()->download($fullPath, $fileName . '.' . $extension);
    }

    // Download file dokumen untuk siswa (tanpa perlu ID, ambil dari session)
    public function downloadSiswa($type)
    {
        $idSiswa = session('id_siswa');
        $dokumen = DokumenSiswa::where('id_siswa', $idSiswa)->first();

        if (!$dokumen) {
            abort(404, 'Dokumen tidak ditemukan');
        }

        return $this->download($dokumen->id, $type);
    }

    // Admin kirim surat pernyataan ke siswa
    public function kirimSuratPernyataan($id)
    {
        $dokumen = DokumenSiswa::with('siswa')->findOrFail($id);

        // Validasi: pastikan siswa sudah upload CV & Portofolio
        if ($dokumen->status_cv_portofolio !== 'sudah') {
            return response()->json([
                'success' => false,
                'message' => 'Siswa belum mengupload CV dan Portofolio'
            ], 400);
        }

        // Generate nomor surat
        $tahun = date('Y');
        $bulan = date('m');
        $nomorUrut = DokumenSiswa::whereYear('tanggal_kirim_surat_pernyataan', $tahun)
            ->whereMonth('tanggal_kirim_surat_pernyataan', $bulan)
            ->where('status_surat_pernyataan', 'terkirim')
            ->count() + 1;

        $nomorSurat = sprintf('SP/%03d/PKL-SMKTELKOM/%s/%s', $nomorUrut, $bulan, $tahun);

        // Data untuk PDF
        $data = [
            'siswa' => $dokumen->siswa,
            'nomorSurat' => $nomorSurat,
            'tanggalSurat' => now()
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdf.surat-pernyataan', $data);
        $pdf->setPaper('a4', 'portrait');

        // Simpan PDF
        $fileName = 'surat-pernyataan/Surat_Pernyataan_' . $dokumen->siswa->nis . '_' . time() . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        // Update database
        $dokumen->update([
            'file_surat_pernyataan' => $fileName,
            'tanggal_kirim_surat_pernyataan' => now(),
            'status_surat_pernyataan' => 'terkirim'
        ]);

        // Log activity
        logActivity('success', 'Kirim Surat Pernyataan', 'Admin mengirim surat pernyataan ke siswa ' . $dokumen->siswa->nama);

        return response()->json([
            'success' => true,
            'message' => 'Surat pernyataan berhasil dikirim ke ' . $dokumen->siswa->nama
        ]);
    }

    // Siswa upload eviden (surat pernyataan + jawaban + foto dengan orang tua)
    public function uploadEviden(Request $request)
    {
        try {
            $request->validate([
                'file_surat_pernyataan_siswa' => 'required|mimes:pdf,jpeg,jpg,png|max:5120', // 5MB
                'jawaban_surat_pernyataan' => 'nullable|string',
                'file_foto_ortu' => 'required|image|mimes:jpeg,jpg,png|max:5120' // 5MB
            ], [
                'file_surat_pernyataan_siswa.required' => 'Surat pernyataan yang sudah diisi wajib diupload',
                'file_surat_pernyataan_siswa.mimes' => 'Format surat pernyataan harus PDF, JPG, JPEG, atau PNG',
                'file_surat_pernyataan_siswa.max' => 'Ukuran file surat pernyataan maksimal 5MB',
                'file_foto_ortu.required' => 'Foto dengan orang tua wajib diupload',
                'file_foto_ortu.image' => 'File harus berupa gambar',
                'file_foto_ortu.mimes' => 'Format foto harus JPG, JPEG, atau PNG',
                'file_foto_ortu.max' => 'Ukuran foto maksimal 5MB'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $e->validator->errors()->all())
            ], 422);
        }

        $idSiswa = session('id_siswa');
        $dokumen = DokumenSiswa::where('id_siswa', $idSiswa)->first();

        if (!$dokumen) {
            return response()->json([
                'success' => false,
                'message' => 'Data dokumen tidak ditemukan'
            ], 404);
        }

        // Validasi: pastikan surat pernyataan sudah terkirim
        if ($dokumen->status_surat_pernyataan !== 'terkirim') {
            return response()->json([
                'success' => false,
                'message' => 'Surat pernyataan belum terkirim dari admin'
            ], 400);
        }

        // Hapus file lama jika ada
        if ($dokumen->file_surat_pernyataan_siswa) {
            Storage::disk('public')->delete($dokumen->file_surat_pernyataan_siswa);
        }
        if ($dokumen->file_foto_dengan_ortu) {
            Storage::disk('public')->delete($dokumen->file_foto_dengan_ortu);
        }

        try {
            // Upload surat pernyataan siswa
            $suratPath = $request->file('file_surat_pernyataan_siswa')->store('dokumen-siswa/surat-pernyataan-siswa', 'public');

            // Upload foto dengan orang tua
            $fotoPath = $request->file('file_foto_ortu')->store('dokumen-siswa/foto-ortu', 'public');

            // Update database
            $dokumen->update([
                'file_surat_pernyataan_siswa' => $suratPath,
                'jawaban_surat_pernyataan' => $request->jawaban_surat_pernyataan,
                'file_foto_dengan_ortu' => $fotoPath,
                'tanggal_upload_eviden' => now(),
                'status_eviden' => 'sudah'
            ]);

            // Log activity
            logActivity('success', 'Upload Eviden', 'Siswa ' . session('nama') . ' mengupload eviden');

            return response()->json([
                'success' => true,
                'message' => 'Eviden berhasil diupload!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    // Admin kirim surat tugas ke siswa
    public function kirimSuratTugas(Request $request, $id)
    {
        // Validasi input nomor surat
        $request->validate([
            'nomor_surat' => 'required|string|max:100'
        ], [
            'nomor_surat.required' => 'Nomor surat harus diisi',
            'nomor_surat.max' => 'Nomor surat maksimal 100 karakter'
        ]);

        $dokumen = DokumenSiswa::with('siswa')->findOrFail($id);

        // Validasi: pastikan siswa sudah upload eviden
        if ($dokumen->status_eviden !== 'sudah') {
            return response()->json([
                'success' => false,
                'message' => 'Siswa belum mengupload eviden'
            ], 400);
        }

        // Gunakan nomor surat dari input admin
        $nomorSurat = $request->nomor_surat;

        // Generate PDF Surat Tugas
        $pdf = Pdf::loadView('pdf.surat-tugas', [
            'siswa' => $dokumen->siswa,
            'nomorSurat' => $nomorSurat,
            'tanggalSurat' => now()->locale('id')->isoFormat('D MMMM Y')
        ]);

        // Simpan PDF ke storage
        $fileName = 'surat-tugas-' . $dokumen->siswa->nis . '-' . time() . '.pdf';
        $filePath = 'dokumen-siswa/surat-tugas/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        // Update database
        $dokumen->update([
            'file_surat_tugas' => $filePath,
            'nomor_surat_tugas' => $nomorSurat,
            'tanggal_kirim_surat_tugas' => now(),
            'status_surat_tugas' => 'terkirim'
        ]);

        // Log activity
        logActivity('success', 'Kirim Surat Tugas', 'Admin mengirim surat tugas ke siswa ' . $dokumen->siswa->nama . ' dengan nomor: ' . $nomorSurat);

        return response()->json([
            'success' => true,
            'message' => 'Surat tugas berhasil dikirim ke ' . $dokumen->siswa->nama
        ]);
    }
}
