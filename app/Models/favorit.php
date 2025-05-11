<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorit extends Model
{
    use HasFactory;

    protected $table = 'favorit';

    protected $fillable = [
        'id_wisata', 
        'id_pengguna', 
        'catatan'
    ];

    // Relasi dengan Wisata
    public function wisata()
    {
        return $this->belongsTo(Wisata::class, 'id_wisata');
    }

    // Relasi dengan Pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    // Mutator untuk membersihkan catatan
    public function setCatatanAttribute($value)
    {
        $this->attributes['catatan'] = strip_tags(trim($value));
    }

    // Scope untuk favorit pengguna tertentu
    public function scopePengguna($query, $penggunaId)
    {
        return $query->where('id_pengguna', $penggunaId);
    }
}