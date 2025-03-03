<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal5;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal5Controller extends Controller
{
    private $fileFields = ['sertifikat_1', 'sertifikat_2', 'sertifikat_3'];

    public function index()
    {
        $soal5 = Soal5::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal5]);
    }

    public function store(Request $request)
    {
        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf|max:2048'));

        $paths = [];
        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $nilai = 0;
        if (!empty($paths['sertifikat_1'])) $nilai += 3;
        if (!empty($paths['sertifikat_2'])) $nilai += 4;
        if (!empty($paths['sertifikat_3'])) $nilai += 5;

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

        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf|max:2048'));

        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($soal5->$field && Storage::disk('public')->exists($soal5->$field)) {
                    Storage::disk('public')->delete($soal5->$field);
                }
                $soal5->$field = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $nilai = 0;
        if ($soal5->sertifikat_1) $nilai += 3;
        if ($soal5->sertifikat_2) $nilai += 4;
        if ($soal5->sertifikat_3) $nilai += 5;

        $nilai = min($nilai, 12);
        $soal5->nilai = $nilai;
        $soal5->save();

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

        foreach ($this->fileFields as $field) {
            if ($soal5->$field) {
                Storage::disk('public')->delete($soal5->$field);
            }
        }

        $soal5->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}