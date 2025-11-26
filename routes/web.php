<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DudiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AuthenController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\DudiMandiriController;
use App\Http\Controllers\DudiMandiriAdminController;
use App\Http\Controllers\PengajuanPklController;
use App\Http\Controllers\PengajuanPklAdminController;
use App\Models\DokumenSiswa;
use App\Http\Controllers\PengajuanPklDudiController;
use App\Http\Controllers\InfoPklSiswaController;
use App\Http\Controllers\DokumenSiswaController;

Route::redirect('/', '/login');

// TEST ROUTE - HAPUS SETELAH TESTING
Route::get('/test-dokumen', function () {
    $dok = DokumenSiswa::find(1);
    return response()->json([
        'id' => $dok->id,
        'id_siswa' => $dok->id_siswa,
        'file_surat_pernyataan_siswa' => $dok->file_surat_pernyataan_siswa,
        'file_foto_dengan_ortu' => $dok->file_foto_dengan_ortu,
        'jawaban_surat_pernyataan' => $dok->jawaban_surat_pernyataan,
        'status_eviden' => $dok->status_eviden
    ]);
});

Route::get('/test-dokumen-view', [DokumenSiswaController::class, 'index']);

// Authentication Routes
Route::get('/registrationSiswa', [AuthenController::class, 'registrationSiswa'])->middleware('alreadyLoggedIn');
Route::post('/registration-userSiswa', [AuthenController::class, 'registerUserSiswa'])->name('register-userSiswa');
Route::get('/registrationDudi', [AuthenController::class, 'registrationDudi'])->middleware('alreadyLoggedIn');
Route::post('/registration-userDudi', [AuthenController::class, 'registerUserDudi'])->name('register-userDudi');
Route::get('/registrationAdmin', [AuthenController::class, 'registrationAdmin'])->middleware('alreadyLoggedIn');
Route::post('/registration-userAdmin', [AuthenController::class, 'registerUserAdmin'])->name('register-userAdmin');
Route::get('/login', [AuthenController::class, 'login'])->middleware('alreadyLoggedIn');
Route::post('/login-user', [AuthenController::class, 'loginUser'])->name('login-user');
Route::get('/dashboard', [AuthenController::class, 'dashboard']);
Route::get('/logout', [AuthenController::class, 'logout']);

