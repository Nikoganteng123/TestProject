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
                'title' => $this->getSoalTitle($i) // Custom titles for each soal
            ];
        }

        return response()->json([
            'data' => $overview,
            'total_nilai' => array_sum(array_column($overview, 'nilai'))
        ]);
    }

    // Helper method to define titles (customize as needed)
    private function getSoalTitle($number)
    {
        $titles = [
            1 => 'Pendidikan Formal',
            2 => 'Mengikuti Pelatihan',
            // Add titles for 3-6 as needed
            7 => 'Kejuaraan/Lomba Merangkai Bunga',
            // Add titles for 8-17 as needed
        ];
        return $titles[$number] ?? "Soal {$number}";
    }
}