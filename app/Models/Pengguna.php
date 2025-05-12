<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama', 
        'email', 
        'kata_sandi', 
        'nomor_telepon', 
        'alamat', 
        'foto_profil', 
        'role', 
        'status'
    ];

    protected $hidden = [
        'kata_sandi', 
        'token_pengingat'
    ];

    protected $casts = [
        'email_terverifikasi_pada' => 'datetime',
        'terakhir_login' => 'datetime'
    ];

    // Definisi konstanta role
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PEMILIK_WISATA = 'pemilik_wisata';
    public const ROLE_USER = 'user';

    // Definisi konstanta status
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';
    public const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';

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

    // Relasi dengan Balasan Ulasan
    public function balasanUlasan()
    {
        return $this->hasMany(BalasanUlasan::class, 'id_pengguna');
    }

    // Relasi dengan Log Aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'id_pengguna');
    }

    // Mutator untuk enkripsi password
    public function setKataSandiAttribute($value)
    {
        $this->attributes['kata_sandi'] = Hash::make($value);
    }

    // Setter untuk foto profil
    public function setFotoProfilAttribute($value)
    {
        // Simpan path relatif
        $this->attributes['foto_profil'] = $value 
            ? Str::after($value, 'storage/') 
            : 'default.jpg';
    }

    // Accessor untuk URL foto profil
    
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

    // Metode untuk mengubah role
    public function ubahRole($role)
    {
        $rolesValid = [
            self::ROLE_ADMIN, 
            self::ROLE_PEMILIK_WISATA, 
            self::ROLE_USER
        ];

        if (!in_array($role, $rolesValid)) {
            throw new \InvalidArgumentException('Role tidak valid');
        }

        $this->role = $role;
        $this->save();
        return $this;
    }

    // Validasi status akun
    public function isAktif()
    {
        return $this->status === self::STATUS_AKTIF;
    }

    // Generate token reset password
    public function generateResetToken()
    {
        $token = Str::random(60);
        $this->token_pengingat = Hash::make($token);
        $this->save();
        return $token;
    }

    // Verifikasi email
    public function verifikasiEmail()
    {
        $this->email_terverifikasi_pada = Carbon::now();
        $this->status = self::STATUS_AKTIF;
        $this->save();
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

    // Hitung jumlah wisata
    public function jumlahWisata()
    {
        return $this->wisata()->count();
    }
}