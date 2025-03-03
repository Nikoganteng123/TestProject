<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal12;
use Illuminate\Support\Facades\Auth;

class Soal12Controller extends Controller
{
    public function index()
    {
        $soal12 = Soal12::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal12]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(['jabatan' => 'required|in:inti,biasa']);

        $nilai = $validatedData['jabatan'] === 'inti' ? 10 : 5;

        $soal12 = Soal12::create([
            'user_id' => Auth::id(),
            'jabatan' => $validatedData['jabatan'],
            'nilai' => $nilai
        ]);

        return response()->json([
            'message' => 'Berhasil menyimpan data!',
            'data' => $soal12
        ], 201);
    }

    public function update(Request $request)
    {
        $soal12 = Soal12::where('user_id', Auth::id())->first();
        if (!$soal12) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $validatedData = $request->validate(['jabatan' => 'required|in:inti,biasa']);
        $soal12->jabatan = $validatedData['jabatan'];

        $nilai = $soal12->jabatan === 'inti' ? 10 : 5;
        $soal12->nilai = $nilai;
        $soal12->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal12
        ]);
    }

    public function destroy()
    {
        $soal12 = Soal12::where('user_id', Auth::id())->first();
        if (!$soal12) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $soal12->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}