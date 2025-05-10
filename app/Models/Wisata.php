<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisata';

    protected $fillable = [
        'nama', 
        'slug', 
        'id_pemilik', 
        'alamat', 
        'link_gmaps', 
        'deskripsi', 
        'jam_buka', 
        'jam_tutup', 
        'hari_operasional', 
        'harga_tiket', 
        'kontak', 
        'email', 
        'website', 
        'instagram', 
        'facebook', 
        'twitter', 
        'fasilitas', 
        'status'
    ];

    // Konstanta status wisata
    public const STATUS_AKTIF = 'aktif';
    public const STATUS_NONAKTIF = 'nonaktif';
    public const STATUS_MENUNGGU_PERSETUJUAN = 'menunggu_persetujuan';

    protected $casts = [
        'hari_operasional' => 'array',
        'fasilitas' => 'array',
        'harga_tiket' => 'float',
        'rata_rata_rating' => 'float',
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i'
    ];

    // Relasi dengan Pemilik (Pengguna)
    public function pemilik()
    {
        return $this->belongsTo(pengguna::class, 'id_pemilik');
    }

    // Relasi dengan Kategori (Many to Many)
    public function kategori()
    {
        return $this->belongsToMany(
            KategoriWisata::class, 
            'wisata_kategori', 
            'id_wisata', 
            'id_kategori'
        );
    }

    // Relasi dengan Fasilitas (Many to Many)
    public function fasilitas()
    {
        return $this->belongsToMany(
            Fasilitas::class, 
            'wisata_fasilitas', 
            'id_wisata', 
            'id_fasilitas'
        );
    }

    // Relasi dengan Gambar
    public function gambar()
    {
        return $this->hasMany(GambarWisata::class, 'id_wisata');
    }

    // Gambar utama
    public function gambarUtama()
    {
        return $this->hasOne(GambarWisata::class, 'id_wisata')
            ->where('is_utama', true);
    }

    // Relasi dengan Ulasan
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_wisata');
    }

    // Relasi dengan Event
    public function event()
    {
        return $this->hasMany(EventWisata::class, 'id_wisata');
    }

    // Relasi dengan Favorit
    public function favorit()
    {
        return $this->hasMany(Favorit::class, 'id_wisata');
    }

    // Mutator untuk nama (generate slug otomatis)
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Scope untuk wisata aktif
    public function scopeAktif($query)
    {
        return $query->where('status', self::STATUS_AKTIF);
    }

    // Scope untuk wisata berdasarkan kategori
    public function scopeKategori($query, $kategoriId)
    {
        return $query->whereHas('kategori', function($q) use ($kategoriId) {
            $q->where('kategori_wisata.id', $kategoriId);
        });
    }

    // Hitung rata-rata rating
    public function hitungRataRating()
    {
        $rataRating = $this->ulasan()->avg('rating') ?? 0;
        
        // Update rata-rata rating di database
        $this->update(['rata_rata_rating' => round($rataRating, 2)]);
        
        return $rataRating;
    }

    // Cek apakah wisata buka sekarang
    public function sedangBuka()
    {
        if (!$this->jam_buka || !$this->jam_tutup) {
            return false;
        }

        $hariIni = strtolower(Carbon::now()->translatedFormat('l'));
        $hariOperasional = $this->hari_operasional ?? [];

        // Cek apakah hari ini termasuk hari operasional
        if (!in_array($hariIni, $hariOperasional)) {
            return false;
        }

        $jamSekarang = Carbon::now();
        $jamBuka = Carbon::createFromTimeString($this->jam_buka);
        $jamTutup = Carbon::createFromTimeString($this->jam_tutup);

        return $jamSekarang->between($jamBuka, $jamTutup);
    }

    // Ambil URL media sosial
    public function getMediaSosial()
    {
        return [
            'instagram' => $this->instagram 
                ? "https://instagram.com/{$this->instagram}" 
                : null,
            'facebook' => $this->facebook ?? null,
            'twitter' => $this->twitter 
                ? "https://twitter.com/{$this->twitter}" 
                : null,
            'website' => $this->website
        ];
    }

    // Accessor untuk URL Google Maps
    public function getLinkGmapsAttribute($value)
    {
        return $value ? 
            (strpos($value, 'http') === 0 ? $value : "https://www.google.com/maps/search/?api=1&query=" . urlencode($this->alamat)) 
            : null;
    }

    // Validasi data wisata
    public function validasi()
    {
        return !empty($this->nama) && 
               !empty($this->alamat) && 
               !empty($this->deskripsi);
    }

    // Tambah view
    public function tambahView()
    {
        $this->increment('jumlah_dilihat');
        return $this;
    }

    // Cek event aktif
    public function eventAktif()
    {
        return $this->event()
            ->where('status', 'aktif')
            ->where('tanggal_selesai', '>=', Carbon::now())
            ->get();
    }

    // Hitung jumlah ulasan
    public function jumlahUlasan()
    {
        return $this->ulasan()->count();
    }
}