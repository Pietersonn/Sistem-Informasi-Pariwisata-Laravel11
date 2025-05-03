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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

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

        // Debug info untuk troubleshooting
        foreach ($pengguna as $user) {
            Log::debug("User ID: {$user->id}, Foto: {$user->foto_profil}, URL: {$user->foto_profil_url}");
        }

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

        // Proses upload foto profil - simpan langsung ke public
        if ($request->hasFile('foto_profil')) {
            try {
                $file = $request->file('foto_profil');
                $namaFile = 'profil_' . Str::slug($validasi['name']) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Pastikan direktori ada
                $uploadPath = public_path('uploads/profil');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true);
                }

                // Simpan file langsung ke public/uploads/profil
                $file->move($uploadPath, $namaFile);

                // Simpan path yang relatif terhadap public
                $user->foto_profil = 'uploads/profil/' . $namaFile;

                Log::info("Foto profil berhasil disimpan: {$user->foto_profil}");
            } catch (\Exception $e) {
                Log::error("Gagal menyimpan foto profil: " . $e->getMessage());
            }
        }

        $user->save();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan');
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

        // Proses upload foto profil baru
        if ($request->hasFile('foto_profil')) {
            try {
                // Hapus foto lama jika bukan default dan file ada
                if ($pengguna->foto_profil && $pengguna->foto_profil != 'default.jpg') {
                    // Coba beberapa kemungkinan path
                    $oldPaths = [
                        public_path($pengguna->foto_profil),
                        public_path('storage/' . $pengguna->foto_profil),
                        public_path('uploads/profil/' . basename($pengguna->foto_profil))
                    ];

                    foreach ($oldPaths as $path) {
                        if (file_exists($path)) {
                            unlink($path);
                            Log::info("Foto profil lama dihapus: {$path}");
                            break;
                        }
                    }
                }

                $file = $request->file('foto_profil');
                $namaFile = 'profil_' . Str::slug($validasi['name']) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Pastikan direktori ada
                $uploadPath = public_path('uploads/profil');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true);
                }

                // Simpan file langsung ke public/uploads/profil
                $file->move($uploadPath, $namaFile);

                // Simpan path yang relatif terhadap public
                $pengguna->foto_profil = 'uploads/profil/' . $namaFile;

                Log::info("Foto profil berhasil diperbarui: {$pengguna->foto_profil}");
            } catch (\Exception $e) {
                Log::error("Gagal memperbarui foto profil: " . $e->getMessage());
            }
        }

        $pengguna->save();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(User $pengguna)
    {
        try {
            // Hapus foto profil jika bukan default
            if ($pengguna->foto_profil && $pengguna->foto_profil != 'default.jpg') {
                // Coba beberapa kemungkinan path
                $paths = [
                    public_path($pengguna->foto_profil),
                    public_path('storage/' . $pengguna->foto_profil),
                    public_path('uploads/profil/' . basename($pengguna->foto_profil))
                ];

                foreach ($paths as $path) {
                    if (file_exists($path)) {
                        unlink($path);
                        Log::info("Foto profil dihapus: {$path}");
                        break;
                    }
                }
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
                        // Coba hapus file
                        $paths = [
                            public_path($gambar->file_gambar),
                            public_path('storage/' . $gambar->file_gambar),
                            storage_path('app/public/' . $gambar->file_gambar)
                        ];

                        foreach ($paths as $path) {
                            if (file_exists($path)) {
                                unlink($path);
                                break;
                            }
                        }

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

            Log::info("Pengguna berhasil dihapus: ID {$pengguna->id}, {$pengguna->name}");

            return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("Gagal menghapus pengguna: " . $e->getMessage());
            return redirect()->route('admin.pengguna.index')->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
