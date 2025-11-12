<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    public function requestOtp(Request $request)
    {
        $request->validate(['nomor' => 'required|string']);

        // Hapus OTP lama jika ada
        Otp::where('nomor', $request->nomor)->delete();

        // Generate OTP
        $otp = rand(100000, 999999);

        // Simpan ke database
        Otp::create([
            'nomor' => $request->nomor,
            'otp' => $otp
        ]);

        // Kirim OTP melalui Fonnte
        $response = Http::withHeaders([
            'Authorization' => 's21uq4B47D3Ab74x7zJx'
        ])->post('https://api.fonnte.com/send', [
                    'target' => $request->nomor,
                    'message' => "Your OTP: " . $otp
                ]);

        return response()->json(['message' => 'OTP sent!', 'response' => $response->json()]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'nomor' => 'required|string',
            'otp' => 'required|string'
        ]);

        // Cari OTP yang cocok dengan nomor dan OTP yang dimasukkan
        $otpRecord = Otp::where('nomor', $request->nomor)
            ->where('otp', $request->otp)
            ->first();

        if ($otpRecord) {
            // Jika OTP cocok
            return response()->json(['message' => 'OTP valid!'], 200);
        }

        // Jika OTP tidak ditemukan atau tidak cocok
        return response()->json(['message' => 'OTP salah!'], 400);
    }

}
