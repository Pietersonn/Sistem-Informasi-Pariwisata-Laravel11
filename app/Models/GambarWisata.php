<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;
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
        'is_utama',
        'width',    // Tambahkan dimensi
        'height'    // Tambahkan dimensi
    ];

    protected $casts = [
        'is_utama' => 'boolean',
        'urutan' => 'integer',
        'width' => 'integer',
        'height' => 'integer'
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

    // Proses dan simpan gambar
    public static function unggahGambar($file, Wisata $wisata, $isUtama = false, $judul = null, $deskripsi = null)
    {
        // Validasi gambar
        if (!self::validasiGambar($file)) {
            throw new \Exception('Gambar tidak valid');
        }

        // Generate nama file
        $namaFile = self::generateNamaFile($wisata, $file);

        // Pastikan direktori ada
        $uploadPath = public_path('uploads/wisata');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0775, true);
        }

        // Pindahkan file
        $file->move($uploadPath, $namaFile);

        // Path lengkap dan relatif
        $fullPath = $uploadPath . '/' . $namaFile;
        $relativeFilePath = 'uploads/wisata/' . $namaFile;

        // Resize gambar jika diperlukan
        self::resizeImage($fullPath);

        // Dapatkan dimensi gambar
        $dimensions = getimagesize($fullPath);
        $width = $dimensions[0];
        $height = $dimensions[1];

        // Buat record gambar
        return self::create([
            'id_wisata' => $wisata->id,
            'file_gambar' => $relativeFilePath,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'is_utama' => $isUtama,
            'urutan' => self::where('id_wisata', $wisata->id)->count() + 1,
            'width' => $width,
            'height' => $height
        ]);
    }

    // Method untuk resize gambar
    private static function resizeImage($sourcePath, $maxWidth = 1200)
    {
        // Dapatkan ukuran gambar asli
        list($width, $height) = getimagesize($sourcePath);

        // Jika gambar sudah lebih kecil dari ukuran maksimum, tidak perlu resize
        if ($width <= $maxWidth) {
            return;
        }

        // Hitung ukuran baru dengan menjaga rasio aspek
        $ratio = $maxWidth / $width;
        $newWidth = $maxWidth;
        $newHeight = $height * $ratio;

        // Buat gambar baru
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Tentukan tipe gambar dan load gambar
        $pathInfo = pathinfo($sourcePath);
        $extension = strtolower($pathInfo['extension']);

        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $source = imagecreatefromjpeg($sourcePath);
                break;
            case 'png':
                $source = imagecreatefrompng($sourcePath);
                // Aktifkan alpha channel
                imagecolortransparent($newImage, imagecolorallocate($newImage, 0, 0, 0));
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                break;
            case 'gif':
                $source = imagecreatefromgif($sourcePath);
                break;
            case 'webp':
                $source = imagecreatefromwebp($sourcePath);
                break;
            default:
                return;
        }

        // Resize gambar
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Simpan gambar sesuai dengan formatnya
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($newImage, $sourcePath, 85); // 85% kualitas
                break;
            case 'png':
                imagepng($newImage, $sourcePath, 8); // Kompresi level 8
                break;
            case 'gif':
                imagegif($newImage, $sourcePath);
                break;
            case 'webp':
                imagewebp($newImage, $sourcePath, 85); // 85% kualitas
                break;
        }

        // Bersihkan memori
        imagedestroy($source);
        imagedestroy($newImage);
    }

    // Validasi gambar
    public static function validasiGambar($file)
    {
        $validExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        return $file->isValid() &&
            in_array(strtolower($file->getClientOriginalExtension()), $validExtensions) &&
            $file->getSize() <= 5 * 1024 * 1024; // Maks 5MB
    }

    // Accessor untuk URL gambar
    public function getUrlAttribute()
    {
        return $this->file_gambar
            ? asset($this->file_gambar)
            : asset('images/placeholder-wisata.jpg');
    }

    // Method untuk mengatur gambar utama
    public static function setAsUtama($id, $wisataId)
    {
        // Reset semua gambar utama untuk wisata ini
        self::where('id_wisata', $wisataId)
            ->where('is_utama', true)
            ->update(['is_utama' => false]);

        // Set gambar ini sebagai utama
        return self::where('id', $id)
            ->where('id_wisata', $wisataId)
            ->update(['is_utama' => true]);
    }

    // Method untuk mengubah urutan gambar
    public static function updateUrutan($wisataId, array $urutanData)
    {
        // Data berupa array [id_gambar => urutan]
        foreach ($urutanData as $id => $urutan) {
            self::where('id', $id)
                ->where('id_wisata', $wisataId)
                ->update(['urutan' => $urutan]);
        }
    }

    // Scope untuk gambar utama
    public function scopeUtama($query)
    {
        return $query->where('is_utama', true);
    }

    // Scope untuk gambar diurutkan
    public function scopeDiurutkan($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    // Hapus file dari storage
    public function hapusFile()
    {
        if ($this->file_gambar && File::exists(public_path($this->file_gambar))) {
            File::delete(public_path($this->file_gambar));
            return true;
        }
        return false;
    }

    // Override method delete
    public function delete()
    {
        $this->hapusFile();
        return parent::delete();
    }
}
