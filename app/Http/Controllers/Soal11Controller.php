<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal11;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal11Controller extends Controller
{
    private $fields = [
        'penguji_sertifikasi' => ['count' => 2, 'points' => 10, 'max' => 20],
        'juri_ipbi' => ['count' => 2, 'points' => 10, 'max' => 20],
        'juri_non_ipbi' => ['count' => 2, 'points' => 5, 'max' => 10]
    ];

    public function index()
    {
        $soal11 = Soal11::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal11
        ]);
    }

    public function store(Request $request)
    {
        $validationRules = [];
        foreach ($this->fields as $field => $config) {
            for ($i = 1; $i <= $config['count']; $i++) {
                $validationRules["{$field}{$i}"] = 'nullable|file|mimes:pdf|max:2048';
            }
        }

        $request->validate($validationRules);

        $paths = [];
        $nilai = 0;

        foreach ($this->fields as $field => $config) {
            $fieldNilai = 0;
            for ($i = 1; $i <= $config['count']; $i++) {
                $fieldName = "{$field}{$i}";
                if ($request->hasFile($fieldName)) {
                    $paths[$fieldName] = $request->file($fieldName)->store('uploads/pdf', 'public');
                    $fieldNilai += $config['points'];
                }
            }
            $nilai += min($fieldNilai, $config['max']);
        }

        $nilai = min($nilai, 30);

        $soal11 = Soal11::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal11
        ], 201);
    }

    public function update(Request $request)
    {
        $soal11 = Soal11::where('user_id', Auth::id())->first();

        if (!$soal11) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $soal11->fill($request->all());

        // Hitung ulang nilai berdasarkan field yang ada
        $nilai = 0;
        foreach ($this->fields as $field => $config) {
            $fieldNilai = 0;
            for ($i = 1; $i <= $config['count']; $i++) {
                $fieldName = "{$field}{$i}";
                if ($soal11->$fieldName) {
                    $fieldNilai += $config['points'];
                }
            }
            $nilai += min($fieldNilai, $config['max']);
        }

        $nilai = min($nilai, 30);
        $soal11->nilai = $nilai;
        $soal11->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal11
        ]);
    }

    public function destroy()
    {
        $soal11 = Soal11::where('user_id', Auth::id())->first();

        if (!$soal11) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach ($this->fields as $field => $config) {
            for ($i = 1; $i <= $config['count']; $i++) {
                $fieldName = "{$field}{$i}";
                if ($soal11->$fieldName) {
                    Storage::disk('public')->delete($soal11->$fieldName);
                }
            }
        }

        $soal11->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}