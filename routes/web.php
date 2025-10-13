<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DudiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AuthenController;

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
    Route::get('/admin/siswa', [SiswaController::class,'index'])->middleware('isLoggedIn');
    Route::post('/admin/siswa', [SiswaController::class,'store'])->middleware('isLoggedIn');
    Route::put('/admin/siswa/{id}', [SiswaController::class,'update'])->middleware('isLoggedIn');
    Route::delete('/admin/siswa/{id}', [SiswaController::class,'destroy'])->middleware('isLoggedIn');
    Route::post('/admin/siswa/import', [SiswaController::class, 'import'])->middleware('isLoggedIn');



    Route::get('/logout', [AuthenController::class, 'logout']);
});
