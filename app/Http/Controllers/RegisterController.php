<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('session.register');
    }

    // Di app/Http/Controllers/RegisterController.php
    public function store()
    {
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:5', 'max:20'],
            'agreement' => ['accepted']
        ]);

        // Tambahkan role default
        $attributes['password'] = bcrypt($attributes['password']);
        $attributes['role'] = 'user'; // Default role

        session()->flash('success', 'Akun berhasil dibuat.');
        $user = User::create($attributes);
        Auth::login($user);

        // Redirect ke halaman utama frontend
        return redirect('/');
    }
}
