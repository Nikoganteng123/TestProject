<?php

// app/Http/Controllers/UjiKompetensiController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UjiKompetensiController extends Controller
{
    public function submit(Request $request)
    {
        $user = Auth::user();
        
        // Update waktu pengumpulan terakhir dan status
        $user->last_submission_date = Carbon::now();
        $user->can_take_test = false; // Blokir akses uji kompetensi
        $user->save();

        return response()->json([
            'message' => 'Uji kompetensi berhasil dikumpulkan. Anda dapat mengakses kembali setelah 3 bulan.',
            'next_available_date' => Carbon::now()->addMonths(3)->toDateTimeString()
        ], 200);
    }

    public function checkAvailability(Request $request)
    {
        $user = Auth::user();

        if (!$user->last_submission_date) {
            return response()->json(['can_take_test' => true], 200);
        }

        $nextAvailableDate = Carbon::parse($user->last_submission_date)->addMonths(3);
        $canTakeTest = Carbon::now()->greaterThanOrEqualTo($nextAvailableDate);

        if ($canTakeTest && !$user->can_take_test) {
            $user->can_take_test = true;
            $user->save();
        }

        return response()->json([
            'can_take_test' => $canTakeTest,
            'next_available_date' => $nextAvailableDate->toDateTimeString(),
            'remaining_days' => $canTakeTest ? 0 : Carbon::now()->diffInDays($nextAvailableDate)
        ], 200);
    }

    // Endpoint yang sudah ada untuk overview
    public function overview(Request $request)
    {
        $user = Auth::user();
        
        // Logika Anda untuk mengambil status soal
        $overview = [
            'soal1' => ['title' => 'Soal 1', 'completed' => true, 'nilai' => 85],
            'soal2' => ['title' => 'Soal 2', 'completed' => false, 'nilai' => null],
            // ... tambahkan sesuai kebutuhan
        ];
        
        $totalNilai = collect($overview)->sum(fn($item) => $item['nilai'] ?? 0);

        return response()->json([
            'data' => $overview,
            'total_nilai' => $totalNilai,
            'can_take_test' => $user->can_take_test
        ], 200);
    }
}