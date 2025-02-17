<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Soal1Controller;
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
    
    // Route untuk Soal1 dengan middleware auth dan otorisasi
    Route::apiResource('/soal1', Soal1Controller::class);
});
