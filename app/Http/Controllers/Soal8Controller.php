<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal8;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal8Controller extends Controller
{
    private $fileFields = [
        'demo_dpp_dpd1', 'demo_dpp_dpd2', 'demo_dpp_dpd3', 'demo_dpp_dpd4', 'demo_dpp_dpd5',
        'non_ipbi1', 'non_ipbi2', 'non_ipbi3', 'non_ipbi4', 'non_ipbi5',
        'international1', 'international2'
    ];

    public function index()
    {
        $soal8 = Soal8::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal8]);
    }

    public function store(Request $request)
    {
        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048'));

        $paths = [];
        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $nilai = 0;
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($paths["demo_dpp_dpd$i"])) $nilai += 2;
        }
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($paths["non_ipbi$i"])) $nilai += 1;
        }
        for ($i = 1; $i <= 2; $i++) {
            if (!empty($paths["international$i"])) $nilai += 2;
        }

        $nilai = min($nilai, 15);

        $soal8 = Soal8::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal8
        ], 201);
    }

    public function update(Request $request)
    {
        $soal8 = Soal8::where('user_id', Auth::id())->first();
        if (!$soal8) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048'));

        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($soal8->$field && Storage::disk('public')->exists($soal8->$field)) {
                    Storage::disk('public')->delete($soal8->$field);
                }
                $soal8->$field = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $nilai = 0;
        for ($i = 1; $i <= 5; $i++) {
            if ($soal8->{"demo_dpp_dpd$i"}) $nilai += 2;
        }
        for ($i = 1; $i <= 5; $i++) {
            if ($soal8->{"non_ipbi$i"}) $nilai += 1;
        }
        for ($i = 1; $i <= 2; $i++) {
            if ($soal8->{"international$i"}) $nilai += 2;
        }

        $nilai = min($nilai, 15);
        $soal8->nilai = $nilai;
        $soal8->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal8
        ]);
    }

    public function destroy()
    {
        $soal8 = Soal8::where('user_id', Auth::id())->first();
        if (!$soal8) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach ($this->fileFields as $field) {
            if ($soal8->$field) {
                Storage::disk('public')->delete($soal8->$field);
            }
        }

        $soal8->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}