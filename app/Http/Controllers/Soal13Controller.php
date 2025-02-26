<?php


// Controller
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Soal13;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Soal13Controller extends Controller
{
    private $fields = [
        'guru_tetap' => ['points' => 15],
        'asisten_guru' => ['points' => 8],
        'owner_sekolah' => ['points' => 8],
        'guru_tidak_tetap_offline' => ['points' => 10],
        'guru_tidak_tetap_online' => ['points' => 10],
        'guru_luar_negeri1' => ['points' => 10],
        'guru_luar_negeri2' => ['points' => 10]
    ];

    public function index()
    {
        $soal13 = Soal13::where('user_id', Auth::id())->first();
        return response()->json([
            'data' => $soal13
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

        // Khusus untuk guru luar negeri, max 20 poin
        $nilaiLuarNegeri = 0;
        if (!empty($paths['guru_luar_negeri1'])) $nilaiLuarNegeri += 10;
        if (!empty($paths['guru_luar_negeri2'])) $nilaiLuarNegeri += 10;
        $nilaiLuarNegeri = min($nilaiLuarNegeri, 20);

        // Hitung total nilai (max 40)
        $nilai = min($nilai - ($nilaiLuarNegeri/2) + $nilaiLuarNegeri, 40);

        $soal13 = Soal13::create(array_merge(
            ['user_id' => Auth::id(), 'nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengunggah file!',
            'data' => $soal13
        ], 201);
    }

    public function update(Request $request)
    {
        $soal13 = Soal13::where('user_id', Auth::id())->first();

        if (!$soal13) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        $validationRules = [];
        foreach ($this->fields as $field => $config) {
            $validationRules[$field] = 'nullable|file|mimes:pdf|max:2048';
        }

        $request->validate($validationRules);

        $paths = [];

        foreach ($this->fields as $field => $config) {
            if ($request->hasFile($field)) {
                if ($soal13->$field) {
                    Storage::disk('public')->delete($soal13->$field);
                }
                $paths[$field] = $request->file($field)->store('uploads/pdf', 'public');
            } elseif ($soal13->$field) {
                $paths[$field] = $soal13->$field;
            }
        }

        // Hitung nilai
        $nilai = 0;
        foreach ($this->fields as $field => $config) {
            if (isset($paths[$field])) {
                $nilai += $config['points'];
            }
        }

        // Khusus untuk guru luar negeri, max 20 poin
        $nilaiLuarNegeri = 0;
        if (isset($paths['guru_luar_negeri1'])) $nilaiLuarNegeri += 10;
        if (isset($paths['guru_luar_negeri2'])) $nilaiLuarNegeri += 10;
        $nilaiLuarNegeri = min($nilaiLuarNegeri, 20);

        // Hitung total nilai (max 40)
        $nilai = min($nilai - ($nilaiLuarNegeri/2) + $nilaiLuarNegeri, 40);

        $soal13->update(array_merge(
            ['nilai' => $nilai],
            $paths
        ));

        return response()->json([
            'message' => 'Berhasil mengupdate data!',
            'data' => $soal13
        ]);
    }

    public function destroy()
    {
        $soal13 = Soal13::where('user_id', Auth::id())->first();

        if (!$soal13) {
            return response()->json(['message' => 'Data tidak ditemukan!'], 404);
        }

        foreach ($this->fields as $field => $config) {
            if ($soal13->$field) {
                Storage::disk('public')->delete($soal13->$field);
            }
        }

        $soal13->delete();

        return response()->json(['message' => 'Berhasil menghapus data!']);
    }
}