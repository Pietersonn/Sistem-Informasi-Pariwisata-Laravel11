<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\EventWisata;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function wisata(Request $request)
    {
        // Filter untuk laporan wisata
        $query = Wisata::query();

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($request->tanggal_selesai)->endOfDay();
            $query->whereBetween('created_at', [$mulai, $selesai]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Statistik wisata
        $statistik = [
            'total_wisata' => $query->count(),
            'wisata_aktif' => (clone $query)->where('status', 'aktif')->count(),
            'wisata_nonaktif' => (clone $query)->where('status', 'nonaktif')->count(),
            'wisata_menunggu' => (clone $query)->where('status', 'menunggu_persetujuan')->count(),
        ];

        // Ambil data wisata
        $wisata = $query->with('kategori', 'pemilik')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Top wisata berdasarkan rating
        $topWisata = Wisata::orderBy('rata_rata_rating', 'desc')
            ->take(5)
            ->get();

        return view('admin.laporan.wisata', [
            'wisata' => $wisata,
            'statistik' => $statistik,
            'topWisata' => $topWisata,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'statusTerpilih' => $request->status
        ]);
    }

    public function ulasan(Request $request)
    {
        // Filter untuk laporan ulasan
        $query = Ulasan::query();

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($request->tanggal_selesai)->endOfDay();
            $query->whereBetween('created_at', [$mulai, $selesai]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Statistik ulasan - hitung dulu sebelum pagination
        $totalQuery = clone $query;
        $statistik = [
            'total_ulasan' => $totalQuery->count(),
            'ulasan_ditampilkan' => (clone $totalQuery)->where('status', 'ditampilkan')->count(),
            'ulasan_disembunyikan' => (clone $totalQuery)->where('status', 'disembunyikan')->count(),
            'ulasan_menunggu' => (clone $totalQuery)->where('status', 'menunggu_moderasi')->count(),
        ];

        // Distribusi rating
        $distribusiRating = (clone $totalQuery)->select('rating', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        // Ambil data ulasan dengan relasi dan filter null
        $ulasan = $query->with(['wisata', 'pengguna'])
            ->whereHas('wisata') // Pastikan ada wisata
            ->whereHas('pengguna') // Pastikan ada pengguna
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.laporan.ulasan', [
            'ulasan' => $ulasan,
            'statistik' => $statistik,
            'distribusiRating' => $distribusiRating,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'statusTerpilih' => $request->status
        ]);
    }

    public function kunjungan(Request $request)
    {
        // Filter untuk laporan kunjungan
        $query = Wisata::query();

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($request->tanggal_selesai)->endOfDay();
            // Asumsi: Kita bisa menambahkan log kunjungan di masa mendatang
            // Untuk saat ini, gunakan jumlah dilihat
        }

        // Top 10 wisata paling banyak dilihat
        $topKunjungan = $query->orderBy('jumlah_dilihat', 'desc')
            ->take(10)
            ->get();

        // Statistik kunjungan
        $statistik = [
            'total_kunjungan' => $query->sum('jumlah_dilihat'),
            'rata_rata_kunjungan' => $query->avg('jumlah_dilihat'),
            'wisata_terbanyak_dilihat' => $topKunjungan->first()->nama ?? 'Tidak ada data'
        ];

        return view('admin.laporan.kunjungan', [
            'topKunjungan' => $topKunjungan,
            'statistik' => $statistik,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai
        ]);
    }

    public function event(Request $request)
    {
        // Filter untuk laporan event - ambil semua termasuk yang wisatanya null
        $query = EventWisata::query();

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($request->tanggal_selesai)->endOfDay();
            $query->whereBetween('tanggal_mulai', [$mulai, $selesai]);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Statistik event
        $statistik = [
            'total_event' => $query->count(),
            'event_aktif' => (clone $query)->where('status', 'aktif')->count(),
            'event_selesai' => (clone $query)->where('status', 'selesai')->count(),
            'event_dibatalkan' => (clone $query)->where('status', 'dibatalkan')->count(),
        ];

        // Ambil data event dengan left join untuk menangani wisata yang null
        $event = $query->with(['wisata' => function ($q) {
            $q->select('id', 'nama', 'alamat', 'status');
        }])
            ->orderBy('tanggal_mulai', 'desc')
            ->paginate(10);

        return view('admin.laporan.event', [
            'event' => $event,
            'statistik' => $statistik,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'statusTerpilih' => $request->status
        ]);
    }

    // Method untuk ekspor laporan ke PDF
    public function eksporPdf(Request $request, $jenis)
    {
        switch ($jenis) {
            case 'wisata':
                return $this->eksporWisataPdf($request);
            case 'ulasan':
                return $this->eksporUlasanPdf($request);
            case 'event':
                return $this->eksporEventPdf($request);
            case 'kunjungan':
                return $this->eksporKunjunganPdf($request);
            default:
                return abort(404);
        }
    }

    private function eksporWisataPdf(Request $request)
    {
        $query = Wisata::query();

        // Terapkan filter yang sama
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($request->tanggal_selesai)->endOfDay();
            $query->whereBetween('created_at', [$mulai, $selesai]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $wisata = $query->with('kategori', 'pemilik')->get();

        $statistik = [
            'total_wisata' => $wisata->count(),
            'wisata_aktif' => $wisata->where('status', 'aktif')->count(),
            'wisata_nonaktif' => $wisata->where('status', 'nonaktif')->count(),
            'wisata_menunggu' => $wisata->where('status', 'menunggu_persetujuan')->count(),
        ];

        $pdf = PDF::loadView('admin.laporan.pdf.wisata', [
            'wisata' => $wisata,
            'statistik' => $statistik,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'statusTerpilih' => $request->status
        ]);

        return $pdf->download('laporan-wisata-' . date('Y-m-d') . '.pdf');
    }

    private function eksporUlasanPdf(Request $request)
    {
        $query = Ulasan::query();

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($request->tanggal_selesai)->endOfDay();
            $query->whereBetween('created_at', [$mulai, $selesai]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $ulasan = $query->with('wisata', 'pengguna')->get();

        $statistik = [
            'total_ulasan' => $ulasan->count(),
            'ulasan_ditampilkan' => $ulasan->where('status', 'ditampilkan')->count(),
            'ulasan_disembunyikan' => $ulasan->where('status', 'disembunyikan')->count(),
            'ulasan_menunggu' => $ulasan->where('status', 'menunggu_moderasi')->count(),
        ];

        $pdf = PDF::loadView('admin.laporan.pdf.ulasan', [
            'ulasan' => $ulasan,
            'statistik' => $statistik,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'statusTerpilih' => $request->status
        ]);

        return $pdf->download('laporan-ulasan-' . date('Y-m-d') . '.pdf');
    }

    private function eksporEventPdf(Request $request)
    {
        $query = EventWisata::whereHas('wisata'); // Hanya ambil event yang punya wisata

        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $mulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
            $selesai = Carbon::parse($request->tanggal_selesai)->endOfDay();
            $query->whereBetween('tanggal_mulai', [$mulai, $selesai]);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $event = $query->with(['wisata' => function ($q) {
            $q->select('id', 'nama', 'alamat');
        }])->get();

        $statistik = [
            'total_event' => $event->count(),
            'event_aktif' => $event->where('status', 'aktif')->count(),
            'event_selesai' => $event->where('status', 'selesai')->count(),
            'event_dibatalkan' => $event->where('status', 'dibatalkan')->count(),
        ];

        $pdf = PDF::loadView('admin.laporan.pdf.event', [
            'event' => $event,
            'statistik' => $statistik,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai,
            'statusTerpilih' => $request->status
        ]);

        return $pdf->download('laporan-event-' . date('Y-m-d') . '.pdf');
    }

    private function eksporKunjunganPdf(Request $request)
    {
        $query = Wisata::query();
        $topKunjungan = $query->orderBy('jumlah_dilihat', 'desc')->take(10)->get();

        $statistik = [
            'total_kunjungan' => $query->sum('jumlah_dilihat'),
            'rata_rata_kunjungan' => $query->avg('jumlah_dilihat'),
            'wisata_terbanyak_dilihat' => $topKunjungan->first()->nama ?? 'Tidak ada data'
        ];

        $pdf = PDF::loadView('admin.laporan.pdf.kunjungan', [
            'topKunjungan' => $topKunjungan,
            'statistik' => $statistik,
            'tanggalMulai' => $request->tanggal_mulai,
            'tanggalSelesai' => $request->tanggal_selesai
        ]);

        return $pdf->download('laporan-kunjungan-' . date('Y-m-d') . '.pdf');
    }
}
