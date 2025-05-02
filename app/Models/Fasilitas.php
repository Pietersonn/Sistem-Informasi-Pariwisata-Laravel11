<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    protected $fillable = [
        'nama', 
        'ikon', 
        'deskripsi'
    ];

    // Relasi dengan Wisata (Many to Many)
    public function wisata()
    {
        return $this->belongsToMany(
            Wisata::class, 
            'wisata_fasilitas', 
            'id_fasilitas', 
            'id_wisata'
        );
    }

    // Mutator untuk nama
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = trim($value);
        $this->attributes['ikon'] = $this->generateIkon($value);
    }

    // Generate ikon berdasarkan nama fasilitas
    protected function generateIkon($nama)
    {
        $namaSlug = Str::slug($nama);
        $ikonDefault = [
            'restoran' => 'restaurant-icon.svg',
            'parkir' => 'parking-icon.svg',
            'wifi' => 'wifi-icon.svg',
            'toilet' => 'toilet-icon.svg',
            'masjid' => 'mosque-icon.svg',
            // Tambahkan ikon default lainnya
        ];

        return $ikonDefault[$namaSlug] ?? 'default-facility-icon.svg';
    }

    // Scope untuk fasilitas utama
    public function scopeUtama($query)
    {
        $fasilitasUtama = [
            'restoran', 'parkir', 'wifi', 'toilet', 'masjid'
        ];
        return $query->whereIn('nama', $fasilitasUtama);
    }

    // Validasi nama fasilitas
    public function validasi()
    {
        return !empty($this->nama) && 
               strlen($this->nama) >= 2 && 
               strlen($this->nama) <= 50;
    }
}