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
use App\Http\Controllers\PengajuanPklDudiController;
use App\Http\Controllers\InfoPklSiswaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-tailwind', function () {
    return view('test-tailwind');
});
// Route::get('/register', function () {
//     return view('auth.register');
// });

Route::controller(AuthenController::class)->group(function () {
    Route::get('/registrationSiswa', [AuthenController::class, 'registrationSiswa'])->middleware('alreadyLoggedIn');
    Route::post('/registration-userSiswa', [AuthenController::class, 'registerUserSiswa'])->name('register-userSiswa');
    Route::get('/registrationDudi', [AuthenController::class, 'registrationDudi'])->middleware('alreadyLoggedIn');
    Route::post('/registration-userDudi', [AuthenController::class, 'registerUserDudi'])->name('register-userDudi');
    Route::get('/registrationAdmin', [AuthenController::class, 'registrationAdmin'])->middleware('alreadyLoggedIn');
    Route::post('/registration-userAdmin', [AuthenController::class, 'registerUserAdmin'])->name('register-userAdmin');
    Route::get('/login', [AuthenController::class, 'login'])->middleware('alreadyLoggedIn');
    Route::post('/login-user', [AuthenController::class, 'loginUser'])->name('login-user');
    Route::get('/dashboard', [AuthenController::class, 'dashboard'])->middleware('isLoggedIn');

    //Route untuk crud dudi di admin
    Route::get('/admin/dudi', [DudiController::class, 'index'])->middleware('isLoggedIn');
    Route::post('/admin/dudi', [DudiController::class, 'store'])->middleware('isLoggedIn');
    Route::put('/admin/dudi/{id}', [DudiController::class, 'update'])->middleware('isLoggedIn');
    Route::delete('/admin/dudi/{id}', [DudiController::class, 'destroy'])->middleware('isLoggedIn');
    Route::post('/admin/dudi/{id}/reset-password', [DudiController::class, 'resetPassword'])->middleware('isLoggedIn');


    //Route untuk crud siswa di admin
    Route::get('/admin/siswa', [SiswaController::class, 'index'])->middleware('isLoggedIn');
    Route::post('/admin/siswa', [SiswaController::class, 'store'])->middleware('isLoggedIn');
    Route::put('/admin/siswa/{id}', [SiswaController::class, 'update'])->middleware('isLoggedIn');
    Route::delete('/admin/siswa/{id}', [SiswaController::class, 'destroy'])->middleware('isLoggedIn');
    Route::post('/admin/siswa/import', [SiswaController::class, 'import'])->middleware('isLoggedIn');

    // Route untuk assign/cancel penempatan PKL
    Route::post('/admin/siswa/{id}/assign', [SiswaController::class, 'assignDudi'])->middleware('isLoggedIn');
    Route::post('/admin/siswa/{id}/cancel-assignment', [SiswaController::class, 'cancelAssignment'])->middleware('isLoggedIn');
    Route::post('/admin/siswa/{id}/set-tanggal-pkl', [SiswaController::class, 'setTanggalPkl'])->middleware('isLoggedIn');

    //Route untuk crud notes di admin dashboard
    Route::get('/admin/notes', [NoteController::class, 'index'])->middleware('isLoggedIn');
    Route::post('/admin/notes', [NoteController::class, 'store'])->middleware('isLoggedIn');
    Route::put('/admin/notes/{id}', [NoteController::class, 'update'])->middleware('isLoggedIn');
    Route::delete('/admin/notes/{id}', [NoteController::class, 'destroy'])->middleware('isLoggedIn');

    //Route untuk DUDI Mandiri (Siswa)
    Route::post('/siswa/dudi-mandiri', [DudiMandiriController::class, 'store'])->middleware('isLoggedIn');
    Route::get('/siswa/dudi-mandiri/current', [DudiMandiriController::class, 'getByCurrentSiswa'])->middleware('isLoggedIn');
    Route::delete('/siswa/dudi-mandiri/{id}', [DudiMandiriController::class, 'destroy'])->middleware('isLoggedIn');

    //Route untuk DUDI Mandiri (Admin)
    Route::get('/admin/dudi-mandiri', [DudiMandiriAdminController::class, 'index'])->middleware('isLoggedIn');
    Route::post('/admin/dudi-mandiri/{id}/approve', [DudiMandiriAdminController::class, 'approve'])->middleware('isLoggedIn');
    Route::post('/admin/dudi-mandiri/{id}/reject', [DudiMandiriAdminController::class, 'reject'])->middleware('isLoggedIn');
    Route::delete('/admin/dudi-mandiri/{id}', [DudiMandiriAdminController::class, 'destroy'])->middleware('isLoggedIn');

    //Route untuk Pengajuan PKL (Siswa)
    Route::get('/siswa/pengajuan-pkl', [PengajuanPklController::class, 'index'])->middleware('isLoggedIn');
    Route::post('/siswa/pengajuan-pkl', [PengajuanPklController::class, 'store'])->middleware('isLoggedIn');

    //Route untuk Info PKL (Siswa)
    Route::get('/siswa/info-pkl', [InfoPklSiswaController::class, 'index'])->middleware('isLoggedIn');

    //Route untuk Pengajuan PKL (Admin)
    Route::get('/admin/pengajuan-pkl', [PengajuanPklAdminController::class, 'index'])->middleware('isLoggedIn');
    Route::get('/admin/pengajuan-pkl/{id}/detail', [PengajuanPklAdminController::class, 'detail'])->middleware('isLoggedIn');
    Route::post('/admin/pengajuan-pkl/{id}/approve', [PengajuanPklAdminController::class, 'approve'])->middleware('isLoggedIn');
    Route::post('/admin/pengajuan-pkl/{id}/reject', [PengajuanPklAdminController::class, 'reject'])->middleware('isLoggedIn');
    Route::post('/admin/pengajuan-pkl/{id}/change-pilihan', [PengajuanPklAdminController::class, 'changePilihan'])->middleware('isLoggedIn');
    Route::delete('/admin/pengajuan-pkl/{id}', [PengajuanPklAdminController::class, 'destroy'])->middleware('isLoggedIn');

    //Route untuk Pengajuan PKL (DUDI)
    Route::get('/dudi/lamaran-pkl', [PengajuanPklDudiController::class, 'index'])->middleware('isLoggedIn');
    Route::post('/dudi/lamaran-pkl/{id}/approve', [PengajuanPklDudiController::class, 'approve'])->middleware('isLoggedIn');
    Route::post('/dudi/lamaran-pkl/{id}/reject', [PengajuanPklDudiController::class, 'reject'])->middleware('isLoggedIn');

    Route::get('/logout', [AuthenController::class, 'logout']);
});
