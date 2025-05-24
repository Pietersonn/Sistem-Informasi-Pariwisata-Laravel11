<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\User;
use App\Models\Ulasan;
use App\Models\EventWisata;

class DashboardController extends Controller
{
    public function index()
    {
        $statistik = [
            'total_wisata' => Wisata::count(),
            'total_pengguna' => User::count(),
            'total_ulasan' => Ulasan::count(),
            'total_event' => EventWisata::count(),
            
            'wisata_aktif' => Wisata::where('status', 'aktif')->count(),
            'pengguna_aktif' => User::where('status', 'aktif')->count(),
            'event_aktif' => EventWisata::where('status', 'aktif')->count(),
        ];

        // Data untuk grafik dan tabel
        $kunjunganWisata = Wisata::with(['kategori', 'gambar'])
            ->orderBy('jumlah_dilihat', 'desc')
            ->take(5)
            ->get();

        $ulasanTerbaru = Ulasan::with(['pengguna', 'wisata'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $eventMendatang = EventWisata::with(['wisata'])
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '>', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'statistik', 
            'kunjunganWisata', 
            'ulasanTerbaru', 
            'eventMendatang'
        ));
    }
}