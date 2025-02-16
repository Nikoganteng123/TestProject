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
            'data' => $users
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }
    public function show(User $user)
    {
        return response()->json([
            'message' => 'Successfully fetched user',
            'data' =>
                $user
        ], 200);
    }
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable',
        ]);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->has('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }
    public function destroy(Request $request)
{
    $user = $request->user(); // Ambil pengguna yang sedang login
    $user->delete();

    return response()->json([
        'message' => 'User successfully deleted'
    ], 200);
}

}