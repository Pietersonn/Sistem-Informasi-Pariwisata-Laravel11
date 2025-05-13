<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Update data basic
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password jika ada
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Handle foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama
            if ($user->foto_profil && $user->foto_profil != 'default.jpg') {
                $oldPath = public_path($user->foto_profil);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Upload foto baru
            $file = $request->file('foto_profil');
            $fileName = 'profil_' . str_replace(' ', '-', strtolower($user->name)) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Simpan ke folder public/uploads/profil
            $destinationPath = public_path('uploads/profil');

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $fileName);

            // Simpan path relatif ke database
            $user->foto_profil = 'uploads/profil/' . $fileName;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
