<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba autentikasi pengguna
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Ambil data pengguna yang sudah diautentikasi
        $user = Auth::user();
        
        // Buat token autentikasi
        $token = $user->createToken('ApiToken')->plainTextToken;

        // Tentukan redirect berdasarkan status admin
        $redirectTo = $user->is_admin ? '/admin' : '/';

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Login successful',
            'user' => $user->only(['id', 'name', 'email', 'is_admin']),
            'redirect_to' => $redirectTo,
        ], 200);
    }

    public function logout(Request $request)
    {
        // Hapus semua token pengguna yang sedang login
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'message' => 'Successfully fetched user',
            'data' => $request->user()
        ], 200);
    }
}