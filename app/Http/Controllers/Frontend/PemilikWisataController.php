<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\KategoriWisata;
use App\Models\GambarWisata;
use App\Models\EventWisata;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PemilikWisataController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        
        // Statistik wisata
        $statistik = [
            'total_wisata' => Wisata::where('id_pemilik', $userId)->count(),
            'total_ulasan' => Ulasan::whereHas('wisata', function($q) use ($userId) {
                $q->where('id_pemilik', $userId);
            })->count(),
            'total_event' => EventWisata::whereHas('wisata', function($q) use ($userId) {
                $q->where('id_pemilik', $userId);
            })->count(),
            'total_kunjungan' => Wisata::where('id_pemilik', $userId)->sum('jumlah_dilihat'),
            'rata_rata_rating' => Wisata::where('id_pemilik', $userId)->avg('rata_rata_rating') ?? 0
        ];
        
        // Top wisata berdasarkan kunjungan
        $topWisata = Wisata::where('id_pemilik', $userId)
            ->orderBy('jumlah_dilihat', 'desc')
            ->take(5)
            ->get();
            
        // Ulasan terbaru
        $ulasanTerbaru = Ulasan::whereHas('wisata', function($q) use ($userId) {
            $q->where('id_pemilik', $userId);
        })
        ->with(['wisata', 'pengguna'])
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
        
        return view('frontend.pemilik.dashboard', compact('statistik', 'topWisata', 'ulasanTerbaru'));
    }
    
    // Daftar wisata milik pemilik
    public function wisataIndex()
    {
        $wisata = Wisata::where('id_pemilik', Auth::id())
            ->with(['kategori', 'gambarUtama'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('frontend.pemilik.wisata.index', compact('wisata'));
    }
    
    // Form tambah wisata
    public function wisataCreate()
    {
        $kategori = KategoriWisata::orderBy('nama')->get();
        return view('frontend.pemilik.wisata.create', compact('kategori'));
    }
    
    // Simpan wisata baru
    public function wisataStore(Request $request)
    {
        // Validasi input
        $validasi = $request->validate([
            'nama' => 'required|max:255|unique:wisata,nama',
            'alamat' => 'required',
            'deskripsi' => 'required',
            'kategori' => 'required|array',
            'kategori.*' => 'exists:kategori_wisata,id',
            'fasilitas' => 'nullable|array',
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'gambar' => 'required|array|min:1',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120'
        ]);

        DB::beginTransaction();
        try {
            // Membuat wisata baru
            $wisata = new Wisata();
            $wisata->fill($request->except(['kategori', 'fasilitas', 'gambar']));
            
            // Simpan fasilitas sebagai array
            $wisata->fasilitas = $request->fasilitas ?? [];
            
            // Set pemilik wisata (user yang login)
            $wisata->id_pemilik = Auth::id();
            $wisata->hari_operasional = $request->hari_operasional ?? [];
            
            // Simpan koordinat
            $wisata->latitude = $request->latitude;
            $wisata->longitude = $request->longitude;
            
            // Status wisata diset menunggu persetujuan
            $wisata->status = 'menunggu_persetujuan';
            
            $wisata->save();

            // Menambahkan kategori
            $wisata->kategori()->sync($request->kategori);

            // Upload Gambar
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $index => $file) {
                    $isUtama = ($index == 0); // Gambar pertama jadi utama

                    // Generate nama file unik
                    $namaFile = 'wisata-' . Str::slug($wisata->nama) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

                    // Pastikan direktori ada
                    $uploadPath = public_path('uploads/wisata');
                    if (!File::exists($uploadPath)) {
                        File::makeDirectory($uploadPath, 0775, true);
                    }

                    // Pindahkan file
                    $file->move($uploadPath, $namaFile);

                    // Simpan ke database
                    $gambarWisata = new GambarWisata();
                    $gambarWisata->id_wisata = $wisata->id;
                    $gambarWisata->file_gambar = 'uploads/wisata/' . $namaFile;
                    $gambarWisata->judul = $request->input('judul_gambar.' . $index, null);
                    $gambarWisata->deskripsi = $request->input('deskripsi_gambar.' . $index, null);
                    $gambarWisata->urutan = $index + 1;
                    $gambarWisata->is_utama = $isUtama;
                    $gambarWisata->save();
                }
            }

            DB::commit();
            return redirect()->route('pemilik.wisata.index')
                ->with('success', 'Wisata berhasil ditambahkan dan menunggu persetujuan admin');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    // Detail wisata
    public function wisataShow($id)
    {
        $wisata = Wisata::where('id', $id)
            ->where('id_pemilik', Auth::id())
            ->with(['kategori', 'gambar', 'ulasan.pengguna', 'event'])
            ->firstOrFail();
            
        return view('frontend.pemilik.wisata.show', compact('wisata'));
    }
    
    // Form edit wisata
    public function wisataEdit($id)
    {
        $wisata = Wisata::where('id', $id)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();
            
        $kategori = KategoriWisata::orderBy('nama')->get();
        
        // Ambil ID kategori yang sudah dipilih
        $selectedKategori = $wisata->kategori->pluck('id')->toArray();
        
        // Muat gambar dengan urutan yang benar
        $gambar = $wisata->gambar()->orderBy('is_utama', 'desc')
            ->orderBy('urutan', 'asc')
            ->get();
            
        return view('frontend.pemilik.wisata.edit', compact('wisata', 'kategori', 'selectedKategori', 'gambar'));
    }
    
    // Update wisata
    public function wisataUpdate(Request $request, $id)
    {
        $wisata = Wisata::where('id', $id)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();
            
        // Validasi input
        $validasi = $request->validate([
            'nama' => 'required|max:255|unique:wisata,nama,' . $wisata->id,
            'alamat' => 'required',
            'deskripsi' => 'required',
            'kategori' => 'required|array',
            'kategori.*' => 'exists:kategori_wisata,id',
            'fasilitas' => 'nullable|array',
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:5120'
        ]);
        
        DB::beginTransaction();
        try {
            $wisata->fill($request->except(['kategori', 'fasilitas', 'gambar']));
            $wisata->fasilitas = $request->fasilitas ?? [];
            $wisata->hari_operasional = $request->hari_operasional ?? [];
            
            // Jika status sebelumnya sudah ditolak, maka set ulang ke menunggu persetujuan
            if ($wisata->status === 'nonaktif') {
                $wisata->status = 'menunggu_persetujuan';
            }
            
            $wisata->save();
            
            // Update kategori
            $wisata->kategori()->sync($request->kategori);
            
            // Upload gambar baru jika ada
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $index => $file) {
                    // Cek ada gambar utama atau tidak
                    $adaGambarUtama = $wisata->gambar()->where('is_utama', true)->exists();
                    $isUtama = !$adaGambarUtama && ($index == 0);
                    
                    // Upload gambar
                    GambarWisata::unggahGambar(
                        $file,
                        $wisata,
                        $isUtama,
                        $request->input('judul_gambar.' . $index, null),
                        $request->input('deskripsi_gambar.' . $index, null)
                    );
                }
            }
            
            // Update gambar utama jika ada permintaan
            if ($request->has('gambar_utama') && $request->gambar_utama) {
                GambarWisata::setAsUtama($request->gambar_utama, $wisata->id);
            }
            
            DB::commit();
            return redirect()->route('pemilik.wisata.index')
                ->with('success', 'Wisata berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    // Hapus gambar
    public function hapusGambar($id)
    {
        $gambar = GambarWisata::findOrFail($id);
        
        // Cek apakah gambar milik wisata yang dimiliki user
        $wisata = Wisata::where('id', $gambar->id_wisata)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();
            
        $isUtama = $gambar->is_utama;
        
        // Hapus gambar
        $gambar->delete();
        
        // Jika ini gambar utama, set gambar pertama sebagai utama
        if ($isUtama) {
            $gambarPertama = GambarWisata::where('id_wisata', $wisata->id)->first();
            if ($gambarPertama) {
                $gambarPertama->update(['is_utama' => true]);
            }
        }
        
        return response()->json(['success' => true]);
    }
    
    // Manajemen event wisata
    public function eventIndex()
    {
        $events = EventWisata::whereHas('wisata', function($q) {
            $q->where('id_pemilik', Auth::id());
        })
        ->with('wisata')
        ->orderBy('tanggal_mulai', 'desc')
        ->paginate(10);
        
        return view('frontend.pemilik.event.index', compact('events'));
    }
    
    // Form tambah event
    public function eventCreate()
    {
        $wisata = Wisata::where('id_pemilik', Auth::id())
            ->where('status', 'aktif')
            ->get();
            
        return view('frontend.pemilik.event.create', compact('wisata'));
    }
    
    // Simpan event baru
    public function eventStore(Request $request)
    {
        // Validasi input
        $validasi = $request->validate([
            'id_wisata' => [
                'required',
                'exists:wisata,id',
                function ($attribute, $value, $fail) {
                    $wisata = Wisata::where('id', $value)
                        ->where('id_pemilik', Auth::id())
                        ->first();
                        
                    if (!$wisata) {
                        $fail('Wisata yang dipilih tidak valid atau bukan milik Anda');
                    }
                }
            ],
            'nama' => 'required|max:255',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120'
        ]);
        
        try {
            $event = new EventWisata();
            $event->fill($request->only(['id_wisata', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']));
            
            // Simpan dalam status menunggu persetujuan
            $event->status = 'menunggu_persetujuan';
            
            // Upload poster jika ada
            if ($request->hasFile('poster')) {
                $file = $request->file('poster');
                $namaFile = 'event-' . Str::slug($request->nama) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Pastikan direktori ada
                $uploadPath = public_path('uploads/event');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true);
                }
                
                // Pindahkan file
                $file->move($uploadPath, $namaFile);
                
                // Simpan path ke database
                $event->poster = 'uploads/event/' . $namaFile;
            }
            
            $event->save();
            
            return redirect()->route('pemilik.event.index')
                ->with('success', 'Event berhasil ditambahkan dan menunggu persetujuan admin');
                
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    // Form edit event
    public function eventEdit($id)
    {
        $event = EventWisata::findOrFail($id);
        
        // Verifikasi pemilik
        $wisata = Wisata::where('id', $event->id_wisata)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();
            
        $wisataList = Wisata::where('id_pemilik', Auth::id())
            ->where('status', 'aktif')
            ->get();
            
        return view('frontend.pemilik.event.edit', compact('event', 'wisataList'));
    }
    
    // Update event
    public function eventUpdate(Request $request, $id)
    {
        $event = EventWisata::findOrFail($id);
        
        // Verifikasi pemilik
        $wisata = Wisata::where('id', $event->id_wisata)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();
            
        // Validasi input
        $validasi = $request->validate([
            'id_wisata' => [
                'required',
                'exists:wisata,id',
                function ($attribute, $value, $fail) {
                    $wisata = Wisata::where('id', $value)
                        ->where('id_pemilik', Auth::id())
                        ->first();
                        
                    if (!$wisata) {
                        $fail('Wisata yang dipilih tidak valid atau bukan milik Anda');
                    }
                }
            ],
            'nama' => 'required|max:255',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120'
        ]);
        
        try {
            $event->fill($request->only(['id_wisata', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai']));
            
            // Upload poster baru jika ada
            if ($request->hasFile('poster')) {
                // Hapus poster lama jika ada
                if ($event->poster) {
                    $oldPath = public_path($event->poster);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                $file = $request->file('poster');
                $namaFile = 'event-' . Str::slug($request->nama) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Pastikan direktori ada
                $uploadPath = public_path('uploads/event');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0775, true);
                }
                
                // Pindahkan file
                $file->move($uploadPath, $namaFile);
                
                // Simpan path ke database
                $event->poster = 'uploads/event/' . $namaFile;
            }
            
            // Jika sebelumnya ditolak, ubah status menjadi menunggu persetujuan
            if ($event->status === 'dibatalkan') {
                $event->status = 'menunggu_persetujuan';
            }
            
            $event->save();
            
            return redirect()->route('pemilik.event.index')
                ->with('success', 'Event berhasil diperbarui');
                
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    // Manajemen ulasan
    public function ulasanIndex()
    {
        $ulasan = Ulasan::whereHas('wisata', function($q) {
                $q->where('id_pemilik', Auth::id());
            })
            ->with(['wisata', 'pengguna', 'balasan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('frontend.pemilik.ulasan.index', compact('ulasan'));
    }
    
    // Tambah balasan ulasan
    public function balasUlasan(Request $request, $id)
    {
        $ulasan = Ulasan::findOrFail($id);
        
        // Verifikasi pemilik
        $wisata = Wisata::where('id', $ulasan->id_wisata)
            ->where('id_pemilik', Auth::id())
            ->firstOrFail();
            
        // Validasi input
        $validasi = $request->validate([
            'balasan' => 'required|min:3|max:500'
        ]);
        
        try {
            // Cek jika sudah ada balasan
            $balasan = $ulasan->balasan()->where('id_pengguna', Auth::id())->first();
            
            if ($balasan) {
                // Update balasan
                $balasan->balasan = $request->balasan;
                $balasan->save();
            } else {
                // Tambah balasan baru
                $ulasan->balasan()->create([
                    'id_pengguna' => Auth::id(),
                    'balasan' => $request->balasan
                ]);
            }
            
            return redirect()->route('pemilik.ulasan.index')
                ->with('success', 'Balasan berhasil disimpan');
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}