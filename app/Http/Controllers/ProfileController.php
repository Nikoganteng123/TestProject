<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Gunakan nilai final dari database jika sudah diverifikasi, jika tidak gunakan temporary_score
        $nilai = $user->is_verified ? $user->nilai : $user->temporary_score;

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'nilai' => $nilai,
            'is_verified' => $user->is_verified,
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
        ]);

        $user->update($validated);

        // Gunakan nilai final dari database jika sudah diverifikasi, jika tidak gunakan temporary_score
        $nilai = $user->is_verified ? $user->nilai : $user->temporary_score;

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'nilai' => $nilai,
            'is_verified' => $user->is_verified,
        ]);
    }

    public function submitCompetency(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'temporary_score' => 'required|integer', // Menerima totalNilai dari frontend
        ]);

        // Simpan nilai sementara dari frontend ke temporary_score
        $user->temporary_score = $validated['temporary_score'];
        $user->save();

        return response()->json([
            'message' => 'Nilai sementara berhasil disimpan',
            'temporary_score' => $user->temporary_score,
        ]);
    }

    public function destroy()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json(null, 204);
    }
}