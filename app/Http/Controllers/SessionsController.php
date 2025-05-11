<?php
// app/Http/Controllers/SessionsController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SessionsController extends Controller
{
    public function create()
    {
        return view('session.login-session');
    }

    // Di app/Http/Controllers/SessionsController.php
    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        Log::info('Login attempt', [
            'email' => $attributes['email']
        ]);

        // Cek apakah user dengan email tersebut ada
        $user = User::where('email', $attributes['email'])->first();

        if (!$user) {
            Log::warning('User not found', ['email' => $attributes['email']]);
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        // Coba login dengan Auth facade
        if (Auth::attempt($attributes)) {
            session()->regenerate();

            Log::info('Login successful with Auth::attempt');

            // Redirect semua role ke halaman frontend/home
            return redirect('/');
        }

        // Jika gagal, coba dengan verifikasi manual
        if (Hash::check($attributes['password'], $user->password)) {
            // Hash cocok, login manual
            Auth::login($user);
            session()->regenerate();

            Log::info('Login successful with manual check');

            // Redirect semua role ke halaman frontend/home
            return redirect('/');
        }

        Log::warning('Login failed', [
            'email' => $attributes['email'],
            'password_match' => Hash::check($attributes['password'], $user->password),
            'stored_hash' => $user->password
        ]);

        return back()->withErrors(['email' => 'Email atau password tidak valid.']);
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('/login')->with(['success' => 'You\'ve been logged out.']);
    }
}
