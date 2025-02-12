<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Buat token unik
        $token = Str::random(60);

        // Simpan token ke database dengan waktu kedaluwarsa
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now(), 'expires_at' => now()->addMinutes(30)]
        );

        // Kirim notifikasi ke user
        $user = User::where('email', $request->email)->first();
        $user->notify(new ResetPasswordNotification($token));

        return response()->json([
            'message' => 'Reset password token sent to your email',
            'token' => $token // Menambahkan token ke response untuk testing
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        // Cek token di database dan apakah token sudah kedaluwarsa
        $reset = DB::table('password_resets')
            ->where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$reset) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        // Update password user berdasarkan email dari token
        User::where('email', $reset->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token reset
        DB::table('password_resets')->where('token', $request->token)->delete();

        return response()->json(['message' => 'Password has been reset successfully!']);
    }
}