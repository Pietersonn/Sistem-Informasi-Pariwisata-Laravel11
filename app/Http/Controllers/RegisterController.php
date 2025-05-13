<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class RegisterController extends Controller
{
    public function create()
    {
        return view('session.register');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'password' => ['required', 'min:5', 'max:20'],
            'agreement' => ['accepted'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        // Create user with basic data
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password; // Will be hashed via mutator
        $user->role = 'user'; // Default role

        // Process profile photo if uploaded
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $fileName = 'profil_' . Str::slug($request->name) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Ensure the upload directory exists
            $uploadPath = public_path('uploads/profil');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0775, true);
            }

            // Move the file to the public directory
            $file->move($uploadPath, $fileName);

            // Save the path relative to public directory
            $user->foto_profil = 'uploads/profil/' . $fileName;
        }

        $user->save();

        // Login the user
        Auth::login($user);

        // Flash success message
        session()->flash('success', 'Account successfully created.');

        // Redirect to home page
        return redirect('/');
    }
}
