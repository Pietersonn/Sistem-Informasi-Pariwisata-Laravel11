<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\EventWisata;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = EventWisata::where('status', 'aktif')
            ->with(['wisata' => function($q) {
                $q->select('id', 'nama', 'alamat', 'slug');
            }]);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', $searchTerm)
                    ->orWhere('deskripsi', 'like', $searchTerm)
                    ->orWhereHas('wisata', function ($wisata) use ($searchTerm) {
                        $wisata->where('nama', 'like', $searchTerm);
                    });
            });
        }

        // Filter berdasarkan bulan
        if ($request->filled('bulan')) {
            $bulan = $request->bulan;
            $query->whereMonth('tanggal_mulai', $bulan);
        }

        // Filter berdasarkan status waktu
        if ($request->filled('waktu')) {
            $now = Carbon::now();
            switch ($request->waktu) {
                case 'mendatang':
                    $query->where('tanggal_mulai', '>', $now);
                    break;
                case 'berlangsung':
                    $query->where('tanggal_mulai', '<=', $now)
                          ->where('tanggal_selesai', '>=', $now);
                    break;
                case 'selesai':
                    $query->where('tanggal_selesai', '<', $now);
                    break;
            }
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'terbaru':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'tanggal_terdekat':
                    $query->orderBy('tanggal_mulai', 'asc');
                    break;
                case 'nama':
                    $query->orderBy('nama', 'asc');
                    break;
                default:
                    $query->orderBy('tanggal_mulai', 'asc');
            }
        } else {
            $query->orderBy('tanggal_mulai', 'asc');
        }

        $events = $query->paginate(12)->withQueryString();

        // Statistik untuk sidebar
        $stats = [
            'total_event' => EventWisata::where('status', 'aktif')->count(),
            'event_mendatang' => EventWisata::where('status', 'aktif')
                ->where('tanggal_mulai', '>', Carbon::now())
                ->count(),
            'event_berlangsung' => EventWisata::where('status', 'aktif')
                ->where('tanggal_mulai', '<=', Carbon::now())
                ->where('tanggal_selesai', '>=', Carbon::now())
                ->count(),
        ];

        // Event populer (berdasarkan wisata dengan view terbanyak)
        $event_populer = EventWisata::where('status', 'aktif')
            ->where('tanggal_mulai', '>', Carbon::now())
            ->with(['wisata' => function($q) {
                $q->select('id', 'nama', 'alamat', 'slug', 'jumlah_dilihat');
            }])
            ->whereHas('wisata', function($q) {
                $q->orderBy('jumlah_dilihat', 'desc');
            })
            ->take(5)
            ->get();

        return view('frontend.event.index', compact('events', 'stats', 'event_populer'));
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        $event = EventWisata::where('id', $id)
            ->where('status', 'aktif')
            ->with(['wisata' => function($q) {
                $q->with(['kategori', 'gambarUtama']);
            }])
            ->firstOrFail();

        // Event lainnya di wisata yang sama
        $event_lainnya = EventWisata::where('id_wisata', $event->id_wisata)
            ->where('id', '!=', $event->id)
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '>', Carbon::now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();

        // Event serupa (di waktu yang berdekatan)
        $event_serupa = EventWisata::where('id', '!=', $event->id)
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '>=', $event->tanggal_mulai->subDays(7))
            ->where('tanggal_mulai', '<=', $event->tanggal_mulai->addDays(7))
            ->with(['wisata' => function($q) {
                $q->select('id', 'nama', 'alamat', 'slug');
            }])
            ->orderBy('tanggal_mulai', 'asc')
            ->take(4)
            ->get();

        return view('frontend.event.detail', compact('event', 'event_lainnya', 'event_serupa'));
    }

    /**
     * Get events by wisata for AJAX request
     */
    public function getEventsByWisata($wisataId)
    {
        $events = EventWisata::where('id_wisata', $wisataId)
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '>', Carbon::now())
            ->orderBy('tanggal_mulai', 'asc')
            ->get(['id', 'nama', 'tanggal_mulai', 'tanggal_selesai']);

        return response()->json($events);
    }
}