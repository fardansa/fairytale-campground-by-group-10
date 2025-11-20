<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // register
    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:6|confirmed' // expect password_confirmation
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // create token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // login -> returns token
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        // revoke previous tokens optionally:
        // $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token
        ]);
    }

    // logout (revoke tokens)
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['message' => 'Logout berhasil']);
    }

    // profile
    public function profile(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan'], 404);
    }

    return response()->json($user);
    }


    // update profile
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'nama' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:user,email,'.$user->user_id.',user_id',
            'password' => 'nullable|string|min:6|confirmed'
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json(['message' => 'Profil diperbarui', 'user' => $user]);
    }
}
