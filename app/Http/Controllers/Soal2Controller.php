<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal2;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal2Controller extends Controller
{
    // Menampilkan data soal2 untuk user yang sedang login
    public function index()
    {
        $soal2 = Soal2::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal2
        ]);
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        // Validasi file PDF
        $request->validate([
            'tp3' => 'nullable|file|mimes:pdf|max:2048',
            'lpmp_diknas' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_1' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_2' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_3' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_4' => 'nullable|file|mimes:pdf|max:2048',
            'training_trainer' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Simpan file ke storage
        $paths = [];
        foreach (['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'] as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        // Simpan ke database dengan user_id dari auth
        $soal2 = Soal2::create([
            'user_id' => Auth::id(),
            'tp3' => $paths['tp3'] ?? null,
            'lpmp_diknas' => $paths['lpmp_diknas'] ?? null,
            'guru_lain_ipbi_1' => $paths['guru_lain_ipbi_1'] ?? null,
            'guru_lain_ipbi_2' => $paths['guru_lain_ipbi_2'] ?? null,
            'guru_lain_ipbi_3' => $paths['guru_lain_ipbi_3'] ?? null,
            'guru_lain_ipbi_4' => $paths['guru_lain_ipbi_4'] ?? null,
            'training_trainer' => $paths['training_trainer'] ?? null,
        ]);

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal2
        ], 201);
    }

    // Mengupdate data yang ada
    public function update(Request $request)
    {
        $soal2 = Soal2::where('user_id', Auth::id())->first();
        
        if (!$soal2) {
            return response()->json([
                'message' => 'Data tidak ditemukan!'
            ], 404);
        }

        // Validasi file PDF
        $request->validate([
            'tp3' => 'nullable|file|mimes:pdf|max:2048',
            'lpmp_diknas' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_1' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_2' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_3' => 'nullable|file|mimes:pdf|max:2048',
            'guru_lain_ipbi_4' => 'nullable|file|mimes:pdf|max:2048',
            'training_trainer' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Update file jika ada
        foreach (['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'] as $field) {
            if ($request->hasFile($field)) {
                // Hapus file lama jika ada
                if ($soal2->$field) {
                    Storage::disk('public')->delete($soal2->$field);
                }
                // Simpan file baru
                $soal2->$field = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $soal2->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal2
        ]);
    }

    // Menghapus data
    public function destroy()
    {
        $soal2 = Soal2::where('user_id', Auth::id())->first();
        
        if (!$soal2) {
            return response()->json([
                'message' => 'Data tidak ditemukan!'
            ], 404);
        }

        // Hapus semua file terkait
        foreach (['tp3', 'lpmp_diknas', 'guru_lain_ipbi_1', 'guru_lain_ipbi_2', 'guru_lain_ipbi_3', 'guru_lain_ipbi_4', 'training_trainer'] as $field) {
            if ($soal2->$field) {
                Storage::disk('public')->delete($soal2->$field);
            }
        }

        $soal2->delete();

        return response()->json([
            'message' => 'Berhasil menghapus data!'
        ]);
    }

    // Download file
    public function download($field)
    {
        $soal2 = Soal2::where('user_id', Auth::id())->first();
        
        if (!$soal2 || !$soal2->$field) {
            return response()->json([
                'message' => 'File tidak ditemukan!'
            ], 404);
        }

        return Storage::disk('public')->download($soal2->$field);
    }
}