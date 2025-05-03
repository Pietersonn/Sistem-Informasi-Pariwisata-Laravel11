<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriWisataController extends Controller
{
    public function index()
    {
        $kategori = KategoriWisata::paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validasi = $request->validate([
            'nama' => 'required|unique:kategori_wisata,nama',
            'deskripsi' => 'nullable',
            'ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'urutan' => 'nullable|integer|min:0'
        ]);

        // Proses upload ikon jika ada
        if ($request->hasFile('ikon')) {
            $file = $request->file('ikon');
            $namaFile = 'kategori_' . Str::slug($request->nama) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/kategori', $namaFile);
            $validasi['ikon'] = str_replace('public/', '', $path);
        }

        // Buat kategori
        $kategori = KategoriWisata::create($validasi);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    public function destroy(KategoriWisata $kategori)
    {
        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
    public function edit(KategoriWisata $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriWisata $kategori)
    {
        // Validasi input
        $validasi = $request->validate([
            'nama' => 'required|unique:kategori_wisata,nama,' . $kategori->id,
            'deskripsi' => 'nullable',
            'ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'urutan' => 'nullable|integer|min:0'
        ]);

        // Proses upload ikon jika ada
        if ($request->hasFile('ikon')) {
            $file = $request->file('ikon');
            $namaFile = 'kategori_' . Str::slug($request->nama) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/kategori', $namaFile);
            $validasi['ikon'] = str_replace('public/', '', $path);
        }

        // Update kategori
        $kategori->update($validasi);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }
}