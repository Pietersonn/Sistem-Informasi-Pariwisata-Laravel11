<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        // Cek apakah role pengguna ada di daftar role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya akses, kembalikan ke halaman sebelumnya dengan pesan error
        return redirect()->back()->with('error', 'Anda tidak memiliki akses');
    }
}
