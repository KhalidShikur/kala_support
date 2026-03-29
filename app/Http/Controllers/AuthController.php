<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'name'=>'required|string|min:3',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'=>$validated['name'],
            'email'=>$validated['email'],
            'password'=>Hash::make($validated['password']),
        ]);
        $token = $user->createToken('kala_support');

        return response()->json([
            'user'=>$user,
            'token'=>$token->plainTextToken,
        ]);
    }
}
