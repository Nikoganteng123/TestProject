<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal9;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal9Controller extends Controller
{
    public function index()
    {
        $soal9 = Soal9::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal9
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'pembina_demonstrator' => 'nullable|file|mimes:pdf|max:2048',
            'panitia' => 'nullable|file|mimes:pdf|max:2048',
            'peserta' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $paths = [];
        $nilai = 0;

        // Proses upload dan hitung nilai
        if ($request->hasFile('pembina_demonstrator')) {
            $paths['pembina_demonstrator'] = $request->file('pembina_demonstrator')->store('uploads/pdf', 'public');
            $nilai += 15;
        }
        if ($request->hasFile('panitia')) {
            $paths['panitia'] = $request->file('panitia')->store('uploads/pdf', 'public');
            $nilai += 10;
        }
        if ($request->hasFile('peserta')) {
            $paths['peserta'] = $request->file('peserta')->store('uploads/pdf', 'public');
            $nilai += 5;
        }

        // Maksimum 15 poin
        $nilai = min($nilai, 15);

        $soal9 = Soal9::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal9
        ], 201);
    }

    public function update(Request $request)
    {
        $soal9 = Soal9::where('user_id', Auth::id())->first();

        if (!$soal9) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $validatedData = $request->validate([
            'pembina_demonstrator' => 'nullable|file|mimes:pdf|max:2048',
            'panitia' => 'nullable|file|mimes:pdf|max:2048',
            'peserta' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $paths = [];
        $nilai = 0;

        // Update files and calculate new score
        foreach ($validatedData as $field => $value) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($soal9->$field) {
                    Storage::disk('public')->delete($soal9->$field);
                }
                
                // Store new file
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            } else {
                // Keep existing file and add to score if exists
                if ($soal9->$field) {
                    $paths[$field] = $soal9->$field;
                }
            }
        }

        // Calculate nilai
        if (isset($paths['pembina_demonstrator'])) $nilai += 15;
        if (isset($paths['panitia'])) $nilai += 10;
        if (isset($paths['peserta'])) $nilai += 5;

        // Maksimum 15 poin
        $nilai = min($nilai, 15);

        $soal9->update(array_merge(
            ['nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal9
        ]);
    }

    public function destroy()
    {
        $soal9 = Soal9::where('user_id', Auth::id())->first();

        if (!$soal9) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        // Delete all files
        $fields = ['pembina_demonstrator', 'panitia', 'peserta'];
        foreach ($fields as $field) {
            if ($soal9->$field) {
                Storage::disk('public')->delete($soal9->$field);
            }
        }

        $soal9->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}
