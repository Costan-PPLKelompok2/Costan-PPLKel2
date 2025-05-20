<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\KosReviewController;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/kos');
});

// Semua route di bawah ini hanya bisa diakses jika sudah login
Route::middleware(['auth'])->group(function () {

    // Dashboard (optional)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Manajemen Kos
    Route::get('/kos', [KosController::class, 'index'])->name('kos.index');
    Route::get('/kos/create', [KosController::class, 'create'])->name('kos.create');
    Route::post('/kos', [KosController::class, 'store'])->name('kos.store');
    Route::get('/kos/{id}/edit', [KosController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{id}', [KosController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{id}', [KosController::class, 'destroy'])->name('kos.destroy');

    // Review Kos (PBI mendatang / PBI tambahan)
    Route::get('/kos/{kos_id}/reviews', [KosReviewController::class, 'index'])->name('kos.reviews.index');

    // Fitur Favorit Kos
    Route::post('/kos/{kos}/favorite', [FavoriteController::class, 'store'])->name('kos.favorite');
    Route::delete('/kos/{kos}/unfavorite', [FavoriteController::class, 'destroy'])->name('kos.unfavorite');
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('favorit.index');

    // Manajemen Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth route (login, register, logout)
require __DIR__.'/auth.php';
