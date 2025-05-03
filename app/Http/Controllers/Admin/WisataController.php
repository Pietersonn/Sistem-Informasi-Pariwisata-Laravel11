<?php
// app/Http/Controllers/Admin/WisataController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\KategoriWisata;
use App\Models\GambarWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        // Query wisata dengan eager loading
        $query = Wisata::with(['pemilik', 'kategori']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('alamat', 'like', '%' . $request->search . '%')
                    ->orWhereHas('pemilik', function ($pemilik) use ($request) {
                        $pemilik->where('nama', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil data dengan pagination
        $wisata = $query->paginate(10);

        // Kirim data ke view
        return view('admin.wisata.index', [
            'wisata' => $wisata,
            'status' => ['aktif', 'nonaktif', 'menunggu_persetujuan']
        ]);
    }

    public function create()
    {
        $kategori = KategoriWisata::all(); // Mengambil semua kategori
        return view('admin.wisata.create', compact('kategori')); 
    }

    public function store(Request $request)
    {
        $validasi = $request->validate([
            'nama' => 'required|unique:wisata,nama',
            'alamat' => 'required',
            'deskripsi' => 'required',
            'kategori' => 'required|array',
            'status' => 'required|in:aktif,nonaktif,menunggu_persetujuan',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Buat wisata baru
        $wisata = new Wisata();
        $wisata->fill($request->except(['kategori', 'gambar']));
        $wisata->id_pemilik = auth()->user()->id;
        $wisata->save();

        // Tambahkan kategori
        $wisata->kategori()->sync($request->kategori);

        // Upload Gambar
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $index => $file) {
                $namaFile = 'wisata_' . $wisata->id . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/wisata', $namaFile);

                $gambar = new GambarWisata();
                $gambar->id_wisata = $wisata->id;
                $gambar->file_gambar = str_replace('public/', '', $path);
                $gambar->is_utama = ($index == 0); // Gambar pertama jadi utama
                $gambar->save();
            }
        }

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil ditambahkan');
    }

    public function show(Wisata $wisata)
    {
        // Muat relasi
        $wisata->load(['pemilik', 'kategori', 'gambar', 'ulasan']);
        return view('admin.wisata.show', compact('wisata'));
    }

    public function edit(Wisata $wisata)
    {
        $kategori = KategoriWisata::all();
        // Ambil ID kategori yang sudah dipilih
        $selectedKategori = $wisata->kategori->pluck('id')->toArray();

        return view('admin.wisata.edit', [
            'wisata' => $wisata,
            'kategori' => $kategori,
            'selectedKategori' => $selectedKategori
        ]);
    }

    public function update(Request $request, Wisata $wisata)
    {
        // Validasi input
        $validasi = $request->validate([
            'nama' => 'required|max:255|unique:wisata,nama,' . $wisata->id,
            'alamat' => 'required',
            'deskripsi' => 'required',
            'kategori' => 'required|array',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
            'harga_tiket' => 'nullable|numeric|min:0',
            'kontak' => 'nullable|max:20',
            'email' => 'nullable|email',
            'status' => 'required|in:aktif,nonaktif,menunggu_persetujuan'
        ]);

        // Update wisata
        $wisata->fill($request->except(['kategori']));
        $wisata->save();

        // Update kategori
        $wisata->kategori()->sync($request->kategori);

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil diperbarui');
    }

    public function destroy(Wisata $wisata)
    {
        // Hapus relasi kategori
        $wisata->kategori()->detach();

        // Hapus wisata
        $wisata->delete();

        return redirect()->route('admin.wisata.index')
            ->with('success', 'Wisata berhasil dihapus');
    }
}
