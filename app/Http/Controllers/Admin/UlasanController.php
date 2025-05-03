<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar untuk ulasan
        $query = Ulasan::with(['wisata', 'pengguna'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('wisata', function($wisata) use ($request) {
                    $wisata->where('nama', 'like', '%' . $request->search . '%');
                })
                ->orWhereHas('pengguna', function($pengguna) use ($request) {
                    $pengguna->where('name', 'like', '%' . $request->search . '%');
                })
                ->orWhere('komentar', 'like', '%' . $request->search . '%');
            });
        }

        // Ambil data dengan pagination
        $ulasan = $query->paginate(10);

        // Kirim data ke view
        return view('admin.ulasan.index', [
            'ulasan' => $ulasan,
            'status' => ['ditampilkan', 'disembunyikan', 'menunggu_moderasi']
        ]);
    }

    public function show(Ulasan $ulasan)
    {
        // Muat relasi yang diperlukan
        $ulasan->load(['wisata', 'pengguna', 'balasan']);

        return view('admin.ulasan.show', compact('ulasan'));
    }

    public function updateStatus(Request $request, Ulasan $ulasan)
    {
        $validasi = $request->validate([
            'status' => 'required|in:ditampilkan,disembunyikan,menunggu_moderasi'
        ]);

        $ulasan->update($validasi);

        return redirect()->route('admin.ulasan.index')
            ->with('success', 'Status ulasan berhasil diperbarui');
    }

    public function destroy(Ulasan $ulasan)
    {
        $ulasan->delete();

        return redirect()->route('admin.ulasan.index')
            ->with('success', 'Ulasan berhasil dihapus');
    }
}