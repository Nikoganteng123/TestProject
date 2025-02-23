<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal17;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal17Controller extends Controller
{
    public function index()
    {
        $soal17 = Soal17::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal17
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'media_cetak_nasional' => 'nullable|file|mimes:pdf|max:2048',
            'media_cetak_internasional' => 'nullable|file|mimes:pdf|max:2048',
            'buku_merangkai_bunga' => 'nullable|file|mimes:pdf|max:2048',
            'kontributor_buku1' => 'nullable|file|mimes:pdf|max:2048',
            'kontributor_buku2' => 'nullable|file|mimes:pdf|max:2048',
            'kontributor_tv1' => 'nullable|file|mimes:pdf|max:2048',
            'kontributor_tv2' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $paths = [];
        $fields = ['media_cetak_nasional', 'media_cetak_internasional', 'buku_merangkai_bunga', 'kontributor_buku1', 'kontributor_buku2', 'kontributor_tv1', 'kontributor_tv2'];

        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        // Score calculation
        $nilai = 0;
        
        // Media Cetak Nasional (5 poin)
        if (!empty($paths['media_cetak_nasional'])) {
            $nilai += 5;
        }

        // Media Cetak Internasional (10 poin)
        if (!empty($paths['media_cetak_internasional'])) {
            $nilai += 10;
        }

        // Buku Merangkai Bunga (20 poin)
        if (!empty($paths['buku_merangkai_bunga'])) {
            $nilai += 20;
        }

        // Kontributor Buku (10 poin each, maximum 20 for both)
        $bukuContributors = 0;
        if (!empty($paths['kontributor_buku1'])) {
            $bukuContributors += 1;
        }
        if (!empty($paths['kontributor_buku2'])) {
            $bukuContributors += 1;
        }
        $nilai += min($bukuContributors * 10, 20); // Cap at 20 points for both contributors

        // Kontributor TV (5 poin each, maximum 10 for both)
        $tvContributors = 0;
        if (!empty($paths['kontributor_tv1'])) {
            $tvContributors += 1;
        }
        if (!empty($paths['kontributor_tv2'])) {
            $tvContributors += 1;
        }
        $nilai += min($tvContributors * 5, 10); // Cap at 10 points for both contributors

        // Overall maximum of 45 points
        $nilai = min($nilai, 45);

        $soal17 = Soal17::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal17
        ], 201);
    }

    public function update(Request $request)
    {
        $soal17 = Soal17::where('user_id', Auth::id())->first();

        if (!$soal17) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $validatedData = $request->validate([
            'media_cetak_nasional' => 'nullable|file|mimes:pdf|max:2048',
            'media_cetak_internasional' => 'nullable|file|mimes:pdf|max:2048',
            'buku_merangkai_bunga' => 'nullable|file|mimes:pdf|max:2048',
            'kontributor_buku1' => 'nullable|file|mimes:pdf|max:2048',
            'kontributor_buku2' => 'nullable|file|mimes:pdf|max:2048',
            'kontributor_tv1' => 'nullable|file|mimes:pdf|max:2048',
            'kontributor_tv2' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $updatedData = [];

        foreach ($validatedData as $field => $file) {
            if ($request->hasFile($field)) {
                if ($soal17->$field) {
                    Storage::disk('public')->delete($soal17->$field);
                }
                $filePath = $request->file($field)->store('uploads/pdf', 'public');
                $updatedData[$field] = $filePath;
            }
        }

        if (!empty($updatedData)) {
            $soal17->update($updatedData);
        }

        // Score calculation
        $nilai = 0;
        
        // Media Cetak Nasional (5 poin)
        if ($soal17->media_cetak_nasional) {
            $nilai += 5;
        }

        // Media Cetak Internasional (10 poin)
        if ($soal17->media_cetak_internasional) {
            $nilai += 10;
        }

        // Buku Merangkai Bunga (20 poin)
        if ($soal17->buku_merangkai_bunga) {
            $nilai += 20;
        }

        // Kontributor Buku (10 poin each, maximum 20 for both)
        $bukuContributors = 0;
        if ($soal17->kontributor_buku1) {
            $bukuContributors += 1;
        }
        if ($soal17->kontributor_buku2) {
            $bukuContributors += 1;
        }
        $nilai += min($bukuContributors * 10, 20); // Cap at 20 points for both contributors

        // Kontributor TV (5 poin each, maximum 10 for both)
        $tvContributors = 0;
        if ($soal17->kontributor_tv1) {
            $tvContributors += 1;
        }
        if ($soal17->kontributor_tv2) {
            $tvContributors += 1;
        }
        $nilai += min($tvContributors * 5, 10); // Cap at 10 points for both contributors

        // Overall maximum of 45 points
        $nilai = min($nilai, 45);

        $soal17->update(['nilai' => $nilai]);

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal17
        ]);
    }

    public function destroy()
    {
        $soal17 = Soal17::where('user_id', Auth::id())->first();

        if (!$soal17) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $fields = ['media_cetak_nasional', 'media_cetak_internasional', 'buku_merangkai_bunga', 'kontributor_buku1', 'kontributor_buku2', 'kontributor_tv1', 'kontributor_tv2'];

        foreach ($fields as $field) {
            if ($soal17->$field) {
                Storage::disk('public')->delete($soal17->$field);
            }
        }

        $soal17->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}