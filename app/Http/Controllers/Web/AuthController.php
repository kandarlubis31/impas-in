<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'age' => 'required|integer|min:14',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'age' => $request->age,
                'balance' => 0,
                'reputation_score' => 0,
            ]);

            Auth::login($user);
            return redirect('/dashboard');
        } catch (ValidationException $e) {
            // biar Laravel handle redirect + errors per-field di view
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Web\AuthController@register: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan server. Coba lagi nanti.')->withInput();
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect('/dashboard');
            }

            return back()->withErrors(['email' => 'Email atau password salah nih.']);
        } catch (ValidationException $e) {
            // biar Laravel handle redirect + errors per-field di view
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Web\AuthController@login: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan server. Coba lagi nanti.')->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}