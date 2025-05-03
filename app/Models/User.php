<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
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

    // Accessor untuk URL foto profil
    public function getFotoProfilUrlAttribute()
    {
        return $this->foto_profil && $this->foto_profil != 'default.jpg'
            ? asset('storage/' . $this->foto_profil) 
            : asset('assets/img/default-avatar.png');
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