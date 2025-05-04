<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\KategoriWisata;
use App\Models\GambarWisata;
use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WisataController extends Controller
{
    public function index(Request $request)
    {
        // Query wisata dengan eager loading
        $query = Wisata::with(['pemilik', 'kategori', 'gambarUtama']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('alamat', 'like', '%' . $request->search . '%')
                    ->orWhereHas('pemilik', function ($pemilik) use ($request) {
                        $pemilik->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('kategori_wisata.id', $request->kategori);
            });
        }

        // Ambil data dengan pagination
        $wisata = $query->latest()->paginate(10);

        // Ambil semua kategori untuk filter
        $kategori = KategoriWisata::all();
        $status = [Wisata::STATUS_AKTIF, Wisata::STATUS_NONAKTIF, Wisata::STATUS_MENUNGGU_PERSETUJUAN];

        // Kirim data ke view
        return view('admin.wisata.index', [
            'wisata' => $wisata,
            'kategori' => $kategori,
            'status' => $status
        ]);
    }

    public function create()
    {
        // Ambil kategori dan fasilitas untuk dropdown
        $kategori = KategoriWisata::orderBy('nama')->get();
        $fasilitas = Fasilitas::orderBy('nama')->get();

        return view('admin.wisata.create', compact('kategori', 'fasilitas'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validasi = $request->validate([
            'nama' => 'required|unique:wisata,nama',
            'alamat' => 'required',
            'deskripsi' => 'required',
            'kategori' => 'required|array',
            'kategori.*' => 'exists:kategori_wisata,id',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
            'hari_operasional' => 'nullable|array',
            'harga_tiket' => 'nullable|numeric|min:0',
            'kontak' => 'nullable|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'instagram' => 'nullable',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable',
            'status' => 'required|in:' . implode(',', [
                Wisata::STATUS_AKTIF,
                Wisata::STATUS_NONAKTIF,
                Wisata::STATUS_MENUNGGU_PERSETUJUAN
            ]),
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120' // max 5MB
        ]);

        DB::beginTransaction();
        try {
            // Buat wisata baru
            $wisata = new Wisata();
            $wisata->fill($request->except(['kategori', 'fasilitas', 'gambar']));
            $wisata->id_pemilik = Auth::id();
            $wisata->hari_operasional = $request->hari_operasional ?? [];
            $wisata->save();

            // Tambahkan kategori
            $wisata->kategori()->sync($request->kategori);

            // Tambahkan fasilitas
            if ($request->has('fasilitas')) {
                $wisata->fasilitasRelasi()->sync($request->fasilitas);
            }

            // Upload Gambar
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $index => $file) {
                    $isUtama = ($index == 0); // Gambar pertama jadi utama
                    GambarWisata::unggahGambar(
                        $file,
                        $wisata,
                        $isUtama,
                        $request->input('judul_gambar.' . $index, null),
                        $request->input('deskripsi_gambar.' . $index, null)
                    );
                }
            }

            DB::commit();
            return redirect()->route('admin.wisata.index')
                ->with('success', 'Wisata berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(Wisata $wisata)
    {
        // Muat relasi
        $wisata->load([
            'pemilik',
            'kategori',
            'gambar' => function ($q) {
                $q->orderBy('is_utama', 'desc')->orderBy('urutan', 'asc');
            },
            'fasilitasRelasi',
            'ulasan' => function ($q) {
                $q->with('pengguna')->orderBy('created_at', 'desc')->limit(10);
            },
            'event' => function ($q) {
                $q->where('tanggal_selesai', '>=', now())->orderBy('tanggal_mulai', 'asc');
            }
        ]);

        return view('admin.wisata.show', compact('wisata'));
    }

    public function edit(Wisata $wisata)
    {
        $kategori = KategoriWisata::orderBy('nama')->get();
        $fasilitas = Fasilitas::orderBy('nama')->get();

        // Ambil ID kategori dan fasilitas yang sudah dipilih
        $selectedKategori = $wisata->kategori->pluck('id')->toArray();
        $selectedFasilitas = $wisata->fasilitasRelasi->pluck('id')->toArray();

        // Muat gambar dengan urutan yang benar
        $gambar = $wisata->gambar()->orderBy('is_utama', 'desc')
            ->orderBy('urutan', 'asc')
            ->get();

        return view('admin.wisata.edit', [
            'wisata' => $wisata,
            'kategori' => $kategori,
            'fasilitas' => $fasilitas,
            'selectedKategori' => $selectedKategori,
            'selectedFasilitas' => $selectedFasilitas,
            'gambar' => $gambar
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
            'kategori.*' => 'exists:kategori_wisata,id',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'exists:fasilitas,id',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
            'hari_operasional' => 'nullable|array',
            'harga_tiket' => 'nullable|numeric|min:0',
            'kontak' => 'nullable|max:20',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'instagram' => 'nullable',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable',
            'status' => 'required|in:' . implode(',', [
                Wisata::STATUS_AKTIF,
                Wisata::STATUS_NONAKTIF,
                Wisata::STATUS_MENUNGGU_PERSETUJUAN
            ]),
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120' // max 5MB
        ]);

        DB::beginTransaction();
        try {
            // Update wisata
            $wisata->fill($request->except(['kategori', 'fasilitas', 'gambar']));
            $wisata->hari_operasional = $request->hari_operasional ?? [];
            $wisata->save();

            // Update kategori
            $wisata->kategori()->sync($request->kategori);

            // Update fasilitas
            $wisata->fasilitasRelasi()->sync($request->fasilitas ?? []);

            // Upload Gambar Baru
            if ($request->hasFile('gambar')) {
                $adaGambarUtama = $wisata->gambar()->where('is_utama', true)->exists();

                foreach ($request->file('gambar') as $index => $file) {
                    $isUtama = !$adaGambarUtama && ($index == 0);
                    GambarWisata::unggahGambar(
                        $file,
                        $wisata,
                        $isUtama,
                        $request->input('judul_gambar.' . $index, null),
                        $request->input('deskripsi_gambar.' . $index, null)
                    );
                }
            }

            // Atur gambar utama jika ada request
            if ($request->has('gambar_utama') && $request->gambar_utama) {
                GambarWisata::setAsUtama($request->gambar_utama, $wisata->id);
            }

            // Update urutan gambar jika ada
            if ($request->has('urutan_gambar') && is_array($request->urutan_gambar)) {
                GambarWisata::updateUrutan($wisata->id, $request->urutan_gambar);
            }

            DB::commit();
            return redirect()->route('admin.wisata.index')
                ->with('success', 'Wisata berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy(Wisata $wisata)
    {
        try {
            $wisata->hapusLengkap();
            return redirect()->route('admin.wisata.index')
                ->with('success', 'Wisata berhasil dihapus');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    // Method tambahan untuk mengelola gambar
    public function hapusGambar(Request $request, $id)
    {
        $gambar = GambarWisata::findOrFail($id);
        $wisataId = $gambar->id_wisata;

        // Periksa apakah ini gambar utama
        $isUtama = $gambar->is_utama;

        // Hapus gambar
        $gambar->delete();

        // Jika ini gambar utama, set gambar pertama sebagai utama
        if ($isUtama) {
            $gambarPertama = GambarWisata::where('id_wisata', $wisataId)->first();
            if ($gambarPertama) {
                $gambarPertama->update(['is_utama' => true]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function aturGambarUtama(Request $request, $id)
    {
        $gambar = GambarWisata::findOrFail($id);
        GambarWisata::setAsUtama($id, $gambar->id_wisata);

        return response()->json(['success' => true]);
    }

    public function updateUrutanGambar(Request $request)
    {
        $request->validate([
            'wisata_id' => 'required|exists:wisata,id',
            'urutan' => 'required|array',
            'urutan.*' => 'required|integer'
        ]);

        GambarWisata::updateUrutan($request->wisata_id, $request->urutan);

        return response()->json(['success' => true]);
    }
}
