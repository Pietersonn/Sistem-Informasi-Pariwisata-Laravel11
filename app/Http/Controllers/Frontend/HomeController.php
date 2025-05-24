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
        // Ambil wisata rekomendasi (berdasarkan rating tertinggi)
        $wisata_rekomendasi = Wisata::where('status', 'aktif')
            ->with(['kategori', 'gambarUtama'])
            ->select('id', 'nama', 'slug', 'alamat', 'deskripsi', 'harga_tiket', 'rata_rata_rating')
            ->orderBy('rata_rata_rating', 'desc')
            ->take(6)
            ->get();

        // Ambil semua kategori
        $kategori = KategoriWisata::orderBy('urutan')
            ->take(5)
            ->get();

        // Ambil event yang akan datang (status aktif dan tanggal mulai > hari ini)
        // PERBAIKAN: Tambahkan debugging dan pastikan data ter-load
        $event_mendatang = EventWisata::where('status', 'aktif')
            ->where('tanggal_mulai', '>', Carbon::now())
            ->with(['wisata' => function($query) {
                $query->select('id', 'nama', 'alamat', 'slug');
            }])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(6)
            ->get();

        // Debugging: Log jumlah event yang ditemukan
        Log::info('Event mendatang ditemukan: ' . $event_mendatang->count());

        // Jika tidak ada event mendatang, coba ambil semua event aktif
        if ($event_mendatang->isEmpty()) {
            $event_mendatang = EventWisata::where('status', 'aktif')
                ->with(['wisata' => function($query) {
                    $query->select('id', 'nama', 'alamat', 'slug');
                }])
                ->orderBy('tanggal_mulai', 'desc')
                ->take(6)
                ->get();
            
            Log::info('Total event aktif: ' . $event_mendatang->count());
        }

        // Ambil wisata populer (berdasarkan jumlah dilihat)
        $wisata_populer = Wisata::where('status', 'aktif')
            ->with(['kategori', 'gambarUtama', 'ulasan'])
            ->orderBy('jumlah_dilihat', 'desc')
            ->take(6)
            ->get();

        // Debug: Log semua data yang akan dikirim ke view
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