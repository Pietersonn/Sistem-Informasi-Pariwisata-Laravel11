<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Ulasan extends Model
{
    use HasFactory;

    protected $table = 'ulasan';

    protected $fillable = [
        'id_wisata', 
        'id_pengguna', 
        'rating', 
        'komentar', 
        'status', 
        'tanggal_kunjungan'
    ];

    protected $dates = [
        'tanggal_kunjungan'
    ];

    // Relasi dengan Wisata
    public function wisata()
    {
        return $this->belongsTo(Wisata::class, 'id_wisata');
    }

    // Relasi dengan Pengguna
    public function pengguna()
    {
        return $this->belongsTo(user::class, 'id_pengguna');
    }

    // Relasi dengan Balasan Ulasan
    public function balasan()
    {
        return $this->hasMany(BalasanUlasan::class, 'id_ulasan');
    }

    // Scope untuk ulasan yang ditampilkan
    public function scopeDitampilkan($query)
    {
        return $query->where('status', 'ditampilkan');
    }

    // Scope untuk ulasan dengan rating tertentu
    public function scopeRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'ditampilkan' => 'Ditampilkan',
            'disembunyikan' => 'Disembunyikan',
            'menunggu_moderasi' => 'Menunggu Moderasi',
            default => 'Tidak Dikenal'
        };
    }

    // Mutator untuk membersihkan komentar
    public function setKomentarAttribute($value)
    {
        $this->attributes['komentar'] = strip_tags(trim($value));
    }
}