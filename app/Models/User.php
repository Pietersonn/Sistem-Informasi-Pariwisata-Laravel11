<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'foto_profil',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Definisi konstanta role
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PEMILIK_WISATA = 'pemilik_wisata';
    public const ROLE_USER = 'user';

    // Relasi dengan Wisata (sebagai pemilik)
    public function wisata()
    {
        return $this->hasMany(Wisata::class, 'id_pemilik');
    }

    // Relasi dengan Ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_pengguna');
    }

    // Relasi dengan Favorit
    public function favorit()
    {
        return $this->hasMany(Favorit::class, 'id_pengguna');
    }

    public function setPasswordAttribute($value)
    {
        // Hanya hash password jika belum di-hash
        if ($value && !preg_match('/^\$2y\$/', $value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // Accessor untuk URL foto profil yang lebih robust
    public function getFotoProfilUrlAttribute()
    {
        // Jika foto profil tidak ada atau default
        if (empty($this->foto_profil) || $this->foto_profil == 'default.jpg') {
            return asset('assets/img/profil.jpg');
        }

        // Coba beberapa kemungkinan path
        $potentialPaths = [
            // 1. Path dengan public/ prefix (untuk file di public/uploads)
            $this->foto_profil,

            // 2. Path dengan storage/ prefix (untuk file yang melalui symbolic link)
            'storage/' . $this->foto_profil,

            // 3. Path langsung ke direktori profil
            'storage/profil/' . basename($this->foto_profil),

            // 4. Path ke uploads/profil (jika disimpan langsung ke public)
            'uploads/profil/' . basename($this->foto_profil)
        ];

        // Periksa jika file secara fisik ada di server
        foreach ($potentialPaths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // Log jika tidak menemukan file
        Log::warning("Foto profil tidak ditemukan untuk user {$this->id}: {$this->foto_profil}");

        // Default fallback
        return asset('assets/img/profil.jpg');
    }

    // Cek role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Scope untuk admin
    public function scopeAdmin($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    // Scope untuk pemilik wisata
    public function scopePemilikWisata($query)
    {
        return $query->where('role', self::ROLE_PEMILIK_WISATA);
    }

    // Scope untuk user biasa
    public function scopeUser($query)
    {
        return $query->where('role', self::ROLE_USER);
    }

    // Hitung jumlah wisata
    public function jumlahWisata()
    {
        return $this->wisata()->count();
    }
}
