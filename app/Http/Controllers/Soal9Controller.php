<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal9;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal9Controller extends Controller
{
    private $fileFields = ['pembina_demonstrator', 'panitia', 'peserta'];

    public function index()
    {
        $soal9 = Soal9::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal9]);
    }

    public function store(Request $request)
    {
        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048'));

        $paths = [];
        $nilai = 0;

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

        $request->validate(array_fill_keys($this->fileFields, 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048'));

        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($soal9->$field && Storage::disk('public')->exists($soal9->$field)) {
                    Storage::disk('public')->delete($soal9->$field);
                }
                $soal9->$field = $request->file($field)->store('uploads/pdf', 'public');
            }
        }

        $nilai = 0;
        if ($soal9->pembina_demonstrator) $nilai += 15;
        if ($soal9->panitia) $nilai += 10;
        if ($soal9->peserta) $nilai += 5;

        $nilai = min($nilai, 15);
        $soal9->nilai = $nilai;
        $soal9->save();

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

        foreach ($this->fileFields as $field) {
            if ($soal9->$field) {
                Storage::disk('public')->delete($soal9->$field);
            }
        }

        $soal9->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}