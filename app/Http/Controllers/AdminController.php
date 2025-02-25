<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Soal1;
use App\Models\Soal2;
use App\Models\Soal3;
use App\Models\Soal4;
use App\Models\Soal5;
use App\Models\Soal6;
use App\Models\Soal7;
use App\Models\Soal8;
use App\Models\Soal9;
use App\Models\Soal10;
use App\Models\Soal11;
use App\Models\Soal12;
use App\Models\Soal13;
use App\Models\Soal14;
use App\Models\Soal15;
use App\Models\Soal16;
use App\Models\Soal17;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function users()
    {
        $users = User::all(['id', 'name', 'email', 'created_at']);
        
        return response()->json([
            'data' => $users
        ], 200);
    }
    
    public function userDetail($userId)
    {
        $user = User::findOrFail($userId);
        
        $soals = [
            'soal1' => Soal1::where('user_id', $userId)->first(),
            'soal2' => Soal2::where('user_id', $userId)->first(),
            'soal3' => Soal3::where('user_id', $userId)->first(),
            'soal4' => Soal4::where('user_id', $userId)->first(),
            'soal5' => Soal5::where('user_id', $userId)->first(),
            'soal6' => Soal6::where('user_id', $userId)->first(),
            'soal7' => Soal7::where('user_id', $userId)->first(),
            'soal8' => Soal8::where('user_id', $userId)->first(),
            'soal9' => Soal9::where('user_id', $userId)->first(),
            'soal10' => Soal10::where('user_id', $userId)->first(),
            'soal11' => Soal11::where('user_id', $userId)->first(),
            'soal12' => Soal12::where('user_id', $userId)->first(),
            'soal13' => Soal13::where('user_id', $userId)->first(),
            'soal14' => Soal14::where('user_id', $userId)->first(),
            'soal15' => Soal15::where('user_id', $userId)->first(),
            'soal16' => Soal16::where('user_id', $userId)->first(),
            'soal17' => Soal17::where('user_id', $userId)->first(),
        ];
        
        $totalNilai = 0;
        foreach ($soals as $soal) {
            if ($soal && isset($soal->nilai)) {
                $totalNilai += $soal->nilai;
            }
        }
        
        return response()->json([
            'user' => $user,
            'soals' => $soals,
            'totalNilai' => $totalNilai
        ], 200);
    }
    
    public function getSoal($soalNumber, $userId)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        
        if (!$soal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        
        return response()->json(['data' => $soal], 200);
    }
    
    public function updateSoal(Request $request, $soalNumber, $userId)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        
        if (!$soal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        
        $request->validate([
            'nilai' => 'nullable|numeric|min:0|max:100'
        ]);
        
        if ($request->has('nilai')) {
            $soal->update(['nilai' => $request->nilai]);
        }
        
        return response()->json([
            'message' => 'Berhasil mengupdate data',
            'data' => $soal
        ], 200);
    }
    
    public function deleteSoal($soalNumber, $userId)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        
        if (!$soal) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        
        $fileFields = $this->getFileFields($soalNumber);
        
        foreach ($fileFields as $field) {
            if ($soal->$field && Storage::disk('public')->exists($soal->$field)) {
                Storage::disk('public')->delete($soal->$field);
            }
        }
        
        $soal->delete();
        
        return response()->json(['message' => 'Berhasil menghapus data'], 200);
    }
    
    private function getFileFields($soalNumber)
    {
        // Sementara, isi berdasarkan yang kamu berikan + asumsi
        $fileFieldsMap = [
            '1' => ['ijazah', 'transkrip'],
            '2' => ['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'],
            '3' => [], // Isi dengan kolom file untuk soal3
            '4' => [], // Isi dengan kolom file untuk soal4
            '5' => [], // Isi dengan kolom file untuk soal5
            '6' => [], // Isi dengan kolom file untuk soal6
            '7' => ['juara_nasional_dpp', 'juara_non_dpp', 'juara_instansi_lain', 'juara_internasional', 
                    'peserta_lomba_1', 'peserta_lomba_2', 'peserta_lomba_3', 'peserta_lomba_4', 'peserta_lomba_5',
                    'juri_lomba_1', 'juri_lomba_2'],
            '8' => [], // Isi dengan kolom file untuk soal8
            '9' => [], // Isi dengan kolom file untuk soal9
            '10' => [], // Isi dengan kolom file untuk soal10
            '11' => [], // Isi dengan kolom file untuk soal11
            '12' => [], // Isi dengan kolom file untuk soal12
            '13' => [], // Isi dengan kolom file untuk soal13
            '14' => [], // Isi dengan kolom file untuk soal14
            '15' => [], // Isi dengan kolom file untuk soal15
            '16' => [], // Isi dengan kolom file untuk soal16
            '17' => [], // Isi dengan kolom file untuk soal17
        ];
        
        return $fileFieldsMap[$soalNumber] ?? [];
    }
    
    public function viewFile($soalNumber, $userId, $fieldName)
    {
        $modelClass = "App\\Models\\Soal{$soalNumber}";
        
        if (!class_exists($modelClass)) {
            return response()->json(['message' => 'Soal tidak ditemukan'], 404);
        }
        
        $soal = $modelClass::where('user_id', $userId)->first();
        
        if (!$soal || !$soal->$fieldName) {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
        
        $path = $soal->$fieldName;
        
        if (!Storage::disk('public')->exists($path)) {
            return response()->json(['message' => 'File tidak ditemukan di storage'], 404);
        }
        
        return response()->file(storage_path('app/public/' . $path));
    }
}