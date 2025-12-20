<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:128',
            'email'=> 'required|string|email|max:255|unique:user,email',
            'password'=> 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email'=> $validated['email'],
            'password'=> Hash::make($validated['password']),
        ]);
    

        return redirect('/register_success') ->with('success', 'Registrasi berhasil');
    }
}
