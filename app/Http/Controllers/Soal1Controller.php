<?php

namespace App\Http\Controllers;

use App\Models\Soal1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Soal1Controller extends Controller
{
    // 🔹 Menyimpan atau memperbarui data soal1 berdasarkan user yang login
    public function store(Request $request)
    {
        $request->validate([
            'tingkat_pendidikan' => 'required|in:SMP-D3,S1,S2_atau_lebih',
        ]);

        $user_id = Auth::id();
        $nilai = $this->getNilaiByTingkatPendidikan($request->tingkat_pendidikan);
        
        $nilai = min($nilai, 5);
        // Simpan atau update data berdasarkan user_id
        $soal1 = Soal1::updateOrCreate(
            ['user_id' => $user_id], 
            [
                'tingkat_pendidikan' => $request->tingkat_pendidikan,
                'nilai' => $nilai,
            ]
        );

        return response()->json($soal1, 201);
    }

    // 🔹 Mengambil data soal1 berdasarkan user yang login
    public function show()
    {
        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();

        
        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($soal1);
    }

    // 🔹 Memperbarui data soal1 berdasarkan user yang login
    public function update(Request $request)
    {
        $request->validate([
            'tingkat_pendidikan' => 'required|in:SMP-D3,S1,S2_atau_lebih',
        ]);

        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();

        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        

        $nilai = $this->getNilaiByTingkatPendidikan($request->tingkat_pendidikan);
        
        $nilai = min($nilai, 5);
        $soal1->update([
            'tingkat_pendidikan' => $request->tingkat_pendidikan,
            'nilai' => $nilai,
        ]);

        return response()->json($soal1);
    }

    // 🔹 Menghapus data soal1 berdasarkan user yang login
    public function destroy()
    {
        $user_id = Auth::id();
        $soal1 = Soal1::where('user_id', $user_id)->first();

        if (!$soal1) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $soal1->delete();

        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    // 🔹 Fungsi untuk menentukan nilai berdasarkan tingkat pendidikan
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
