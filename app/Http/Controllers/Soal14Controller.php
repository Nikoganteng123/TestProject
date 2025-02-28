<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal14;
use Illuminate\Support\Facades\Auth;

class Soal14Controller extends Controller
{
    public function index()
    {
        $soal14 = Soal14::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal14
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ngajar_online' => 'required|in:sendiri,team'
        ]);

        $nilai = $validatedData['ngajar_online'] === 'sendiri' ? 10 : 8;

        $soal14 = Soal14::create([
            'user_id' => Auth::id(),
            'ngajar_online' => $validatedData['ngajar_online'],
            'nilai' => $nilai
        ]);

        return response()->json([
            'message' => 'Berhasil menyimpan data!',
            'data' => $soal14
        ], 201);
    }

    public function update(Request $request)
    {
        $soal14 = Soal14::where('user_id', Auth::id())->first();

        if (!$soal14) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $soal14->fill($request->all());

        // Hitung ulang nilai berdasarkan field yang ada
        $nilai = $soal14->ngajar_online === 'sendiri' ? 10 : ($soal14->ngajar_online === 'team' ? 8 : 0);
        $soal14->nilai = $nilai;
        $soal14->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal14
        ]);
    }

    public function destroy()
    {
        $soal14 = Soal14::where('user_id', Auth::id())->first();

        if (!$soal14) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $soal14->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}