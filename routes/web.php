<?php
// routes/web.php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\Admin\WisataController;
use App\Http\Controllers\Admin\KategoriWisataController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\WisataController as FrontendWisataController;

// ==============================
// PUBLIC ROUTES (SEMUA AKSES)
// ==============================
Route::get('/', [HomeController::class, 'index']);
Route::get('/wisata', [FrontendWisataController::class, 'index'])->name('wisata.index');
Route::get('/wisata/detail/{slug}', [FrontendWisataController::class, 'show'])->name('wisata.detail');

// ==============================
// GUEST ONLY ROUTES (HANYA NON-LOGIN)
// ==============================
Route::middleware(['guest'])->group(function () {
    // Authentication
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);

    // Password Reset
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// ==============================
// AUTH ROUTES (SEMUA USER LOGIN)
// ==============================
Route::middleware(['auth'])->group(function () {
    // Favorit & Ulasan
    Route::post('/wisata/favorit/{slug}', [FrontendWisataController::class, 'addToFavorite'])->name('wisata.favorit');
    Route::delete('/wisata/favorit/{slug}', [FrontendWisataController::class, 'removeFromFavorite'])->name('wisata.favorit.hapus');
    Route::post('/wisata/ulasan/{slug}', [FrontendWisataController::class, 'addReview'])->name('wisata.ulasan');

    // Favorit routes
    Route::get('/favorit', [App\Http\Controllers\Frontend\FavoritController::class, 'index'])->name('favorit.index');
    Route::put('/favorit/{id}/catatan', [App\Http\Controllers\Frontend\FavoritController::class, 'updateCatatan'])->name('favorit.update-catatan');
    Route::delete('/favorit/{id}', [App\Http\Controllers\Frontend\FavoritController::class, 'destroy'])->name('favorit.destroy');


    // User Profile
    Route::get('/profil', function () {
        return view('frontend.profil.dashboard');
    })->name('profil');

    Route::get('/favorit', function () {
        return view('frontend.profil.favorit');
    })->name('favorit');

    // Logout
    Route::get('/logout', [SessionsController::class, 'destroy']);
});

// ==============================
// ADMIN ROUTES
// ==============================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // User Profile
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);

    // Wisata Management
    Route::resource('/wisata', WisataController::class);

    // Kategori Management
    Route::resource('/kategori', KategoriWisataController::class)->except(['show']);

    // Ulasan Management
    Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
    Route::get('/ulasan/{ulasan}', [UlasanController::class, 'show'])->name('ulasan.show');
    Route::put('/ulasan/{ulasan}/status', [UlasanController::class, 'updateStatus'])->name('ulasan.update-status');
    Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');

    // Pengguna Management
    Route::resource('/pengguna', PenggunaController::class);

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/wisata', [LaporanController::class, 'wisata'])->name('wisata');
        Route::get('/ulasan', [LaporanController::class, 'ulasan'])->name('ulasan');
        Route::get('/kunjungan', [LaporanController::class, 'kunjungan'])->name('kunjungan');
        Route::get('/event', [LaporanController::class, 'event'])->name('event');
        Route::get('/ekspor/{jenis}', [LaporanController::class, 'eksporPdf'])->name('ekspor');
    });
});

// ==============================
// PEMILIK WISATA ROUTES
// ==============================
Route::middleware(['auth', 'role:pemilik_wisata'])->prefix('pemilik')->name('pemilik.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pemilik.dashboard');
    })->name('dashboard');

    // Tambahkan routes lain untuk pemilik wisata jika diperlukan
});
