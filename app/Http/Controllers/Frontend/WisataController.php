<?php
// File: App\Http\Controllers\Frontend\WisataController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Ulasan;
use App\Models\Favorit;
use Illuminate\Support\Facades\Auth;

class WisataController extends Controller
{
    public function index()
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
        
        // Tampilkan view FRONTEND (bukan admin)
        return view('frontend.wisata.detail', compact(
            'wisata',
            'ulasan',
            'sudahDifavorit'
        ));
    }
}