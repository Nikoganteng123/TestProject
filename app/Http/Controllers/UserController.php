<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'message' => 'Hooray!! Successfully fetched users',
            'data' => $users->map(function ($user) {
                return $user->only([
                    'id', 'name', 'email', 'is_admin', 'email_verified_at', 'profile_picture', 'nilai',
                    'temporary_score', 'last_submission_date', 'is_verified', 'can_take_test', 'status',
                    'pekerjaan', 'tanggal_lahir', 'informasi_ipbi', 'domisili', 'created_at', 'updated_at'
                ]);
            })
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'is_admin' => 'nullable|boolean',
            'profile_picture' => 'nullable|string',
            'nilai' => 'nullable|integer',
            'temporary_score' => 'nullable|integer',
            'last_submission_date' => 'nullable|date',
            'is_verified' => 'nullable|boolean',
            'can_take_test' => 'nullable|boolean',
            'status' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'informasi_ipbi' => 'nullable|string',
            'domisili' => 'nullable|string',
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->is_admin = $request->input('is_admin', false);
        $user->profile_picture = $request->input('profile_picture');
        $user->nilai = $request->input('nilai');
        $user->temporary_score = $request->input('temporary_score');
        $user->last_submission_date = $request->input('last_submission_date');
        $user->is_verified = $request->input('is_verified', false);
        $user->can_take_test = $request->input('can_take_test', true);
        $user->status = $request->input('status');
        $user->pekerjaan = $request->input('pekerjaan');
        $user->tanggal_lahir = $request->input('tanggal_lahir');
        $user->informasi_ipbi = $request->input('informasi_ipbi');
        $user->domisili = $request->input('domisili');
        $user->save();

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user->only([
                'id', 'name', 'email', 'is_admin', 'email_verified_at', 'profile_picture', 'nilai',
                'temporary_score', 'last_submission_date', 'is_verified', 'can_take_test', 'status',
                'pekerjaan', 'tanggal_lahir', 'informasi_ipbi', 'domisili', 'created_at', 'updated_at'
            ]),
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'message' => 'Successfully fetched user',
            'data' => $user->only([
                'id', 'name', 'email', 'is_admin', 'email_verified_at', 'profile_picture', 'nilai',
                'temporary_score', 'last_submission_date', 'is_verified', 'can_take_test', 'status',
                'pekerjaan', 'tanggal_lahir', 'informasi_ipbi', 'domisili', 'created_at', 'updated_at'
            ])
        ], 200);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'is_admin' => 'nullable|boolean',
            'profile_picture' => 'nullable|string',
            'nilai' => 'nullable|integer',
            'temporary_score' => 'nullable|integer',
            'last_submission_date' => 'nullable|date',
            'is_verified' => 'nullable|boolean',
            'can_take_test' => 'nullable|boolean',
            'status' => 'nullable|string',
            'pekerjaan' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'informasi_ipbi' => 'nullable|string',
            'domisili' => 'nullable|string',
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->is_admin = $request->input('is_admin', $user->is_admin);
        $user->profile_picture = $request->input('profile_picture', $user->profile_picture);
        $user->nilai = $request->input('nilai', $user->nilai);
        $user->temporary_score = $request->input('temporary_score', $user->temporary_score);
        $user->last_submission_date = $request->input('last_submission_date', $user->last_submission_date);
        $user->is_verified = $request->input('is_verified', $user->is_verified);
        $user->can_take_test = $request->input('can_take_test', $user->can_take_test);
        $user->status = $request->input('status', $user->status);
        $user->pekerjaan = $request->input('pekerjaan', $user->pekerjaan);
        $user->tanggal_lahir = $request->input('tanggal_lahir', $user->tanggal_lahir);
        $user->informasi_ipbi = $request->input('informasi_ipbi', $user->informasi_ipbi);
        $user->domisili = $request->input('domisili', $user->domisili);
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user->only([
                'id', 'name', 'email', 'is_admin', 'email_verified_at', 'profile_picture', 'nilai',
                'temporary_score', 'last_submission_date', 'is_verified', 'can_take_test', 'status',
                'pekerjaan', 'tanggal_lahir', 'informasi_ipbi', 'domisili', 'created_at', 'updated_at'
            ]),
        ], 200);
    }

    public function destroy(Request $request)
    {
        $user = $request->user(); // Ambil pengguna yang sedang login
        $user->delete();

        return response()->json([
            'message' => 'User successfully deleted'
        ], 200);
    }
}