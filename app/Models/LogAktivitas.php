<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Request;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'id_pengguna', 
        'aktivitas', 
        'tipe', 
        'detail', 
        'ip_address', 
        'user_agent'
    ];

    // Relasi dengan Pengguna
    public function pengguna()
    {
        return $this->belongsTo(user::class, 'id_pengguna');
    }

    // Scope untuk tipe aktivitas tertentu
    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    // Scope untuk aktivitas pengguna tertentu
    public function scopePengguna($query, $penggunaId)
    {
        return $query->where('id_pengguna', $penggunaId);
    }

    // Method untuk mencatat aktivitas
    public static function catat($pengguna, $aktivitas, $tipe = null, $detail = null)
    {
        return self::create([
            'id_pengguna' => $pengguna ? $pengguna->id : null,
            'aktivitas' => $aktivitas,
            'tipe' => $tipe,
            'detail' => is_array($detail) ? json_encode($detail) : $detail,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent()
        ]);
    }

    // Getter untuk detail aktivitas
    public function getDetailAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) : $value;
    }

    // Scope untuk rentang waktu
    public function scopeRentangWaktu($query, $mulai, $selesai)
    {
        return $query->whereBetween('created_at', [$mulai, $selesai]);
    }

    // Method untuk membersihkan log lama
    public static function bersihkanLogLama($hariKebelakang = 30)
    {
        return self::where('created_at', '<', now()->subDays($hariKebelakang))->delete();
    }
}