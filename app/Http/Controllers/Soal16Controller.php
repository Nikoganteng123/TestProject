<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal16;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal16Controller extends Controller
{
    private $fields = [
        'aktif_merangkai' => ['points' => 10],
        'owner_berbadan_hukum' => ['points' => 10],
        'owner_tanpa_badan_hukum' => ['points' => 5],
        'freelance_designer' => ['points' => 5]
    ];

    public function index()
    {
        $soal16 = Soal16::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal16
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

        // Maximum 20 points
        $nilai = min($nilai, 20);

        $soal16 = Soal16::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal16
        ], 201);
    }

    public function update(Request $request)
    {
        $soal16 = Soal16::where('user_id', Auth::id())->first();

        if (!$soal16) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $validationRules = [];
        foreach ($this->fields as $field => $config) {
            $validationRules[$field] = 'nullable|file|mimes:pdf|max:2048';
        }

        $request->validate($validationRules);

        $paths = [];
        $nilai = $soal16->nilai; // Preserve existing score

        foreach ($this->fields as $field => $config) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($soal16->$field) {
                    Storage::disk('public')->delete($soal16->$field);
                }
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
                $nilai += $config['points']; // Add points for new upload
            } elseif ($soal16->$field) {
                $paths[$field] = $soal16->$field; // Keep existing file path
            }
        }

        // Maximum 20 points
        $nilai = min($nilai, 20);

        $soal16->update(array_merge(
            ['nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil memperbarui file!',
            'data' => $soal16
        ]);
    }

    public function destroy()
    {
        $soal16 = Soal16::where('user_id', Auth::id())->first();

        if (!$soal16) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        // Delete all associated files
        foreach ($this->fields as $field => $config) {
            if ($soal16->$field) {
                Storage::disk('public')->delete($soal16->$field);
            }
        }

        // Delete the record
        $soal16->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus!'
        ], 200);
    }
}