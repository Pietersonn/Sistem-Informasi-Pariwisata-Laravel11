<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventWisata;
use App\Models\Wisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar untuk event
        $query = EventWisata::with(['wisata'])
            ->orderBy('tanggal_mulai', 'desc');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                    ->orWhereHas('wisata', function ($wisata) use ($request) {
                        $wisata->where('nama', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan wisata
        if ($request->filled('wisata')) {
            $query->where('id_wisata', $request->wisata);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai);
        }

        // Ambil data dengan pagination
        $events = $query->paginate(10);

        // Data untuk filter dropdown
        $wisataList = Wisata::where('status', 'aktif')->get();
        $statusList = ['aktif', 'selesai', 'dibatalkan'];

        return view('admin.event.index', compact('events', 'wisataList', 'statusList'));
    }

    public function create()
    {
        $wisataList = Wisata::where('status', 'aktif')->get();
        return view('admin.event.create', compact('wisataList'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validasi = $request->validate([
            'id_wisata' => 'required|exists:wisata,id',
            'nama' => 'required|max:255',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'status' => 'required|in:aktif,selesai,dibatalkan'
        ]);

        try {
            $event = new EventWisata();
            $event->fill($request->only(['id_wisata', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status']));

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

            return redirect()->route('admin.event.index')
                ->with('success', 'Event berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show(EventWisata $event)
    {
        $event->load(['wisata']);
        return view('admin.event.show', compact('event'));
    }

    public function edit(EventWisata $event)
    {
        $wisataList = Wisata::where('status', 'aktif')->get();
        return view('admin.event.edit', compact('event', 'wisataList'));
    }

    public function update(Request $request, EventWisata $event)
    {
        // Validasi input
        $validasi = $request->validate([
            'id_wisata' => 'required|exists:wisata,id',
            'nama' => 'required|max:255',
            'deskripsi' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'status' => 'required|in:aktif,selesai,dibatalkan'
        ]);

        try {
            $event->fill($request->only(['id_wisata', 'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'status']));

            // Upload poster baru jika ada
            if ($request->hasFile('poster')) {
                // Hapus poster lama jika ada
                if ($event->poster && file_exists(public_path($event->poster))) {
                    unlink(public_path($event->poster));
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

            $event->save();

            return redirect()->route('admin.event.index')
                ->with('success', 'Event berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function destroy(EventWisata $event)
    {
        try {
            // Hapus poster jika ada
            if ($event->poster && file_exists(public_path($event->poster))) {
                unlink(public_path($event->poster));
            }

            $event->delete();

            return redirect()->route('admin.event.index')
                ->with('success', 'Event berhasil dihapus');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, EventWisata $event)
    {
        $validasi = $request->validate([
            'status' => 'required|in:aktif,selesai,dibatalkan'
        ]);

        $event->update($validasi);

        return redirect()->route('admin.event.index')
            ->with('success', 'Status event berhasil diperbarui');
    }

    // Method untuk mendapatkan statistik event
    public function getEventStats()
    {
        $stats = [
            'total_event' => EventWisata::count(),
            'event_aktif' => EventWisata::where('status', 'aktif')->count(),
            'event_selesai' => EventWisata::where('status', 'selesai')->count(),
            'event_dibatalkan' => EventWisata::where('status', 'dibatalkan')->count(),
            'event_bulan_ini' => EventWisata::whereMonth('tanggal_mulai', Carbon::now()->month)
                ->whereYear('tanggal_mulai', Carbon::now()->year)
                ->count(),
            'event_mendatang' => EventWisata::where('status', 'aktif')
                ->where('tanggal_mulai', '>', Carbon::now())
                ->count()
        ];

        return $stats;
    }
}