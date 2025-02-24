<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal8;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal8Controller extends Controller
{
    public function index()
    {
        $soal8 = Soal8::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal8
        ]);
    }

    public function store(Request $request)
    {
        $fields = [
            'demo_dpp_dpd1', 'demo_dpp_dpd2', 'demo_dpp_dpd3', 'demo_dpp_dpd4', 'demo_dpp_dpd5',
            'non_ipbi1', 'non_ipbi2', 'non_ipbi3', 'non_ipbi4', 'non_ipbi5',
            'international1', 'international2'
        ];

        $validationRules = [];
        foreach ($fields as $field) {
            $validationRules[$field] = 'nullable|file|mimes:pdf|max:2048';
        }

        $request->validate($validationRules);

        $paths = [];
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        // Hitung nilai
        $nilai = 0;
        
        // Demo DPP/DPD/DPC IPBI (2 poin per file)
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($paths["demo_dpp_dpd$i"])) {
                $nilai += 2;
            }
        }
        
        // Acara diluar IPBI (1 poin per file)
        for ($i = 1; $i <= 5; $i++) {
            if (!empty($paths["non_ipbi$i"])) {
                $nilai += 1;
            }
        }
        
        // Acara internasional (2 poin per file)
        for ($i = 1; $i <= 2; $i++) {
            if (!empty($paths["international$i"])) {
                $nilai += 2;
            }
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

        $fields = [
            'demo_dpp_dpd1', 'demo_dpp_dpd2', 'demo_dpp_dpd3', 'demo_dpp_dpd4', 'demo_dpp_dpd5',
            'non_ipbi1', 'non_ipbi2', 'non_ipbi3', 'non_ipbi4', 'non_ipbi5',
            'international1', 'international2'
        ];

        $validationRules = [];
        foreach ($fields as $field) {
            $validationRules[$field] = 'nullable|file|mimes:pdf|max:2048';
        }

        $validatedData = $request->validate($validationRules);

        $updatedData = [];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                if ($soal8->$field) {
                    Storage::disk('public')->delete($soal8->$field);
                }
                $filePath = $request->file($field)->store('uploads/pdf', 'public');
                $updatedData[$field] = $filePath;
            }
        }

        if (!empty($updatedData)) {
            $soal8->update($updatedData);
        }

        // Hitung ulang nilai
        $nilai = 0;
        
        // Demo DPP/DPD/DPC IPBI (2 poin per file)
        for ($i = 1; $i <= 5; $i++) {
            if ($soal8->{"demo_dpp_dpd$i"}) {
                $nilai += 2;
            }
        }
        
        // Acara diluar IPBI (1 poin per file)
        for ($i = 1; $i <= 5; $i++) {
            if ($soal8->{"non_ipbi$i"}) {
                $nilai += 1;
            }
        }
        
        // Acara internasional (2 poin per file)
        for ($i = 1; $i <= 2; $i++) {
            if ($soal8->{"international$i"}) {
                $nilai += 2;
            }
        }
        
        $nilai = min($nilai, 15);

        $soal8->update(['nilai' => $nilai]);

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

        $fields = [
            'demo_dpp_dpd1', 'demo_dpp_dpd2', 'demo_dpp_dpd3', 'demo_dpp_dpd4', 'demo_dpp_dpd5',
            'non_ipbi1', 'non_ipbi2', 'non_ipbi3', 'non_ipbi4', 'non_ipbi5',
            'international1', 'international2'
        ];

        foreach ($fields as $field) {
            if ($soal8->$field) {
                Storage::disk('public')->delete($soal8->$field);
            }
        }

        $soal8->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}