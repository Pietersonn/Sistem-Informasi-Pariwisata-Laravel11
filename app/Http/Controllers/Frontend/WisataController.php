<?php
// File: App\Http\Controllers\Frontend\WisataController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Ulasan;
use App\Models\Favorit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;


class WisataController extends Controller
{
    public function index(Request $request)
    {
        // Kode untuk halaman daftar wisata
        $wisata = Wisata::where('status', 'aktif')
            ->with(['kategori', 'gambarUtama'])
            ->paginate(9);

        $kategori = \App\Models\KategoriWisata::all();

        return view('frontend.wisata.index', compact('wisata', 'kategori'));
    }

    public function show($slug)
    {
        $wisata = Wisata::where('slug', $slug)
            ->where('status', 'aktif')
            ->firstOrFail();

        // Tambahkan jumlah view
        $wisata->increment('jumlah_dilihat');

        // Cek apakah sudah di-favorit (jika user login)
        $sudahDifavorit = false;
        if (Auth::check()) {
            $sudahDifavorit = Favorit::where('id_wisata', $wisata->id)
                ->where('id_pengguna', Auth::id())
                ->exists();
        }

        // Ambil ulasan
        $ulasan = Ulasan::where('id_wisata', $wisata->id)
            ->where('status', 'ditampilkan')
            ->with('pengguna')
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
                'status' => 'menunggu_moderasi' // Reset status moderasi
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
            'status' => 'menunggu_moderasi'
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
