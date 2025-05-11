<?php
// app/Http/Controllers/Frontend/FavoritController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Favorit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    public function index()
    {
        // Ambil semua favorit milik user yang login
        $favorit = Favorit::with(['wisata' => function($query) {
            $query->with(['kategori', 'gambarUtama']);
        }])
        ->where('id_pengguna', Auth::id())
        ->latest()
        ->paginate(9);
        
        return view('frontend.profil.favorit', compact('favorit'));
    }
    
    public function updateCatatan(Request $request, $id)
    {
        $favorit = Favorit::where('id', $id)
            ->where('id_pengguna', Auth::id())
            ->firstOrFail();
            
        $favorit->update([
            'catatan' => $request->catatan
        ]);
        
        return back()->with('success', 'Catatan berhasil diperbarui');
    }
    
    public function destroy($id)
    {
        $favorit = Favorit::where('id', $id)
            ->where('id_pengguna', Auth::id())
            ->firstOrFail();
            
        $favorit->delete();
        
        return back()->with('success', 'Wisata berhasil dihapus dari favorit');
    }
}