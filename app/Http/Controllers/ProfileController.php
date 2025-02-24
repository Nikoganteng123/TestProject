<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $overview = [];
        for ($i = 1; $i <= 17; $i++) {
            $model = "\\App\\Models\\Soal{$i}";
            $exists = class_exists($model) && $model::where('user_id', $user->id)->exists();
            $nilai = $exists ? $model::where('user_id', $user->id)->value('nilai') ?? 0 : 0;
            $overview["soal{$i}"] = $nilai;
        }

        $totalNilai = array_sum($overview);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $user->profile_picture,
            'nilai' => $totalNilai,
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
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/profile_pictures/' . basename($user->profile_picture));
            }
            $fileName = time() . '.' . $request->file('profile_picture')->extension();
            $request->file('profile_picture')->storeAs('public/profile_pictures', $fileName);
            $validated['profile_picture'] = $fileName;
        }

        $user->update($validated);

        $overview = [];
        for ($i = 1; $i <= 17; $i++) {
            $model = "\\App\\Models\\Soal{$i}";
            $exists = class_exists($model) && $model::where('user_id', $user->id)->exists();
            $nilai = $exists ? $model::where('user_id', $user->id)->value('nilai') ?? 0 : 0;
            $overview["soal{$i}"] = $nilai;
        }

        $totalNilai = array_sum($overview);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'profile_picture' => $user->profile_picture,
            'nilai' => $totalNilai,
        ]);
    }

    public function destroy()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if ($user->profile_picture) {
            Storage::delete('public/profile_pictures/' . basename($user->profile_picture));
        }

        $user->delete();

        return response()->json(null, 204);
    }
}