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
            'wisata_aktif' => $query->where('status', 'aktif')->count(),
            'wisata_nonaktif' => $query->where('status', 'nonaktif')->count(),
            'wisata_menunggu' => $query->where('status', 'menunggu_persetujuan')->count(),
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

        // Statistik ulasan
        $statistik = [
            'total_ulasan' => $query->count(),
            'ulasan_ditampilkan' => $query->where('status', 'ditampilkan')->count(),
            'ulasan_disembunyikan' => $query->where('status', 'disembunyikan')->count(),
            'ulasan_menunggu' => $query->where('status', 'menunggu_moderasi')->count(),
        ];

        // Distribusi rating
        $distribusiRating = $query->select('rating', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('rating')
            ->orderBy('rating')
            ->get();

        // Ambil data ulasan
        $ulasan = $query->with('wisata', 'pengguna')
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
        // Filter untuk laporan event
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
            'event_aktif' => $query->where('status', 'aktif')->count(),
            'event_selesai' => $query->where('status', 'selesai')->count(),
            'event_dibatalkan' => $query->where('status', 'dibatalkan')->count(),
        ];

        // Ambil data event
        $event = $query->with('wisata')
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

    // Metode untuk ekspor laporan (sebagai contoh ke PDF atau Excel)
    public function eksporPdf(Request $request, $jenis)
    {
        // Implementasi ekspor PDF akan ditambahkan di masa mendatang
        // Anda bisa menggunakan library seperti FPDF atau TCPDF
    }
}