<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\Admin\WisataController;
use App\Http\Controllers\Admin\KategoriWisataController;
use App\Http\Controllers\Admin\UlasanController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\EventController as FrontendEventController;
use App\Http\Controllers\Frontend\PemilikWisataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\WisataController as FrontendWisataController;
use App\Http\Controllers\Frontend\ProfilController;

// ==============================
// PUBLIC ROUTES (SEMUA AKSES)
// ==============================
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/wisata', [FrontendWisataController::class, 'index'])->name('wisata.index');
Route::get('/wisata/detail/{slug}', [FrontendWisataController::class, 'show'])->name('wisata.detail');

// PERBAIKAN: Gunakan Frontend EventController untuk routes publik
Route::get('/event', [FrontendEventController::class, 'index'])->name('event.index');
Route::get('/event/{id}', [FrontendEventController::class, 'show'])->name('event.detail');

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
    Route::put('/profile/update', [ProfilController::class, 'update'])->name('profile.update');

    // Logout
    Route::get('/signout', [SessionsController::class, 'destroy']);
});

// ==============================
// PEMILIK WISATA ROUTES
// ==============================
Route::middleware(['auth', 'role:pemilik_wisata'])->prefix('pemilik')->name('pemilik.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [PemilikWisataController::class, 'dashboard'])->name('dashboard');

    // Manajemen Wisata
    Route::get('/wisata', [PemilikWisataController::class, 'wisataIndex'])->name('wisata.index');
    Route::get('/wisata/create', [PemilikWisataController::class, 'wisataCreate'])->name('wisata.create');
    Route::post('/wisata', [PemilikWisataController::class, 'wisataStore'])->name('wisata.store');
    Route::get('/wisata/{id}', [PemilikWisataController::class, 'wisataShow'])->name('wisata.show');
    Route::get('/wisata/{id}/edit', [PemilikWisataController::class, 'wisataEdit'])->name('wisata.edit');
    Route::put('/wisata/{id}', [PemilikWisataController::class, 'wisataUpdate'])->name('wisata.update');

    // Manajemen Gambar
    Route::delete('/gambar/{id}', [PemilikWisataController::class, 'hapusGambar'])->name('gambar.destroy');

    // Manajemen Event
    Route::get('/event', [PemilikWisataController::class, 'eventIndex'])->name('event.index');
    Route::get('/event/create', [PemilikWisataController::class, 'eventCreate'])->name('event.create');
    Route::post('/event', [PemilikWisataController::class, 'eventStore'])->name('event.store');
    Route::get('/event/{id}/edit', [PemilikWisataController::class, 'eventEdit'])->name('event.edit');
    Route::put('/event/{id}', [PemilikWisataController::class, 'eventUpdate'])->name('event.update');

    // Manajemen Ulasan
    Route::get('/ulasan', [PemilikWisataController::class, 'ulasanIndex'])->name('ulasan.index');
    Route::post('/ulasan/{id}/balas', [PemilikWisataController::class, 'balasUlasan'])->name('ulasan.balas');
});

// ==============================
// ADMIN ROUTES
// ==============================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile', function () {
        return view('profile');
    })->name('profile');
    Route::get('tables', function () {
        return view('tables');
    })->name('tables');
    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);

    Route::prefix('admin')->name('admin.')->group(function () {
        // Route Wisata
        Route::get('/wisata', [WisataController::class, 'index'])->name('wisata.index');
        Route::get('/wisata/create', [WisataController::class, 'create'])->name('wisata.create');
        Route::post('/wisata', [WisataController::class, 'store'])->name('wisata.store');
        Route::get('/wisata/{wisata}', [WisataController::class, 'show'])->name('wisata.show');
        Route::get('/wisata/{wisata}/edit', [WisataController::class, 'edit'])->name('wisata.edit');
        Route::put('/wisata/{wisata}', [WisataController::class, 'update'])->name('wisata.update');
        Route::delete('/wisata/{wisata}', [WisataController::class, 'destroy'])->name('wisata.destroy');

        // Route Event - PERBAIKAN: Gunakan AdminEventController untuk admin routes
        Route::get('/event', [AdminEventController::class, 'index'])->name('event.index');
        Route::get('/event/create', [AdminEventController::class, 'create'])->name('event.create');
        Route::post('/event', [AdminEventController::class, 'store'])->name('event.store');
        Route::get('/event/{event}/edit', [AdminEventController::class, 'edit'])->name('event.edit');
        Route::put('/event/{event}', [AdminEventController::class, 'update'])->name('event.update');
        Route::put('/event/{event}/status', [AdminEventController::class, 'updateStatus'])->name('event.update-status');
        Route::delete('/event/{event}', [AdminEventController::class, 'destroy'])->name('event.destroy');

        // Route Kategori
        Route::get('/kategori', [KategoriWisataController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [KategoriWisataController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', [KategoriWisataController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{kategori}/edit', [KategoriWisataController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{kategori}', [KategoriWisataController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriWisataController::class, 'destroy'])->name('kategori.destroy');

        // Route Ulasan
        Route::get('/ulasan', [UlasanController::class, 'index'])->name('ulasan.index');
        Route::get('/ulasan/{ulasan}', [UlasanController::class, 'show'])->name('ulasan.show');
        Route::put('/ulasan/{ulasan}/status', [UlasanController::class, 'updateStatus'])->name('ulasan.update-status');
        Route::delete('/ulasan/{ulasan}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');

        // Route pengguna
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
        Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::get('/pengguna/{pengguna}', [PenggunaController::class, 'show'])->name('pengguna.show');
        Route::get('/pengguna/{pengguna}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{pengguna}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

        // Route Laporan
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/wisata', [LaporanController::class, 'wisata'])->name('wisata');
            Route::get('/ulasan', [LaporanController::class, 'ulasan'])->name('ulasan');
            Route::get('/kunjungan', [LaporanController::class, 'kunjungan'])->name('kunjungan');
            Route::get('/event', [LaporanController::class, 'event'])->name('event');

            // Routes untuk export PDF
            Route::get('/wisata/pdf', [LaporanController::class, 'eksporPdf'])->defaults('jenis', 'wisata')->name('wisata.pdf');
            Route::get('/ulasan/pdf', [LaporanController::class, 'eksporPdf'])->defaults('jenis', 'ulasan')->name('ulasan.pdf');
            Route::get('/event/pdf', [LaporanController::class, 'eksporPdf'])->defaults('jenis', 'event')->name('event.pdf');
            Route::get('/kunjungan/pdf', [LaporanController::class, 'eksporPdf'])->defaults('jenis', 'kunjungan')->name('kunjungan.pdf');
        });
    });
});
