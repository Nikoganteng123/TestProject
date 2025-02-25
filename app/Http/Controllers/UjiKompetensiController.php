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
        
        $user->last_submission_date = Carbon::now();
        $user->can_take_test = false;
        $user->save();

        return response()->json([
            'message' => 'Uji kompetensi berhasil dikumpulkan. Anda dapat mengakses kembali setelah 30 detik.',
            'next_available_date' => $user->next_available_date->toDateTimeString()
        ], 200);
    }

    public function checkAvailability(Request $request)
    {
        $user = Auth::user();

        if (!$user->last_submission_date) {
            return response()->json(['can_take_test' => true], 200);
        }

        $nextAvailableDate = $user->next_available_date;
        $canTakeTest = Carbon::now()->greaterThanOrEqualTo($nextAvailableDate);

        if ($canTakeTest && !$user->can_take_test) {
            $user->can_take_test = true;
            $user->save();
        }

        // Ubah remaining_days menjadi remaining_seconds untuk akurasi
        return response()->json([
            'can_take_test' => $canTakeTest,
            'next_available_date' => $nextAvailableDate->toDateTimeString(),
            'remaining_seconds' => $canTakeTest ? 0 : Carbon::now()->diffInSeconds($nextAvailableDate)
        ], 200);
    }

    public function overview(Request $request)
    {
        $user = Auth::user();
        
        $overview = [
            'soal1' => ['title' => 'Soal 1', 'completed' => true, 'nilai' => 85],
            'soal2' => ['title' => 'Soal 2', 'completed' => false, 'nilai' => null],
        ];
        
        $totalNilai = collect($overview)->sum(fn($item) => $item['nilai'] ?? 0);

        return response()->json([
            'data' => $overview,
            'total_nilai' => $totalNilai,
            'can_take_test' => $user->can_take_test
        ], 200);
    }
}