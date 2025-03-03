<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal6;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal6Controller extends Controller
{
    private $fileFields = ['penghargaan_daerah', 'penghargaan_nasional', 'penghargaan_internasional'];

    public function index()
    {
        $soal6 = Soal6::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal6]);
    }

    public function store(Request $request)
    {
        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf|max:2048'));

        $paths = [];
        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/penghargaan', 'public');
            }
        }

        $nilai = 0;
        if (!empty($paths['penghargaan_daerah'])) $nilai += 5;
        if (!empty($paths['penghargaan_nasional'])) $nilai += 10;
        if (!empty($paths['penghargaan_internasional'])) $nilai += 15;

        $nilai = min($nilai, 25);

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
        if (!$soal6) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf|max:2048'));

        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($soal6->$field && Storage::disk('public')->exists($soal6->$field)) {
                    Storage::disk('public')->delete($soal6->$field);
                }
                $soal6->$field = $request->file($field)->store('uploads/penghargaan', 'public');
            }
        }

        $nilai = 0;
        if ($soal6->penghargaan_daerah) $nilai += 5;
        if ($soal6->penghargaan_nasional) $nilai += 10;
        if ($soal6->penghargaan_internasional) $nilai += 15;

        $nilai = min($nilai, 25);
        $soal6->nilai = $nilai;
        $soal6->save();

        return response()->json([
            'message' => 'Data berhasil diperbarui!',
            'data' => $soal6
        ]);
    }

    public function destroy()
    {
        $soal6 = Soal6::where('user_id', Auth::id())->first();
        if (!$soal6) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach ($this->fileFields as $field) {
            if ($soal6->$field) {
                Storage::disk('public')->delete($soal6->$field);
            }
        }

        $soal6->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}