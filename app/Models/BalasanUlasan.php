<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BalasanUlasan extends Model
{
    use HasFactory;

    protected $table = 'balasan_ulasan';

    protected $fillable = [
        'id_ulasan', 
        'id_pengguna', 
        'balasan'
    ];

    // Relasi dengan Ulasan
    public function ulasan()
    {
        return $this->belongsTo(Ulasan::class, 'id_ulasan');
    }

    // Relasi dengan Pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    // Mutator untuk membersihkan balasan
    public function setBalasanAttribute($value)
    {
        $this->attributes['balasan'] = strip_tags(trim($value));
    }

    // Scope untuk balasan terbaru
    public function scopeTerbaru($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}