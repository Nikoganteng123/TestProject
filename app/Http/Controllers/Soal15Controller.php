<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal15;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal15Controller extends Controller
{
    private $fields = [
        'ikebana_murid' => ['points' => 5],
        'ikebana_guru' => ['points' => 15],
        'rangkaian_tradisional' => ['points' => 10],
        'lainnya' => ['points' => 5]
    ];

    public function index()
    {
        $soal15 = Soal15::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal15
        ]);
    }

    public function store(Request $request)
    {
        $validationRules = [];
        foreach ($this->fields as $field => $config) {
            $validationRules[$field] = 'nullable|file|mimes:pdf|max:2048';
        }

        $request->validate($validationRules);

        $paths = [];
        $nilai = 0;

        foreach ($this->fields as $field => $config) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
                $nilai += $config['points'];
            }
        }

        $nilai = min($nilai, 20);

        $soal15 = Soal15::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal15
        ], 201);
    }

    public function update(Request $request)
    {
        $soal15 = Soal15::where('user_id', Auth::id())->first();

        if (!$soal15) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $soal15->fill($request->all());

        // Hitung ulang nilai berdasarkan field yang ada
        $nilai = 0;
        foreach ($this->fields as $field => $config) {
            if ($soal15->$field) {
                $nilai += $config['points'];
            }
        }

        $nilai = min($nilai, 20);
        $soal15->nilai = $nilai;
        $soal15->save();

        return response()->json([
            'message' => 'Berhasil memperbarui file!',
            'data' => $soal15
        ]);
    }

    public function destroy()
    {
        $soal15 = Soal15::where('user_id', Auth::id())->first();

        if (!$soal15) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach ($this->fields as $field => $config) {
            if ($soal15->$field) {
                Storage::disk('public')->delete($soal15->$field);
            }
        }

        $soal15->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!'
        ], 200);
    }
}