<?php

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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

// Route untuk guest
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
    Route::get('/wisata', [WisataController::class, 'index'])->name('wisata.index');
});

// Route untuk user yang sudah login dan memiliki role admin
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('profile', function () {
        return view('profile');
    })->name('profile');
    Route::get('tables', function () {
        return view('tables');
    })->name('tables');
    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            // Route Wisata
            Route::get('/wisata', [WisataController::class, 'index'])->name('wisata.index');
            Route::get('/wisata/create', [WisataController::class, 'create'])->name('wisata.create');
            Route::post('/wisata', [WisataController::class, 'store'])->name('wisata.store');
            Route::get('/wisata/{wisata}', [WisataController::class, 'show'])->name('wisata.show');
            Route::get('/wisata/{wisata}/edit', [WisataController::class, 'edit'])->name('wisata.edit');
            Route::put('/wisata/{wisata}', [WisataController::class, 'update'])->name('wisata.update');
            Route::delete('/wisata/{wisata}', [WisataController::class, 'destroy'])->name('wisata.destroy');

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
                Route::get('/ekspor/{jenis}', [LaporanController::class, 'eksporPdf'])->name('ekspor');
            });
        });
    });
});
