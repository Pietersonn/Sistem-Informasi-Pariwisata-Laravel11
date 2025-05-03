<?php

use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\Admin\WisataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

// Route untuk guest
Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

// Route untuk user yang sudah login dan memiliki role admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [HomeController::class, 'home']);
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
    
    // Route untuk admin (wisata)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/wisata', [WisataController::class, 'index'])->name('wisata.index');
        Route::get('/wisata/create', [WisataController::class, 'create'])->name('wisata.create');
        Route::post('/wisata', [WisataController::class, 'store'])->name('wisata.store');
        Route::get('/wisata/{wisata}', [WisataController::class, 'show'])->name('wisata.show');
        Route::get('/wisata/{wisata}/edit', [WisataController::class, 'edit'])->name('wisata.edit');
        Route::put('/wisata/{wisata}', [WisataController::class, 'update'])->name('wisata.update');
        Route::delete('/wisata/{wisata}', [WisataController::class, 'destroy'])->name('wisata.destroy');
    });
});