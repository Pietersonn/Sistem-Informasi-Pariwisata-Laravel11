<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\KategoriWisata;
use App\Models\EventWisata;

class HomeController extends Controller
{
    // Gunakan index() bukan home()
    public function index()
    {
        // Ambil wisata rekomendasi (berdasarkan rating tertinggi)
        $wisata_rekomendasi = Wisata::where('status', 'aktif')
            ->with(['kategori', 'gambarUtama'])
            ->select('id', 'nama', 'slug', 'alamat', 'deskripsi', 'harga_tiket', 'rata_rata_rating') // Pastikan kolom ini diambil
            ->orderBy('rata_rata_rating', 'desc')
            ->take(6)
            ->get();



        // Ambil semua kategori
        $kategori = KategoriWisata::orderBy('urutan')
            ->take(5)
            ->get();

        // Ambil event yang akan datang
        $event_mendatang = EventWisata::where('status', 'aktif')
            ->where('tanggal_mulai', '>', now())
            ->with('wisata')
            ->orderBy('tanggal_mulai')
            ->take(3)
            ->get();

        // Ambil wisata populer (berdasarkan jumlah dilihat)
        $wisata_populer = Wisata::where('status', 'aktif')
            ->with(['kategori', 'gambarUtama'])
            ->orderBy('jumlah_dilihat', 'desc')
            ->take(6)
            ->get();

        return view('frontend.home', compact(
            'wisata_rekomendasi',
            'kategori',
            'event_mendatang',
            'wisata_populer'
        ));
    }
}
