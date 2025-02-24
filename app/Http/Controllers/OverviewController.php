<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OverviewController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $overview = [];

        // Loop through all 17 questions
        for ($i = 1; $i <= 17; $i++) {
            $model = "\\App\\Models\\Soal{$i}"; // Dynamically reference the model (Soal1, Soal2, etc.)
            $exists = class_exists($model) && $model::where('user_id', $userId)->exists();
            $nilai = $exists ? $model::where('user_id', $userId)->value('nilai') ?? 0 : 0;

            $overview["soal{$i}"] = [
                'completed' => $exists, // True if record exists, False if not
                'nilai' => $nilai,
                'title' => "Soal {$i}" // Simple "Soal 1", "Soal 2", etc.
            ];
        }

        return response()->json([
            'data' => $overview,
            'total_nilai' => array_sum(array_column($overview, 'nilai'))
        ]);
    }
}