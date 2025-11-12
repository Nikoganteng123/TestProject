<?php

namespace App\Http\Controllers;

use App\Models\Soal1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Soal1Controller extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tingkat_pendidikan' => 'required|in:SMP-D3,S1,S2_atau_lebih',
            'tingkat_pendidikan_file' => 'required|file|mimes:pdf,png,jpg,jpeg|max:2048', // File PDF, PNG, atau JPG untuk bukti
        ]);

        $user_id = Auth::id();

        // Simpan file PDF
        $filePath = $request->file('tingkat_pendidikan_file')->store('uploads/pdf', 'public');

        // Hitung nilai berdasarkan tingkat pendidikan
        $nilai = $this->getNilaiByTingkatPendidikan($request->tingkat_pendidikan);
        $nilai = min($nilai, 5);

        // Simpan atau update data
        $soal1 = Soal1::updateOrCreate(
            ['user_id' => $user_id],
            [
                'tingkat_pendidikan' => $request->tingkat_pendidikan,
                'tingkat_pendidikan_file' => $filePath, // Simpan path file
                'nilai' => $nilai,
            ]
        );

        return response()->json($soal1, 201);
    }

    public function show()
    {
        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();

        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($soal1);
    }

    public function update(Request $request)
    {
        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();

        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Validasi hanya untuk field yang ada di request
        $request->validate([
            'tingkat_pendidikan' => 'sometimes|required|in:SMP-D3,S1,S2_atau_lebih',
            'tingkat_pendidikan_file' => 'sometimes|required|file|mimes:pdf,png,jpg,jpeg|max:2048',
        ]);

        // Jika ada file baru, hapus file lama dan simpan yang baru
        if ($request->hasFile('tingkat_pendidikan_file')) {
            if ($soal1->tingkat_pendidikan_file && Storage::disk('public')->exists($soal1->tingkat_pendidikan_file)) {
                Storage::disk('public')->delete($soal1->tingkat_pendidikan_file);
            }
            $soal1->tingkat_pendidikan_file = $request->file('tingkat_pendidikan_file')->store('uploads/pdf', 'public');
        }

        // Update tingkat pendidikan jika ada di request
        if ($request->has('tingkat_pendidikan')) {
            $soal1->tingkat_pendidikan = $request->tingkat_pendidikan;
        }

        // Hitung ulang nilai berdasarkan tingkat pendidikan
        $nilai = $this->getNilaiByTingkatPendidikan($soal1->tingkat_pendidikan);
        $soal1->nilai = min($nilai, 5);
        $soal1->save();

        return response()->json($soal1);
    }

    public function destroy()
    {
        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();

        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Hapus file dari storage jika ada
        if ($soal1->tingkat_pendidikan_file && Storage::disk('public')->exists($soal1->tingkat_pendidikan_file)) {
            Storage::disk('public')->delete($soal1->tingkat_pendidikan_file);
        }

        $soal1->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    private function getNilaiByTingkatPendidikan($tingkat_pendidikan)
    {
        $nilai = 0;
        switch ($tingkat_pendidikan) {
            case 'SMP-D3':
                $nilai = 2;
                break;
            case 'S1':
                $nilai = 4;
                break;
            case 'S2_atau_lebih':
                $nilai = 5;
                break;
        }
        return $nilai;
    }
}