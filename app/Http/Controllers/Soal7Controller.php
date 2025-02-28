<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal7;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal7Controller extends Controller
{
    public function index()
    {
        $soal7 = Soal7::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal7
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'juara_nasional_dpp' => 'nullable|file|mimes:pdf|max:2048',
            'juara_non_dpp' => 'nullable|file|mimes:pdf|max:2048',
            'juara_instansi_lain' => 'nullable|file|mimes:pdf|max:2048',
            'juara_internasional' => 'nullable|file|mimes:pdf|max:2048',
            'peserta_lomba_1' => 'nullable|file|mimes:pdf|max:2048',
            'peserta_lomba_2' => 'nullable|file|mimes:pdf|max:2048',
            'peserta_lomba_3' => 'nullable|file|mimes:pdf|max:2048',
            'peserta_lomba_4' => 'nullable|file|mimes:pdf|max:2048',
            'peserta_lomba_5' => 'nullable|file|mimes:pdf|max:2048',
            'juri_lomba_1' => 'nullable|file|mimes:pdf|max:2048',
            'juri_lomba_2' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $paths = [];
        $fields = [
            'juara_nasional_dpp', 'juara_non_dpp', 'juara_instansi_lain', 
            'juara_internasional', 
            'peserta_lomba_1', 'peserta_lomba_2', 'peserta_lomba_3', 
            'peserta_lomba_4', 'peserta_lomba_5',
            'juri_lomba_1', 'juri_lomba_2'
        ];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $nilai = 0;
        if (!empty($paths['juara_nasional_dpp'])) $nilai += 15;
        if (!empty($paths['juara_non_dpp'])) $nilai += 10;
        if (!empty($paths['juara_instansi_lain'])) $nilai += 5;
        if (!empty($paths['juara_internasional'])) $nilai += 15;

        $pesertaFields = ['peserta_lomba_1', 'peserta_lomba_2', 'peserta_lomba_3', 'peserta_lomba_4', 'peserta_lomba_5'];
        foreach ($pesertaFields as $field) {
            if (!empty($paths[$field])) $nilai += 1;
        }

        $juriFields = ['juri_lomba_1', 'juri_lomba_2'];
        foreach ($juriFields as $field) {
            if (!empty($paths[$field])) $nilai += 3;
        }

        $nilai = min($nilai, 50);

        $soal7 = Soal7::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal7
        ], 201);
    }

    public function update(Request $request)
    {
        $soal7 = Soal7::where('user_id', Auth::id())->first();

        if (!$soal7) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $soal7->fill($request->all());

        // Hitung ulang nilai berdasarkan field yang ada
        $nilai = 0;
        if ($soal7->juara_nasional_dpp) $nilai += 15;
        if ($soal7->juara_non_dpp) $nilai += 10;
        if ($soal7->juara_instansi_lain) $nilai += 5;
        if ($soal7->juara_internasional) $nilai += 15;

        $pesertaFields = ['peserta_lomba_1', 'peserta_lomba_2', 'peserta_lomba_3', 'peserta_lomba_4', 'peserta_lomba_5'];
        foreach ($pesertaFields as $field) {
            if ($soal7->$field) $nilai += 1;
        }

        $juriFields = ['juri_lomba_1', 'juri_lomba_2'];
        foreach ($juriFields as $field) {
            if ($soal7->$field) $nilai += 3;
        }

        $nilai = min($nilai, 50);
        $soal7->nilai = $nilai;
        $soal7->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal7
        ]);
    }

    public function destroy()
    {
        $soal7 = Soal7::where('user_id', Auth::id())->first();

        if (!$soal7) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $fields = [
            'juara_nasional_dpp', 'juara_non_dpp', 'juara_instansi_lain', 
            'juara_internasional', 
            'peserta_lomba_1', 'peserta_lomba_2', 'peserta_lomba_3', 
            'peserta_lomba_4', 'peserta_lomba_5',
            'juri_lomba_1', 'juri_lomba_2'
        ];

        foreach ($fields as $field) {
            if ($soal7->$field) {
                Storage::disk('public')->delete($soal7->$field);
            }
        }

        $soal7->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}