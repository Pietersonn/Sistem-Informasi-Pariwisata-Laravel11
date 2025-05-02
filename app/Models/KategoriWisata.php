<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class KategoriWisata extends Model
{
    use HasFactory;

    protected $table = 'kategori_wisata';

    protected $fillable = [
        'nama', 
        'slug', 
        'deskripsi', 
        'ikon', 
        'urutan'
    ];

    // Relasi dengan Wisata (Many to Many)
    public function wisata()
    {
        return $this->belongsToMany(
            Wisata::class, 
            'wisata_kategori', 
            'id_kategori', 
            'id_wisata'
        );
    }

    // Generate slug otomatis
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Scope untuk kategori utama/teratas
    public function scopeUtama($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}