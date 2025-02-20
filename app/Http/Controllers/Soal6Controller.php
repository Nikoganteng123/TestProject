<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal6;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal6Controller extends Controller
{
    public function index()
    {
        $soal6 = Soal6::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal6]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'penghargaan_daerah' => 'nullable|file|mimes:pdf|max:2048',
            'penghargaan_nasional' => 'nullable|file|mimes:pdf|max:2048',
            'penghargaan_internasional' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $paths = [];
        foreach (['penghargaan_daerah', 'penghargaan_nasional', 'penghargaan_internasional'] as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/penghargaan', 'public');
            }
        }

        // Hitung poin
        $nilai = 0;
        if (!empty($paths['penghargaan_daerah'])) $nilai += 5;  // Daerah
        if (!empty($paths['penghargaan_nasional'])) $nilai += 10; // Nasional
        if (!empty($paths['penghargaan_internasional'])) $nilai += 15; // Internasional

        // Maksimal 25 poin
        $nilai = min($nilai, 30);

        $soal6 = Soal6::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah penghargaan!',
            'data' => $soal6
        ], 201);
    }

    public function update(Request $request)
    {
        $soal6 = Soal6::where('user_id', Auth::id())->first();
        if (!$soal6) return response()->json(['message' => 'Data tidak ditemukan!'], 404);

        $validatedData = $request->validate([
            'penghargaan_daerah' => 'nullable|file|mimes:pdf|max:2048',
            'penghargaan_nasional' => 'nullable|file|mimes:pdf|max:2048',
            'penghargaan_internasional' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $updatedData = [];

        foreach ($validatedData as $field => $file) {
            if ($request->hasFile($field)) {
                if ($soal6->$field) Storage::disk('public')->delete($soal6->$field);

                $filePath = $request->file($field)->store('uploads/penghargaan', 'public');
                $updatedData[$field] = $filePath;
            }
        }

        if (!empty($updatedData)) {
            $soal6->update($updatedData);
        }

        // Hitung ulang poin
        $nilai = 0;
        if ($soal6->penghargaan_daerah) $nilai += 5;
        if ($soal6->penghargaan_nasional) $nilai += 10;
        if ($soal6->penghargaan_internasional) $nilai += 15;

        // Maksimal 25 poin
        $nilai = min($nilai, 30);
        $soal6->update(['nilai' => $nilai]);

        return response()->json(['message' => 'Data berhasil diperbarui!', 'data' => $soal6]);
    }

    public function destroy()
    {
        $soal6 = Soal6::where('user_id', Auth::id())->first();
        if (!$soal6) return response()->json(['message' => 'Data tidak ditemukan!'], 404);

        foreach (['penghargaan_daerah', 'penghargaan_nasional', 'penghargaan_internasional'] as $field) {
            if ($soal6->$field) Storage::disk('public')->delete($soal6->$field);
        }

        $soal6->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}
