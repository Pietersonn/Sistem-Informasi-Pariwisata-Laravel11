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

    // PERBAIKAN: Gunakan $casts instead of $dates
    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Hapus $dates karena sudah deprecated
    // protected $dates = [
    //     'tanggal_mulai', 
    //     'tanggal_selesai'
    // ];

    // Relasi dengan Wisata
    public function wisata()
    {
        return $this->belongsTo(Wisata::class, 'id_wisata');
    }

    // Scope untuk event yang sedang berlangsung
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

    // Accessor untuk status event
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'menunggu_persetujuan' => 'Menunggu Persetujuan',
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

    // Method untuk format tanggal yang lebih aman
    public function getTanggalMulaiFormatted($format = 'd M Y')
    {
        try {
            if ($this->tanggal_mulai instanceof Carbon) {
                return $this->tanggal_mulai->format($format);
            }
            
            // Jika masih string, convert ke Carbon dulu
            return Carbon::parse($this->tanggal_mulai)->format($format);
        } catch (\Exception $e) {
            return 'Tanggal tidak valid';
        }
    }

    public function getTanggalSelesaiFormatted($format = 'd M Y')
    {
        try {
            if ($this->tanggal_selesai instanceof Carbon) {
                return $this->tanggal_selesai->format($format);
            }
            
            // Jika masih string, convert ke Carbon dulu
            return Carbon::parse($this->tanggal_selesai)->format($format);
        } catch (\Exception $e) {
            return 'Tanggal tidak valid';
        }
    }
}