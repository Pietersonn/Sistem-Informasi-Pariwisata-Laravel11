<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Konfigurasi extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi';

    protected $fillable = [
        'kunci', 
        'nilai', 
        'grup', 
        'deskripsi'
    ];

    // Scope untuk mencari konfigurasi berdasarkan kunci
    public function scopeCariKunci($query, $kunci)
    {
        return $query->where('kunci', $kunci);
    }

    // Scope untuk mencari konfigurasi berdasarkan grup
    public function scopeGrup($query, $grup)
    {
        return $query->where('grup', $grup);
    }

    // Getter untuk nilai konfigurasi
    public static function getNilai($kunci, $default = null)
    {
        $konfigurasi = self::where('kunci', $kunci)->first();
        return $konfigurasi ? $konfigurasi->nilai : $default;
    }

    // Setter untuk konfigurasi
    public static function setNilai($kunci, $nilai, $grup = null, $deskripsi = null)
    {
        $konfigurasi = self::firstOrNew(['kunci' => $kunci]);
        $konfigurasi->nilai = $nilai;
        
        if ($grup) {
            $konfigurasi->grup = $grup;
        }
        
        if ($deskripsi) {
            $konfigurasi->deskripsi = $deskripsi;
        }
        
        $konfigurasi->save();
        
        return $konfigurasi;
    }
}