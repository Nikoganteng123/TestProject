<?php

namespace App\Http\Controllers;

use App\Models\Soal1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Soal1Controller extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tingkat_pendidikan' => 'required|in:SMP-D3,S1,S2_atau_lebih',
        ]);

        $user_id = Auth::id();
        $nilai = $this->getNilaiByTingkatPendidikan($request->tingkat_pendidikan);
        
        $nilai = min($nilai, 5);
        $soal1 = Soal1::updateOrCreate(
            ['user_id' => $user_id], 
            [
                'tingkat_pendidikan' => $request->tingkat_pendidikan,
                'nilai' => $nilai,
            ]
        );

        return response()->json($soal1, 201);
    }

    public function show()
    {
        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();
        
        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($soal1);
    }

    public function update(Request $request)
    {
        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();

        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $soal1->fill($request->all());
        
        // Hitung ulang nilai berdasarkan field yang ada
        $nilai = $soal1->tingkat_pendidikan ? $this->getNilaiByTingkatPendidikan($soal1->tingkat_pendidikan) : 0;
        $soal1->nilai = min($nilai, 5);
        $soal1->save();

        return response()->json($soal1);
    }

    public function destroy()
    {
        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();

        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $soal1->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    private function getNilaiByTingkatPendidikan($tingkat_pendidikan)
    {
        $nilai = 0;
        switch ($tingkat_pendidikan) {
            case 'SMP-D3':
                $nilai = 2;
                break;
            case 'S1':
                $nilai = 4;
                break;
            case 'S2_atau_lebih':
                $nilai = 5;
                break;
        }
        return $nilai;
    }
}