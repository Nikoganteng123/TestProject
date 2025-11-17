<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user() ?? Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json($this->formatProfileResponse($user));
    }

    public function update(Request $request)
    {
        $user = $request->user() ?? Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'NoHp' => 'sometimes|nullable|string|max:20',
            'pekerjaan' => 'sometimes|nullable|string|max:255',
            'tanggal_lahir' => 'sometimes|nullable|date',
            'domisili' => 'sometimes|nullable|string|max:255',
            'informasi_ipbi' => 'sometimes|nullable|string',
        ]);

        $user->fill($validated);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diupdate',
            'data' => $this->formatProfileResponse($user),
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

    private function formatProfileResponse($user): array
    {
        return [
            'name' => $user->name,
            'email' => $user->email,
            'NoHp' => $user->NoHp,
            'pekerjaan' => $user->pekerjaan,
            'tanggal_lahir' => $user->tanggal_lahir,
            'domisili' => $user->domisili,
            'informasi_ipbi' => $user->informasi_ipbi,
            'profile_picture' => $user->profile_picture,
            'profile_picture_url' => $user->profile_picture ? asset('storage/' . $user->profile_picture) : null,
            'nilai' => $user->nilai,
            'temporary_score' => $user->temporary_score,
            'is_verified' => $user->is_verified,
        ];
    }
}