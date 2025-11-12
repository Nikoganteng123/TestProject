<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal4;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal4Controller extends Controller
{
    private $fileFields = [
        'independent_org', 'foreign_school_degree',
        'foreign_school_no_degree_1', 'foreign_school_no_degree_2', 'foreign_school_no_degree_3',
        'foreign_school_no_degree_4', 'foreign_school_no_degree_5',
        'domestic_school_no_degree_1', 'domestic_school_no_degree_2', 'domestic_school_no_degree_3',
        'domestic_school_no_degree_4', 'domestic_school_no_degree_5'
    ];

    public function index()
    {
        $soal4 = Soal4::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal4]);
    }

    public function store(Request $request)
    {
        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048'));

        $paths = [];
        foreach ($request->allFiles() as $field => $file) {
            $paths[$field] = $file->store('uploads/pdf', 'public');
        }

        $nilai = 0;
        if (!empty($paths['independent_org'])) $nilai += 8;
        if (!empty($paths['foreign_school_degree'])) $nilai += 7;
        foreach (['foreign_school_no_degree_1', 'foreign_school_no_degree_2', 'foreign_school_no_degree_3', 'foreign_school_no_degree_4', 'foreign_school_no_degree_5'] as $field) {
            if (!empty($paths[$field])) $nilai += 3;
        }
        foreach (['domestic_school_no_degree_1', 'domestic_school_no_degree_2', 'domestic_school_no_degree_3', 'domestic_school_no_degree_4', 'domestic_school_no_degree_5'] as $field) {
            if (!empty($paths[$field])) $nilai += 3;
        }

        $nilai = min($nilai, 30);

        $soal4 = Soal4::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal4
        ], 201);
    }

    public function update(Request $request)
    {
        $soal4 = Soal4::where('user_id', Auth::id())->first();
        if (!$soal4) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048'));

        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($soal4->$field && Storage::disk('public')->exists($soal4->$field)) {
                    Storage::disk('public')->delete($soal4->$field);
                }
                $soal4->$field = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $nilai = 0;
        if ($soal4->independent_org) $nilai += 8;
        if ($soal4->foreign_school_degree) $nilai += 7;
        foreach (['foreign_school_no_degree_1', 'foreign_school_no_degree_2', 'foreign_school_no_degree_3', 'foreign_school_no_degree_4', 'foreign_school_no_degree_5'] as $field) {
            if ($soal4->$field) $nilai += 3;
        }
        foreach (['domestic_school_no_degree_1', 'domestic_school_no_degree_2', 'domestic_school_no_degree_3', 'domestic_school_no_degree_4', 'domestic_school_no_degree_5'] as $field) {
            if ($soal4->$field) $nilai += 3;
        }

        $nilai = min($nilai, 30);
        $soal4->nilai = $nilai;
        $soal4->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal4
        ]);
    }

    public function destroy()
    {
        $soal4 = Soal4::where('user_id', Auth::id())->first();
        if (!$soal4) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach ($this->fileFields as $field) {
            if ($soal4->$field) {
                Storage::disk('public')->delete($soal4->$field);
            }
        }

        $soal4->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}