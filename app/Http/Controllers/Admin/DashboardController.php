<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Pengguna;
use App\Models\Ulasan;
use App\Models\EventWisata;

class DashboardController extends Controller
{
    public function index()
    {
        $statistik = [
            'total_wisata' => Wisata::count(),
            'total_pengguna' => Pengguna::count(),
            'total_ulasan' => Ulasan::count(),
            'total_event' => EventWisata::count(),
            
            'wisata_aktif' => Wisata::where('status', 'aktif')->count(),
            'pengguna_aktif' => Pengguna::where('status', 'aktif')->count(),
            'event_aktif' => EventWisata::where('status', 'aktif')->count(),
        ];

        return view('admin.dashboard', compact('statistik'));
    }
}