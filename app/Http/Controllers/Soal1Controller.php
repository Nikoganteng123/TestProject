<?php

namespace App\Http\Controllers;

use App\Models\Soal1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Soal1Controller extends Controller
{
    // Menyimpan soal1 baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tingkat_pendidikan' => 'required|in:SMP-D3,S1,S2_or_above',
        ]);

        // Tentukan nilai otomatis berdasarkan tingkat pendidikan
        $nilai = $this->getNilaiByTingkatPendidikan($request->tingkat_pendidikan);

        // Mendapatkan user_id dari pengguna yang sedang login
        $user_id = Auth::id();  // Mendapatkan ID pengguna yang sedang login

        // Simpan data soal1 dengan nilai otomatis
        $soal1 = Soal1::create([
            'user_id' => $user_id,
            'tingkat_pendidikan' => $request->tingkat_pendidikan,
            'nilai' => $nilai,
        ]);

        // Kembalikan response JSON
        return response()->json($soal1, 201);
    }

    // Fungsi untuk menentukan nilai berdasarkan tingkat pendidikan
    private function getNilaiByTingkatPendidikan($tingkat_pendidikan)
    {
        $nilai = 0;

        switch ($tingkat_pendidikan) {
            case 'SMP-D3':
                $nilai = 2;  // Misalnya nilai untuk SMP-D3 adalah 2
                break;
            case 'S1':
                $nilai = 4;  // Nilai untuk S1 adalah 4
                break;
            case 'S2_or_above':
                $nilai = 6;  // Nilai untuk S2 atau lebih tinggi adalah 6
                break;
            default:
                $nilai = 0;
                break;
        }

        return $nilai;
    }



    // Menampilkan soal1 berdasarkan id
    public function show($id)
    {
        $soal1 = Soal1::find($id);

        if (!$soal1) {
            return response()->json(['message' => 'Soal1 not found'], 404);
        }

        return response()->json($soal1);
    }

    // Mengupdate soal1
    public function update(Request $request, $id)
    {
        $request->validate([
            'tingkat_pendidikan' => 'required|in:SMP,D3,S1,S2_or_above',
        ]);

        $soal1 = Soal1::find($id);

        if (!$soal1) {
            return response()->json(['message' => 'Soal1 not found'], 404);
        }

        $soal1->update([
            'tingkat_pendidikan' => $request->tingkat_pendidikan,
            'nilai' => $request->nilai,
        ]);

        return response()->json($soal1);
    }

    // Menghapus soal1
    public function destroy($id)
    {
        $soal1 = Soal1::find($id);

        if (!$soal1) {
            return response()->json(['message' => 'Soal1 not found'], 404);
        }

        $soal1->delete();

        return response()->json(['message' => 'Soal1 deleted successfully']);
    }
}
