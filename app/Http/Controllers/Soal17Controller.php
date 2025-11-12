<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal17;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal17Controller extends Controller
{
    private $fileFields = [
        'media_cetak_nasional', 'media_cetak_internasional', 'buku_merangkai_bunga',
        'kontributor_buku1', 'kontributor_buku2', 'kontributor_tv1', 'kontributor_tv2'
    ];

    public function index()
    {
        $soal17 = Soal17::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal17]);
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
        if (!empty($paths['media_cetak_nasional'])) $nilai += 5;
        if (!empty($paths['media_cetak_internasional'])) $nilai += 10;
        if (!empty($paths['buku_merangkai_bunga'])) $nilai += 20;

        $bukuContributors = 0;
        if (!empty($paths['kontributor_buku1'])) $bukuContributors += 1;
        if (!empty($paths['kontributor_buku2'])) $bukuContributors += 1;
        $nilai += min($bukuContributors * 10, 20);

        $tvContributors = 0;
        if (!empty($paths['kontributor_tv1'])) $tvContributors += 1;
        if (!empty($paths['kontributor_tv2'])) $tvContributors += 1;
        $nilai += min($tvContributors * 5, 10);

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

        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048'));

        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($soal17->$field && Storage::disk('public')->exists($soal17->$field)) {
                    Storage::disk('public')->delete($soal17->$field);
                }
                $soal17->$field = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $nilai = 0;
        if ($soal17->media_cetak_nasional) $nilai += 5;
        if ($soal17->media_cetak_internasional) $nilai += 10;
        if ($soal17->buku_merangkai_bunga) $nilai += 20;

        $bukuContributors = 0;
        if ($soal17->kontributor_buku1) $bukuContributors += 1;
        if ($soal17->kontributor_buku2) $bukuContributors += 1;
        $nilai += min($bukuContributors * 10, 20);

        $tvContributors = 0;
        if ($soal17->kontributor_tv1) $tvContributors += 1;
        if ($soal17->kontributor_tv2) $tvContributors += 1;
        $nilai += min($tvContributors * 5, 10);

        $nilai = min($nilai, 45);
        $soal17->nilai = $nilai;
        $soal17->save();

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

        foreach ($this->fileFields as $field) {
            if ($soal17->$field) {
                Storage::disk('public')->delete($soal17->$field);
            }
        }

        $soal17->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}