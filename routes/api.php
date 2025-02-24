<?php

use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Soal1Controller;
use App\Http\Controllers\Soal2Controller;
use App\Http\Controllers\Soal3Controller;
use App\Http\Controllers\Soal4Controller;
use App\Http\Controllers\Soal5Controller;
use App\Http\Controllers\Soal6Controller;
use App\Http\Controllers\Soal7Controller;
use App\Http\Controllers\Soal8Controller;
use App\Http\Controllers\Soal9Controller;
use App\Http\Controllers\Soal10Controller;
use App\Http\Controllers\Soal11Controller;
use App\Http\Controllers\Soal12Controller;
use App\Http\Controllers\Soal13Controller;
use App\Http\Controllers\Soal14Controller;
use App\Http\Controllers\Soal15Controller;
use App\Http\Controllers\Soal16Controller;
use App\Http\Controllers\Soal17Controller;
use App\Http\Controllers\UjiKompetensiController;

// use App\Http\Controllers\OverviewController; // This line is commented out to avoid the error

use App\Http\Controllers\OTPController;
use App\Http\Controllers\PasswordResetController;

// Route untuk OTP dan Password Reset
Route::post('/otp/send', [OTPController::class, 'requestOtp']);
Route::post('/otp/verify', [OTPController::class, 'verifyOtp']);
Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);

