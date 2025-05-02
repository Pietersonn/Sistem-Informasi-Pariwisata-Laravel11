<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GambarWisata extends Model
{
    use HasFactory;

    protected $table = 'gambar_wisata';

    protected $fillable = [
        'id_wisata', 
        'file_gambar', 
        'judul', 
        'deskripsi', 
        'urutan', 
        'is_utama'
    ];

    protected $casts = [
        'is_utama' => 'boolean',
        'urutan' => 'integer'
    ];

    // Relasi dengan Wisata
    public function wisata()
    {
        return $this->belongsTo(Wisata::class, 'id_wisata');
    }

    // Generate nama file unik
    public static function generateNamaFile(Wisata $wisata, $file)
    {
        $ekstensi = $file->getClientOriginalExtension();
        return 'wisata-' . 
               Str::slug($wisata->nama) . 
               '-' . 
               uniqid() . 
               '.' . 
               $ekstensi;
    }

    // Resize gambar menggunakan GD
    private static function resizeGambar($fileInput, $outputPath, $lebarMaksimal = 1200)
    {
        // Dapatkan informasi gambar
        list($lebarAwal, $tinggiAwal, $tipe) = getimagesize($fileInput);

        // Tentukan ukuran baru
        $rasio = $lebarMaksimal / $lebarAwal;
        $lebarBaru = $lebarMaksimal;
        $tinggiBaru = round($tinggiAwal * $rasio);

        // Buat gambar baru
        $gambarBaru = imagecreatetruecolor($lebarBaru, $tinggiBaru);

        // Pilih fungsi create berdasarkan tipe gambar
        switch ($tipe) {
            case IMAGETYPE_JPEG:
                $gambarAsli = imagecreatefromjpeg($fileInput);
                break;
            case IMAGETYPE_PNG:
                $gambarAsli = imagecreatefrompng($fileInput);
                break;
            case IMAGETYPE_WEBP:
                $gambarAsli = imagecreatefromwebp($fileInput);
                break;
            default:
                throw new \Exception('Tipe gambar tidak didukung');
        }

        // Resize gambar
        imagecopyresampled(
            $gambarBaru, $gambarAsli, 
            0, 0, 0, 0, 
            $lebarBaru, $tinggiBaru, 
            $lebarAwal, $tinggiAwal
        );

        // Simpan gambar
        switch ($tipe) {
            case IMAGETYPE_JPEG:
                imagejpeg($gambarBaru, $outputPath, 80);
                break;
            case IMAGETYPE_PNG:
                imagepng($gambarBaru, $outputPath, 8);
                break;
            case IMAGETYPE_WEBP:
                imagewebp($gambarBaru, $outputPath, 80);
                break;
        }

        // Bersihkan memori
        imagedestroy($gambarAsli);
        imagedestroy($gambarBaru);
    }

    // Proses dan simpan gambar
    public static function unggahGambar($file, Wisata $wisata, $isUtama = false)
    {
        // Validasi gambar
        if (!self::validasiGambar($file)) {
            throw new \Exception('Gambar tidak valid');
        }

        // Generate nama file
        $namaFile = self::generateNamaFile($wisata, $file);
        $path = 'public/wisata/' . $namaFile;
        $fullPath = storage_path('app/' . $path);

        // Resize dan simpan gambar
        self::resizeGambar($file->getRealPath(), $fullPath);

        // Buat record gambar
        return self::create([
            'id_wisata' => $wisata->id,
            'file_gambar' => str_replace('public/', '', $path),
            'is_utama' => $isUtama,
            'urutan' => self::where('id_wisata', $wisata->id)->count() + 1
        ]);
    }

    // Validasi gambar
    public static function validasiGambar($file)
    {
        $validExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        return $file->isValid() && 
               in_array(strtolower($file->getClientOriginalExtension()), $validExtensions) &&
               $file->getSize() <= 5 * 1024 * 1024; // Maks 5MB
    }

    // Accessor untuk URL gambar
    public function getUrlAttribute()
    {
        return $this->file_gambar 
            ? Storage::url($this->file_gambar) 
            : asset('images/placeholder-wisata.jpg');
    }

    // Scope untuk gambar utama
    public function scopeUtama($query)
    {
        return $query->where('is_utama', true);
    }

    // Hapus file dari storage
    public function hapusFile()
    {
        if ($this->file_gambar && Storage::exists('public/' . $this->file_gambar)) {
            Storage::delete('public/' . $this->file_gambar);
        }
    }

    // Override method delete
    public function delete()
    {
        $this->hapusFile();
        return parent::delete();
    }
}