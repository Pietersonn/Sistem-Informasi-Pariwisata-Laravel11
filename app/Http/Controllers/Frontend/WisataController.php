<?php
// File: App\Http\Controllers\Frontend\WisataController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Ulasan;
use App\Models\Favorit;
use App\Models\KategoriWisata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        // Inisialisasi query dasar
        $query = Wisata::where('status', 'aktif')
            ->with(['kategori', 'gambarUtama']);

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('kategori_wisata.id', $request->kategori);
            });
        }

        // Filter berdasarkan pencarian
        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', $searchTerm)
                    ->orWhere('alamat', 'like', $searchTerm)
                    ->orWhere('deskripsi', 'like', $searchTerm);
            });
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'terbaru':
                    $query->latest();
                    break;
                case 'terpopuler':
                    $query->orderBy('jumlah_dilihat', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('rata_rata_rating', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            // Default sorting jika tidak ada sort parameter
            $query->latest();
        }

        // Pagination dengan mempertahankan query parameters
        $wisata = $query->paginate(9)->withQueryString();

        // Ambil semua kategori untuk filter
        $kategori = KategoriWisata::all();

        return view('frontend.wisata.index', compact('wisata', 'kategori'));
    }

    public function show($slug)
    {
        $wisata = Wisata::where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        // Tambahkan jumlah view
        $wisata->increment('jumlah_dilihat');

        $sudahDifavorit = false;
        if (Auth::check()) {
            $sudahDifavorit = Favorit::where('id_wisata', $wisata->id)
                ->where('id_pengguna', Auth::id())
                ->exists();
        }

        // Ambil ulasan dengan balasan
        $ulasan = Ulasan::where('id_wisata', $wisata->id)
            ->where('status', 'ditampilkan')
            ->with([
                'pengguna',
                'balasan' => function ($query) use ($wisata) {
                    // Hanya balasan dari pemilik wisata
                    $query->where('id_pengguna', $wisata->id_pemilik)
                        ->with('pengguna');
                }
            ])
            ->latest()
            ->take(5)
            ->get();

        // Deteksi jika request AJAX/XHR, kirimkan modal saja
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') == 'XMLHttpRequest') {
            return view('frontend.wisata.detail-modal', compact(
                'wisata',
                'ulasan',
                'sudahDifavorit'
            ));
        }

        // Jika bukan AJAX, tampilkan halaman normal dengan layout
        return view('frontend.wisata.detail', compact(
            'wisata',
            'ulasan',
            'sudahDifavorit'
        ));
    }
    // Menambahkan ke favorit
    public function addToFavorite($slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wisata = Wisata::where('slug', $slug)->firstOrFail();

        // Cek jika sudah difavoritkan
        $favorit = Favorit::where('id_wisata', $wisata->id)
            ->where('id_pengguna', Auth::id())
            ->first();

        if (!$favorit) {
            Favorit::create([
                'id_wisata' => $wisata->id,
                'id_pengguna' => Auth::id(),
                'catatan' => null
            ]);

            return back()->with('success', 'Wisata berhasil ditambahkan ke favorit');
        }

        return back()->with('info', 'Wisata sudah ada di daftar favorit');
    }

    // Menghapus dari favorit
    public function removeFromFavorite($slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wisata = Wisata::where('slug', $slug)->firstOrFail();

        Favorit::where('id_wisata', $wisata->id)
            ->where('id_pengguna', Auth::id())
            ->delete();

        return back()->with('success', 'Wisata berhasil dihapus dari favorit');
    }

    // Menambahkan ulasan
    public function addReview(Request $request, $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|min:10|max:500'
        ]);

        $wisata = Wisata::where('slug', $slug)->firstOrFail();

        // Cek jika sudah pernah memberi ulasan
        $existingReview = Ulasan::where('id_wisata', $wisata->id)
            ->where('id_pengguna', Auth::id())
            ->first();

        if ($existingReview) {
            // Update ulasan yang sudah ada
            $existingReview->update([
                'rating' => $request->rating,
                'tanggal_kunjungan' => Carbon::now()->format('Y-m-d'), // Menggunakan tanggal hari ini
                'komentar' => $request->komentar,
                'status' => 'ditampilkan' // Reset status moderasi
            ]);

            // Update rata-rata rating
            $this->updateRatingAverage($wisata->id);

            return back()->with('success', 'Ulasan berhasil diperbarui');
        }

        // Buat ulasan baru
        Ulasan::create([
            'id_wisata' => $wisata->id,
            'id_pengguna' => Auth::id(),
            'rating' => $request->rating,
            'tanggal_kunjungan' => Carbon::now()->format('Y-m-d'), // Menggunakan tanggal hari ini
            'komentar' => $request->komentar,
            'status' => 'ditampilkan'
        ]);

        // Update rata-rata rating
        $this->updateRatingAverage($wisata->id);

        return back()->with('success', 'Ulasan berhasil dikirim');
    }

    // Tambahkan metode untuk memperbarui rata-rata rating
    private function updateRatingAverage($wisataId)
    {
        $wisata = Wisata::findOrFail($wisataId);
        $averageRating = $wisata->ulasan()->avg('rating') ?? 0;
        $wisata->update(['rata_rata_rating' => round($averageRating, 2)]);
        return $averageRating;
    }
}
