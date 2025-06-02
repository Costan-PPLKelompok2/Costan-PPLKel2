<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OwnerReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

// Semua route di bawah ini hanya bisa diakses jika sudah login
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Halaman eksplorasi kos untuk penyewa
    Route::get('/explore', [KosController::class, 'explore'])->name('kos.explore');

    // Manajemen Kos (Untuk pemilik kos)
    Route::get('/kos', [KosController::class, 'index'])->name('kos.index');
    Route::get('/kos/create', [KosController::class, 'create'])->name('kos.create');
    Route::post('/kos', [KosController::class, 'store'])->name('kos.store');
    Route::get('/kos/{id}/edit', [KosController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{id}', [KosController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{id}', [KosController::class, 'destroy'])->name('kos.destroy');

    // Hapus atau comment out route berikut jika tidak ingin dipakai:
    // Route::get('/kos/{kos_id}/reviews', [KosReviewController::class, 'index'])->name('kos.reviews.index');

    // Fitur Favorit Kos (untuk penyewa)
    Route::post('/kos/{kos}/favorite', [FavoriteController::class, 'store'])->name('kos.favorite');
    Route::delete('/kos/{kos}/unfavorite', [FavoriteController::class, 'destroy'])->name('kos.unfavorite');
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('kos.favorites');

    // *** Route Review menggunakan ReviewController ***
    Route::get('/kos/{id}/reviews',      [ReviewController::class, 'show'])->name('review.show');
    Route::get('/pemilik/reviews',       [ReviewController::class, 'ownerReviews'])->name('review.owner');
    Route::get('/review/{id}/edit',      [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/{id}',           [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id}',        [ReviewController::class, 'destroy'])->name('review.destroy');

    // Route untuk review pemilik (oleh penyewa)
    Route::get('/owner-review/{ownerId}', [OwnerReviewController::class, 'create'])->name('owner-reviews.create');
    Route::post('/owner-review',          [OwnerReviewController::class, 'store'])->name('owner-reviews.store');
    Route::get('/owner-review/show/{ownerId}', [OwnerReviewController::class, 'show'])->name('owner-reviews.show');
    Route::get('/owner-reviews/{id}/edit',[OwnerReviewController::class, 'edit'])->name('owner-reviews.edit');
    Route::put('/owner-reviews/{id}',     [OwnerReviewController::class, 'update'])->name('owner-reviews.update');
    Route::delete('/owner-reviews/{id}',  [OwnerReviewController::class, 'destroy'])->name('owner-reviews.destroy');

    // Manajemen Profil
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth route (login, register, logout)
require __DIR__.'/auth.php';