<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Mengirimkan email dengan token reset password
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Buat token unik
        $token = Str::random(60);

        // Simpan token ke database dengan waktu kedaluwarsa
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now(), 'expires_at' => now()->addMinutes(30)]
        );

        // Kirim email ke user
        Mail::send('emails.reset_password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email)->subject('Reset Password');
        });

        return response()->json(['message' => 'Reset link sent successfully!']);
    }

    // Mereset password menggunakan token
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Cek token di database dan apakah token sudah kedaluwarsa
        $reset = DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->where('expires_at', '>', now())->first();

        if (!$reset) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        // Update password user
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token reset
        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password has been reset successfully!']);
    }
}