Route::middleware('isLoggedIn')->group(function () {

    //Route untuk crud dudi di admin
    Route::get('/admin/dudi', [DudiController::class, 'index']);
    Route::post('/admin/dudi', [DudiController::class, 'store']);
    Route::put('/admin/dudi/{id}', [DudiController::class, 'update']);
    Route::delete('/admin/dudi/{id}', [DudiController::class, 'destroy']);
    Route::post('/admin/dudi/{id}/reset-password', [DudiController::class, 'resetPassword']);
    Route::post('/admin/dudi/upload-surat', [DudiController::class, 'uploadSurat']);

    //Route untuk crud siswa di admin
    Route::get('/admin/siswa', [SiswaController::class, 'index']);
    Route::post('/admin/siswa', [SiswaController::class, 'store']);
    Route::put('/admin/siswa/{id}', [SiswaController::class, 'update']);
    Route::delete('/admin/siswa/{id}', [SiswaController::class, 'destroy']);
    Route::post('/admin/siswa/import', [SiswaController::class, 'import']);
    Route::post('/admin/siswa/{id}/grade', [SiswaController::class, 'updateGrade']);

    // Route untuk assign/cancel penempatan PKL
    Route::post('/admin/siswa/{id}/assign', [SiswaController::class, 'assignDudi']);
    Route::post('/admin/siswa/{id}/cancel-assignment', [SiswaController::class, 'cancelAssignment']);
    Route::post('/admin/siswa/{id}/set-tanggal-pkl', [SiswaController::class, 'setTanggalPkl']);

    //Route untuk crud notes di admin dashboard
    Route::get('/admin/notes', [NoteController::class, 'index']);
    Route::post('/admin/notes', [NoteController::class, 'store']);
    Route::put('/admin/notes/{id}', [NoteController::class, 'update']);
    Route::delete('/admin/notes/{id}', [NoteController::class, 'destroy']);

    //Route untuk DUDI Mandiri (Siswa)
    Route::post('/siswa/dudi-mandiri', [DudiMandiriController::class, 'store']);
    Route::get('/siswa/dudi-mandiri/current', [DudiMandiriController::class, 'getByCurrentSiswa']);
    Route::delete('/siswa/dudi-mandiri/{id}', [DudiMandiriController::class, 'destroy']);

    //Route untuk DUDI Mandiri (Admin)
    Route::get('/admin/dudi-mandiri', [DudiMandiriAdminController::class, 'index']);
    Route::post('/admin/dudi-mandiri/{id}/approve', [DudiMandiriAdminController::class, 'approve']);
    Route::post('/admin/dudi-mandiri/{id}/reject', [DudiMandiriAdminController::class, 'reject']);
    Route::delete('/admin/dudi-mandiri/{id}', [DudiMandiriAdminController::class, 'destroy']);

    //Route untuk Pengajuan PKL (Siswa)
    Route::get('/siswa/pengajuan-pkl', [PengajuanPklController::class, 'index']);
    Route::post('/siswa/pengajuan-pkl', [PengajuanPklController::class, 'store']);

    //Route untuk Info PKL (Siswa)
    Route::get('/siswa/info-pkl', [InfoPklSiswaController::class, 'index']);

    //Route untuk Status Siswa
    Route::get('/siswa/status', [\App\Http\Controllers\StatusSiswaController::class, 'index']);

    //Route untuk Status Pengajuan PKL (Siswa)
    Route::get('/siswa/status-pengajuan', [\App\Http\Controllers\StatusPengajuanController::class, 'index']);

    //Route untuk Upload Dokumen (Siswa)
    Route::get('/siswa/upload-dokumen', [\App\Http\Controllers\UploadDokumenController::class, 'index']);
    Route::post('/siswa/upload-dokumen', [\App\Http\Controllers\UploadDokumenController::class, 'upload']);
    Route::get('/siswa/upload-dokumen/download/{type}', [\App\Http\Controllers\UploadDokumenController::class, 'download']);

    //Route untuk Dokumen PKL (Siswa)
    Route::get('/siswa/dokumen-pkl', [DokumenSiswaController::class, 'index']);
    Route::post('/siswa/dokumen-pkl/upload', [DokumenSiswaController::class, 'uploadCvPortofolio']);
    Route::get('/siswa/dokumen-pkl/download/{type}', [DokumenSiswaController::class, 'downloadSiswa']);
    Route::post('/siswa/dokumen-pkl/upload-eviden', [DokumenSiswaController::class, 'uploadEviden']);

    //Route untuk Dokumen PKL (Admin)
    Route::get('/admin/dokumen-siswa', [DokumenSiswaController::class, 'adminIndex']);
    Route::get('/admin/dokumen-siswa/{id}/download/{type}', [DokumenSiswaController::class, 'download']);
    Route::post('/admin/dokumen-siswa/{id}/kirim-surat-pernyataan', [DokumenSiswaController::class, 'kirimSuratPernyataan']);
    Route::post('/admin/dokumen-siswa/{id}/kirim-surat-tugas', [DokumenSiswaController::class, 'kirimSuratTugas']);

    //Route untuk Pengajuan PKL (Admin)
    Route::get('/admin/pengajuan-pkl', [PengajuanPklAdminController::class, 'index']);
    Route::get('/admin/pengajuan-pkl/export-approved', [PengajuanPklAdminController::class, 'exportApproved']);
    Route::get('/admin/pengajuan-pkl/{id}/detail', [PengajuanPklAdminController::class, 'detail']);
    Route::post('/admin/pengajuan-pkl/{id}/approve', [PengajuanPklAdminController::class, 'approve']);
    Route::post('/admin/pengajuan-pkl/{id}/reject', [PengajuanPklAdminController::class, 'reject']);
    Route::post('/admin/pengajuan-pkl/{id}/change-pilihan', [PengajuanPklAdminController::class, 'changePilihan']);
    Route::delete('/admin/pengajuan-pkl/{id}', [PengajuanPklAdminController::class, 'destroy']);

    //Route untuk Pengajuan PKL (DUDI)
    Route::get('/dudi/lamaran-pkl', [PengajuanPklDudiController::class, 'index']);
    Route::post('/dudi/lamaran-pkl/{id}/approve', [PengajuanPklDudiController::class, 'approve']);
    Route::post('/dudi/lamaran-pkl/{id}/reject', [PengajuanPklDudiController::class, 'reject']);

    //Route untuk Surat PKL (DUDI)
    Route::get('/dudi/dashboard', [\App\Http\Controllers\SuratDudiController::class, 'dashboard']);
    Route::get('/dudi/surat-pengajuan', [\App\Http\Controllers\SuratDudiController::class, 'indexPengajuan']);
    Route::get('/dudi/surat-permohonan', [\App\Http\Controllers\SuratDudiController::class, 'indexPermohonan']);
    Route::get('/dudi/surat-pkl', [\App\Http\Controllers\SuratDudiController::class, 'indexDudi']);
    Route::post('/dudi/surat-pkl/upload-balasan', [\App\Http\Controllers\SuratDudiController::class, 'uploadBalasan']);
    // DUDI download route (use same controller download method which handles authorization)
    Route::get('/dudi/surat-pkl/{id}/download', [\App\Http\Controllers\SuratDudiController::class, 'download']);

    //Route untuk Surat PKL (Admin)
    Route::get('/admin/surat-dudi', [\App\Http\Controllers\SuratDudiController::class, 'indexAdmin']);
    Route::get('/admin/surat-pengajuan', [\App\Http\Controllers\SuratDudiController::class, 'createPengajuan']);
    Route::post('/admin/surat-pengajuan/kirim', [\App\Http\Controllers\SuratDudiController::class, 'storePengajuan']);
    Route::get('/admin/surat-permohonan', [\App\Http\Controllers\SuratDudiController::class, 'createPermohonan']);
    Route::post('/admin/surat-permohonan/kirim', [\App\Http\Controllers\SuratDudiController::class, 'storePermohonan']);
    Route::get('/admin/surat-dudi/siswa/{id_dudi}', [\App\Http\Controllers\SuratDudiController::class, 'getSiswaDudi']);
    Route::get('/admin/surat-dudi/{id}/download', [\App\Http\Controllers\SuratDudiController::class, 'download']);
    Route::delete('/admin/surat-dudi/{id}', [\App\Http\Controllers\SuratDudiController::class, 'destroy']);
});
