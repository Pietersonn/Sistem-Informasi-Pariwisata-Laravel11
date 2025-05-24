<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class EventWisata extends Model
{
    use HasFactory;

    protected $table = 'event_wisata';

    protected $fillable = [
        'id_wisata',
        'nama',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'poster',
        'status'
    ];

    // Cast untuk datetime
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi dengan Wisata - dengan handling NULL
    public function wisata()
    {
        return $this->belongsTo(Wisata::class, 'id_wisata');
    }

    // Scope untuk event yang berlangsung
    public function scopeBerlangsung($query)
    {
        $now = Carbon::now();
        return $query->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now);
    }

    // Scope untuk event mendatang
    public function scopeMendatang($query)
    {
        $now = Carbon::now();
        return $query->where('status', 'aktif')
            ->where('tanggal_mulai', '>', $now);
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => 'Tidak Dikenal'
        };
    }

    // Cek apakah event sudah selesai
    public function getSelesaiAttribute()
    {
        return $this->tanggal_selesai < Carbon::now();
    }

    // Method untuk mengecek status event secara dinamis
    public function getStatusDinamis()
    {
        $now = Carbon::now();

        if ($this->status !== 'aktif') {
            return $this->status;
        }

        if ($now < $this->tanggal_mulai) {
            return 'mendatang';
        } elseif ($now >= $this->tanggal_mulai && $now <= $this->tanggal_selesai) {
            return 'berlangsung';
        } else {
            return 'selesai';
        }
    }

    // Method untuk format tanggal yang aman
    public function getTanggalMulaiFormatted($format = 'd M Y')
    {
        try {
            return $this->tanggal_mulai ? $this->tanggal_mulai->format($format) : 'Tanggal tidak tersedia';
        } catch (\Exception $e) {
            return 'Tanggal tidak valid';
        }
    }

    public function getTanggalSelesaiFormatted($format = 'd M Y')
    {
        try {
            return $this->tanggal_selesai ? $this->tanggal_selesai->format($format) : 'Tanggal tidak tersedia';
        } catch (\Exception $e) {
            return 'Tanggal tidak valid';
        }
    }

    // Accessor untuk nama wisata yang aman
    public function getNamaWisataAttribute()
    {
        return $this->wisata ? $this->wisata->nama : 'Wisata Tidak Ditemukan';
    }

    // Accessor untuk URL poster yang aman
    public function getPosterUrlAttribute()
    {
        if ($this->poster && file_exists(public_path($this->poster))) {
            return asset($this->poster);
        }
        return null;
    }
}
