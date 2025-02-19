<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal2;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal2Controller extends Controller
{
    public function index()
    {
        $soal2 = Soal2::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal2
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tp3' => 'nullable|file|mimes:pdf|max:2048',
            'lpmp_diknas' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_1' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_2' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_3' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_4' => 'nullable|file|mimes:pdf|max:2048',
            'training_trainer' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        $paths = [];
        foreach (['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'] as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        // Hitung nilai otomatis
        $nilai = 0;
        if (!empty($paths['tp3'])) $nilai += 20;
        if (!empty($paths['lpmp_diknas'])) $nilai += 30;
        foreach (['guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4'] as $guru) {
            if (!empty($paths[$guru])) $nilai += 5;
        }
        if (!empty($paths['training_trainer'])) $nilai += 10;

        $soal2 = Soal2::create([
            'user_id' => Auth::id(),
            'tp3' => $paths['tp3'] ?? null,
            'lpmp_diknas' => $paths['lpmp_diknas'] ?? null,
            'guru_lain_ipbi_1' => $paths['guru_lain_ipbi_1'] ?? null,
            'guru_lain_ipbi_2' => $paths['guru_lain_ipbi_2'] ?? null,
            'guru_lain_ipbi_3' => $paths['guru_lain_ipbi_3'] ?? null,
            'guru_lain_ipbi_4' => $paths['guru_lain_ipbi_4'] ?? null,
            'training_trainer' => $paths['training_trainer'] ?? null,
            'nilai' => $nilai
        ]);

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal2
        ], 201);
    }

    public function update(Request $request)
    {
        $soal2 = Soal2::where('user_id', Auth::id())->first();
        
        if (!$soal2) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $request->validate([
            'tp3' => 'nullable|file|mimes:pdf|max:2048',
            'lpmp_diknas' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_1' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_2' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_3' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_4' => 'nullable|file|mimes:pdf|max:2048',
            'training_trainer' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        foreach (['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'] as $field) {
            if ($request->hasFile($field)) {
                if ($soal2->$field) {
                    Storage::disk('public')->delete($soal2->$field);
                }
                $soal2->$field = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        // Hitung nilai otomatis ulang
        $nilai = 0;
        if ($soal2->tp3) $nilai += 20;
        if ($soal2->lpmp_diknas) $nilai += 30;
        foreach (['guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4'] as $guru) {
            if ($soal2->$guru) $nilai += 5;
        }
        if ($soal2->training_trainer) $nilai += 10;

        $soal2->nilai = $nilai;
        $soal2->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal2
        ]);
    }

    public function destroy()
    {
        $soal2 = Soal2::where('user_id', Auth::id())->first();
        
        if (!$soal2) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach (['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'] as $field) {
            if ($soal2->$field) {
                Storage::disk('public')->delete($soal2->$field);
            }
        }

        $soal2->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}
