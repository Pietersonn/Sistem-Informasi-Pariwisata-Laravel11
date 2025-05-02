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

    protected $dates = [
        'tanggal_mulai', 
        'tanggal_selesai'
    ];

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
            default => 'Tidak Dikenal'
        };
    }

    // Cek apakah event sudah selesai
    public function getSelesaiAttribute()
    {
        return $this->tanggal_selesai < Carbon::now();
    }
}