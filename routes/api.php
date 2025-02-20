<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Soal1Controller;
use App\Http\Controllers\Soal2Controller;
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


});
