<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wisata;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PenggunaController extends Controller
{
    // Definisi roles untuk dropdown
    private function getRoles()
    {
        return [
            'user' => 'Pengguna Biasa',
            'pemilik_wisata' => 'Pemilik Wisata',
            'admin' => 'Administrator'
        ];
    }

    public function index(Request $request)
    {
        // Query dasar untuk pengguna
        $query = User::query();

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Ambil data dengan pagination
        $pengguna = $query->paginate(10);

        // Kirim data ke view
        return view('admin.pengguna.index', [
            'pengguna' => $pengguna,
            'roles' => $this->getRoles()
        ]);
    }

    public function create()
    {
        return view('admin.pengguna.create', [
            'roles' => $this->getRoles()
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validasi = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:user,pemilik_wisata,admin',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Buat pengguna baru
        $user = new User();
        $user->fill([
            'name' => $validasi['name'],
            'email' => $validasi['email'],
            'password' => $validasi['password'], // Hash via mutator
            'role' => $validasi['role']
        ]);

        // Proses upload foto profil
        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $namaFile = 'profil_' . Str::slug($validasi['name']) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/profil', $namaFile);
            $user->foto_profil = str_replace('public/', '', $path);
        }

        $user->save();

        return redirect()->route('admin.pengguna.index');
    }

    public function show(User $pengguna)
    {
        // Muat relasi yang diperlukan
        $pengguna->load(['wisata', 'ulasan', 'favorit']);

        return view('admin.pengguna.show', compact('pengguna'));
    }

    public function edit(User $pengguna)
    {
        return view('admin.pengguna.edit', [
            'pengguna' => $pengguna,
            'roles' => $this->getRoles()
        ]);
    }

    public function update(Request $request, User $pengguna)
    {
        // Validasi input
        $validasi = $request->validate([
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($pengguna->id)
            ],
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|in:user,pemilik_wisata,admin',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Update data pengguna
        $pengguna->name = $validasi['name'];
        $pengguna->email = $validasi['email'];
        $pengguna->role = $validasi['role'];

        // Update password jika diisi
        if ($request->filled('password')) {
            $pengguna->password = $validasi['password'];
        }

        // Proses upload foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika bukan default
            if ($pengguna->foto_profil && $pengguna->foto_profil != 'default.jpg') {
                Storage::delete('public/' . $pengguna->foto_profil);
            }

            $file = $request->file('foto_profil');
            $namaFile = 'profil_' . Str::slug($validasi['name']) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/profil', $namaFile);
            $pengguna->foto_profil = str_replace('public/', '', $path);
        }

        $pengguna->save();

        return redirect()->route('admin.pengguna.index');
    }

    public function destroy(User $pengguna)
    {
        // Hapus foto profil jika bukan default
        if ($pengguna->foto_profil && $pengguna->foto_profil != 'default.jpg') {
            Storage::delete('public/' . $pengguna->foto_profil);
        }

        // Periksa dan hapus relasi
        if ($pengguna->ulasan()->count() > 0) {
            $pengguna->ulasan()->delete();
        }

        if ($pengguna->favorit()->count() > 0) {
            $pengguna->favorit()->delete();
        }

        if ($pengguna->wisata()->count() > 0) {
            // Untuk setiap wisata, tangani dependensi
            foreach ($pengguna->wisata as $wisata) {
                // Hapus relasi kategori
                $wisata->kategori()->detach();

                // Hapus gambar wisata
                foreach ($wisata->gambar as $gambar) {
                    Storage::delete('public/' . $gambar->file_gambar);
                    $gambar->delete();
                }

                // Hapus event wisata
                $wisata->event()->delete();

                // Hapus ulasan untuk wisata ini
                $wisata->ulasan()->delete();

                // Hapus favorit untuk wisata ini
                $wisata->favorit()->delete();
            }

            // Hapus wisata
            $pengguna->wisata()->delete();
        }

        // Hapus pengguna
        $pengguna->delete();

        return redirect()->route('admin.pengguna.index');
    }
}
