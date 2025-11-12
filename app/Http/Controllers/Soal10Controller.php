<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal10;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal10Controller extends Controller
{
    private $fields = [
        'ipbi_offline' => ['count' => 3, 'points' => 5, 'max' => 15],
        'ipbi_online' => ['count' => 3, 'points' => 3, 'max' => 9],
        'non_ipbi_offline' => ['count' => 3, 'points' => 5, 'max' => 15],
        'non_ipbi_online' => ['count' => 3, 'points' => 3, 'max' => 9],
        'international_offline' => ['count' => 2, 'points' => 10, 'max' => 20],
        'international_online' => ['count' => 2, 'points' => 5, 'max' => 10],
        'host_moderator' => ['count' => 5, 'points' => 1, 'max' => 5]
    ];

    public function index()
    {
        $soal10 = Soal10::where('user_id', Auth::id())->first();
        return response()->json(['data' => $soal10]);
    }

    public function store(Request $request)
    {
        $validationRules = [];
        foreach ($this->fields as $field => $config) {
            for ($i = 1; $i <= $config['count']; $i++) {
                $validationRules["{$field}{$i}"] = 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048';
            }
        }
        $request->validate($validationRules);

        $paths = [];
        $nilai = 0;
        foreach ($this->fields as $field => $config) {
            $fieldNilai = 0;
            for ($i = 1; $i <= $config['count']; $i++) {
                $fieldName = "{$field}{$i}";
                if ($request->hasFile($fieldName)) {
                    $paths[$fieldName] = $request->file($fieldName)->store('uploads/pdf', 'public');
                    $fieldNilai += $config['points'];
                }
            }
            $nilai += min($fieldNilai, $config['max']);
        }

        $nilai = min($nilai, 40);

        $soal10 = Soal10::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal10
        ], 201);
    }

    public function update(Request $request)
    {
        $soal10 = Soal10::where('user_id', Auth::id())->first();
        if (!$soal10) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $validationRules = [];
        foreach ($this->fields as $field => $config) {
            for ($i = 1; $i <= $config['count']; $i++) {
                $validationRules["{$field}{$i}"] = 'nullable|file|mimes:pdf,png,jpg,jpeg|max:2048';
            }
        }
        $request->validate($validationRules);

        foreach ($this->fields as $field => $config) {
            for ($i = 1; $i <= $config['count']; $i++) {
                $fieldName = "{$field}{$i}";
                if ($request->hasFile($fieldName)) {
                    if ($soal10->$fieldName && Storage::disk('public')->exists($soal10->$fieldName)) {
                        Storage::disk('public')->delete($soal10->$fieldName);
                    }
                    $soal10->$fieldName = $request->file($fieldName)->store('uploads/pdf', 'public');
                }
            }
        }

        $nilai = 0;
        foreach ($this->fields as $field => $config) {
            $fieldNilai = 0;
            for ($i = 1; $i <= $config['count']; $i++) {
                $fieldName = "{$field}{$i}";
                if ($soal10->$fieldName) {
                    $fieldNilai += $config['points'];
                }
            }
            $nilai += min($fieldNilai, $config['max']);
        }

        $nilai = min($nilai, 40);
        $soal10->nilai = $nilai;
        $soal10->save();

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal10
        ]);
    }

    public function destroy()
    {
        $soal10 = Soal10::where('user_id', Auth::id())->first();
        if (!$soal10) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach ($this->fields as $field => $config) {
            for ($i = 1; $i <= $config['count']; $i++) {
                $fieldName = "{$field}{$i}";
                if ($soal10->$fieldName) {
                    Storage::disk('public')->delete($soal10->$fieldName);
                }
            }
        }

        $soal10->delete();
        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}