// Route untuk User dan Login
Route::post('/users', [UserController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);

// Rute yang dilindungi autentikasi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::post('/users', [UserController::class,'store']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/users/profile', [UserController::class, 'profile']);

    Route::post('/soal1', [Soal1Controller::class, 'store']);    // Simpan atau update
    Route::get('/soal1', [Soal1Controller::class, 'show']);      // Ambil data user
    Route::put('/soal1', [Soal1Controller::class, 'update']);    // Perbarui data user
    Route::delete('/soal1', [Soal1Controller::class, 'destroy']);// Hapus data user

    Route::get('/soal2', [Soal2Controller::class, 'index']);
    Route::post('/soal2', [Soal2Controller::class, 'store']);
    Route::post('/update2', [Soal2Controller::class, 'update']);
    Route::delete('/soal2', [Soal2Controller::class, 'destroy']);
    Route::get('/soal2/download/{field}', [Soal2Controller::class, 'download']);

    Route::post('/soal3', [Soal3Controller::class, 'store']);
    Route::get('/soal3', [Soal3Controller::class, 'show']);
    Route::put('/soal3', [Soal3Controller::class, 'update']);
    Route::delete('/soal3', [Soal3Controller::class, 'destroy']);

    Route::post('/soal4', [Soal4Controller::class, 'store']); // Simpan data
    Route::get('/soal4', [Soal4Controller::class, 'index']); // Tampilkan data user yang login
    Route::post('/update4', [Soal4Controller::class, 'update']); // Update data
    Route::delete('/soal4', [Soal4Controller::class, 'destroy']); // Hapus data

    Route::get('/soal5', [Soal5Controller::class, 'index']);
    Route::post('/soal5', [Soal5Controller::class, 'store']);
    Route::post('/update5', [Soal5Controller::class, 'update']);
    Route::delete('/soal5', [Soal5Controller::class, 'destroy']);

    Route::get('/soal6', [Soal6Controller::class, 'index']); // Ambil data penghargaan
    Route::post('/soal6', [Soal6Controller::class, 'store']); // Upload penghargaan
    Route::post('/update6', [Soal6Controller::class, 'update']); // Update penghargaan
    Route::delete('/soal6', [Soal6Controller::class, 'destroy']); // Hapus penghargaan

    Route::get('/soal7', [Soal7Controller::class, 'index']);
    Route::post('/soal7', [Soal7Controller::class, 'store']);
    Route::post('/update7', [Soal7Controller::class, 'update']);
    Route::delete('/soal7', [Soal7Controller::class, 'destroy']);

    Route::get('/soal8', [Soal8Controller::class, 'index']);
    Route::post('/soal8', [Soal8Controller::class, 'store']);
    Route::post('/update8', [Soal8Controller::class, 'update']);
    Route::delete('/soal8', [Soal8Controller::class, 'destroy']);

    Route::get('/soal9', [Soal9Controller::class, 'index']);
    Route::post('/soal9', [Soal9Controller::class, 'store']);
    Route::post('/update9', [Soal9Controller::class, 'update']);
    Route::delete('/soal9', [Soal9Controller::class, 'destroy']);

    Route::get('/soal9', [Soal9Controller::class, 'index']);
    Route::post('/soal9', [Soal9Controller::class, 'store']);
    Route::post('/update9', [Soal9Controller::class, 'update']);
    Route::delete('/soal9', [Soal9Controller::class, 'destroy']);

    Route::get('/soal10', [Soal10Controller::class, 'index']);
    Route::post('/soal10', [Soal10Controller::class, 'store']);
    Route::post('/update10', [Soal10Controller::class, 'update']);
    Route::delete('/soal10', [Soal10Controller::class, 'destroy']);

    Route::get('/soal11', [Soal11Controller::class, 'index']);
    Route::post('/soal11', [Soal11Controller::class, 'store']);
    Route::post('/update11', [Soal11Controller::class, 'update']);
    Route::delete('/soal11', [Soal11Controller::class, 'destroy']);

    Route::get('/soal12', [Soal12Controller::class, 'index']);
    Route::post('/soal12', [Soal12Controller::class, 'store']);
    Route::post('/update12', [Soal12Controller::class, 'update']);
    Route::delete('/soal12', [Soal12Controller::class, 'destroy']);

    Route::get('/soal13', [Soal13Controller::class, 'index']);
    Route::post('/soal13', [Soal13Controller::class, 'store']);
    Route::post('/update13', [Soal13Controller::class, 'update']);
    Route::delete('/soal13', [Soal13Controller::class, 'destroy']);

    Route::get('/soal14', [Soal14Controller::class, 'index']);
    Route::post('/soal14', [Soal14Controller::class, 'store']);
    Route::post('/update14', [Soal14Controller::class, 'update']);
    Route::delete('/soal14', [Soal14Controller::class, 'destroy']);

    Route::get('/soal15', [Soal15Controller::class, 'index']);
    Route::post('/soal15', [Soal15Controller::class, 'store']);
    Route::post('/update15', [Soal15Controller::class, 'update']);
    Route::delete('/soal15', [Soal15Controller::class, 'destroy']);

    Route::get('/soal16', [Soal16Controller::class, 'index']);
    Route::post('/soal16', [Soal16Controller::class, 'store']);
    Route::post('/update16', [Soal16Controller::class, 'update']);
    Route::delete('/soal16', [Soal16Controller::class, 'destroy']);

    Route::get('/soal17', [Soal17Controller::class, 'index']);
    Route::post('/soal17', [Soal17Controller::class, 'store']);
    Route::post('/update17', [Soal17Controller::class, 'update']);
    Route::delete('/soal17', [Soal17Controller::class, 'destroy']);

    Route::get('/overview', [OverviewController::class, 'index']);

    Route::get('/users', [ProfileController::class, 'profile']);         // Menampilkan profile
    Route::post('/users', [ProfileController::class, 'updateProfile']);  // Update profile (dengan _method: PUT)
    Route::delete('/users', [ProfileController::class, 'destroyProfile']); // Hapus akun

    Route::get('/profile', [ProfileController::class, 'show']);         // Menampilkan profile dengan nilai
    Route::post('/updateprofile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    Route::get('/overview', [UjiKompetensiController::class, 'overview']);
    Route::post('/kumpul', [UjiKompetensiController::class, 'submit']);
    Route::get('/check-availability', [UjiKompetensiController::class, 'checkAvailability']);
});
