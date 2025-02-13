<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\OTPController;

use App\Http\Controllers\PasswordResetController;

// Route::post('/register', [UserController::class, 'register']); //ini buat nantii biar klo register ada di halaman utama /
Route::post('/otp/send', [OTPController::class, 'requestOtp']);
Route::post('/otp/verify', [OTPController::class, 'verifyOtp']);
Route::post('/users', [UserController::class, 'store']);
Route::apiResource('/products', ProductController::class);



Route::post('forgot-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('reset-password', [PasswordResetController::class, 'resetPassword']);



Route::post('/login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::apiResource('products', ProductController::class);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::get('/users/profile', [UserController::class, 'profile']);
});
