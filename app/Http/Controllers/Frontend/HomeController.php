<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\KategoriWisata;
use App\Models\EventWisata;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $wisata_rekomendasi = Wisata::where('status', 'aktif')
            ->with(['kategori', 'gambarUtama'])
            ->select('id', 'nama', 'slug', 'alamat', 'deskripsi', 'harga_tiket', 'rata_rata_rating')
            ->orderBy('rata_rata_rating', 'desc')
            ->take(6)
            ->get();

        $kategori = KategoriWisata::orderBy('urutan')
            ->take(5)
            ->get();


        $event_mendatang = EventWisata::where('status', 'aktif')
            ->with(['wisata' => function($query) {
                $query->select('id', 'nama', 'alamat', 'slug');
            }])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(6) // Batasi 6 untuk home page
            ->get();

        Log::info('Event aktif ditemukan: ' . $event_mendatang->count());

        $wisata_populer = Wisata::where('status', 'aktif')
            ->with(['kategori', 'gambarUtama', 'ulasan'])
            ->orderBy('jumlah_dilihat', 'desc')
            ->take(6)
            ->get();

        Log::info('Data untuk home page:', [
            'wisata_rekomendasi_count' => $wisata_rekomendasi->count(),
            'kategori_count' => $kategori->count(),
            'event_mendatang_count' => $event_mendatang->count(),
            'wisata_populer_count' => $wisata_populer->count()
        ]);

        return view('frontend.home', compact(
            'wisata_rekomendasi',
            'kategori',
            'event_mendatang',
            'wisata_populer'
        ));
    }
}