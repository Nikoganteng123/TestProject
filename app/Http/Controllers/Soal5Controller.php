<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal5;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal5Controller extends Controller
{
    public function index()
    {
        $soal5 = Soal5::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal5
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sertifikat_1' => 'nullable|file|mimes:pdf|max:2048',
            'sertifikat_2' => 'nullable|file|mimes:pdf|max:2048',
            'sertifikat_3' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $paths = [];
        foreach (['sertifikat_1', 'sertifikat_2', 'sertifikat_3'] as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        // Perhitungan nilai otomatis berdasarkan sertifikat
        $nilai = 0;
        if (!empty($paths['sertifikat_1'])) $nilai += 3; // Level 1
        if (!empty($paths['sertifikat_2'])) $nilai += 4; // Level 2
        if (!empty($paths['sertifikat_3'])) $nilai += 5; // Level 3

        // Maksimal poin adalah 12
        $nilai = min($nilai, 12);

        $soal5 = Soal5::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal5
        ], 201);
    }

    public function update(Request $request)
    {
        $soal5 = Soal5::where('user_id', Auth::id())->first();

        if (!$soal5) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $validatedData = $request->validate([
            'sertifikat_1' => 'nullable|file|mimes:pdf|max:2048',
            'sertifikat_2' => 'nullable|file|mimes:pdf|max:2048',
            'sertifikat_3' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $updatedData = [];

        foreach ($validatedData as $field => $file) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($soal5->$field) {
                    Storage::disk('public')->delete($soal5->$field);
                }

                // Simpan file baru
                $filePath = $request->file($field)->store('uploads/pdf', 'public');
                $updatedData[$field] = $filePath;
            }
        }

        if (!empty($updatedData)) {
            $soal5->update($updatedData);
        }

        // Hitung ulang nilai
        $nilai = 0;
        if ($soal5->sertifikat_1) $nilai += 3;
        if ($soal5->sertifikat_2) $nilai += 4;
        if ($soal5->sertifikat_3) $nilai += 5;

        // Maksimal 12 poin
        $nilai = min($nilai, 12);

        $soal5->update(['nilai' => $nilai]);

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal5
        ]);
    }

    public function destroy()
    {
        $soal5 = Soal5::where('user_id', Auth::id())->first();

        if (!$soal5) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach (['sertifikat_1', 'sertifikat_2', 'sertifikat_3'] as $field) {
            if ($soal5->$field) {
                Storage::disk('public')->delete($soal5->$field);
            }
        }

        $soal5->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}
