<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Soal3;

class Soal3Controller extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bahasa_inggris' => 'nullable|in:Dasar,Fasih',
            'bahasa_lain1' => 'nullable|string',
            'bahasa_lain2' => 'nullable|string',
            'bahasa_lain3' => 'nullable|string',
            'bahasa_lain4' => 'nullable|string',
        ]);

        $user_id = Auth::id();
        $nilai = $this->hitungNilai($request);

        $soal3 = Soal3::updateOrCreate(
            ['user_id' => $user_id],
            [
                'bahasa_inggris' => $request->bahasa_inggris,
                'bahasa_lain1' => $request->bahasa_lain1,
                'bahasa_lain2' => $request->bahasa_lain2,
                'bahasa_lain3' => $request->bahasa_lain3,
                'bahasa_lain4' => $request->bahasa_lain4,
                'nilai' => $nilai,
            ]
        );

        return response()->json($soal3, 201);
    }

    public function show()
    {
        $user_id = Auth::id();
        $soal3 = Soal3::where('user_id', $user_id)->first();

        if (!$soal3) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($soal3);
    }

    public function update(Request $request)
    {
        $request->validate([
            'bahasa_inggris' => 'nullable|in:Dasar,Fasih',
            'bahasa_lain1' => 'nullable|string',
            'bahasa_lain2' => 'nullable|string',
            'bahasa_lain3' => 'nullable|string',
            'bahasa_lain4' => 'nullable|string',
        ]);

        $user_id = Auth::id();
        $soal3 = Soal3::where('user_id', $user_id)->first();

        if (!$soal3) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Mempertahankan nilai lama jika request tidak mengirimkan nilai baru
        $soal3->fill([
            'bahasa_inggris' => $request->bahasa_inggris ?? $soal3->bahasa_inggris,
            'bahasa_lain1' => $request->bahasa_lain1 ?? $soal3->bahasa_lain1,
            'bahasa_lain2' => $request->bahasa_lain2 ?? $soal3->bahasa_lain2,
            'bahasa_lain3' => $request->bahasa_lain3 ?? $soal3->bahasa_lain3,
            'bahasa_lain4' => $request->bahasa_lain4 ?? $soal3->bahasa_lain4,
        ]);

        // Hitung ulang nilai
        $soal3->nilai = $this->hitungNilai($soal3);

        // Simpan perubahan
        $soal3->save();

        return response()->json($soal3);
    }


    public function destroy()
    {
        $user_id = Auth::id();
        $soal3 = Soal3::where('user_id', $user_id)->first();

        if (!$soal3) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $soal3->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    private function hitungNilai($request)
    {
        $nilai = 0;

        if ($request->bahasa_inggris === 'Dasar')
            $nilai += 3;
        if ($request->bahasa_inggris === 'Fasih')
            $nilai += 5;

        if ($request->bahasa_lain1)
            $nilai += 5;
        if ($request->bahasa_lain2)
            $nilai += 5;
        if ($request->bahasa_lain3)
            $nilai += 5;
        if ($request->bahasa_lain4)
            $nilai += 5;

        return $nilai;
    }
}
