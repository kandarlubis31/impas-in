<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8',
                'age' => 'required|integer|min:14', 
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'age' => $request->age,
                'balance' => 0,
                'reputation_score' => 0
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Registrasi berhasil',
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);
        } catch (\Throwable $e) {
            Log::error('AuthController@register: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Email atau password salah'
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'data' => $user
            ]);
        } catch (\Throwable $e) {
            Log::error('AuthController@login: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logout berhasil']);
        } catch (\Throwable $e) {
            Log::error('AuthController@logout: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan server. Coba lagi nanti.'], 500);
        }
    }
    
    public function me(Request $request)
    {
        return $request->user();
    }
}