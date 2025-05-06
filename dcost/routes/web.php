<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\KosReviewController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Public landing & listing
Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::get('/kos', [KosController::class, 'index'])->name('kos.index');

// 2. Full‐filter search page
Route::get('/kos/search', [KosController::class, 'search'])->name('kos.search');

// 3. Public detail (only numeric IDs)
Route::get('/kos/{id_kos}', [KosController::class, 'show'])
    ->whereNumber('id_kos')
    ->name('kos.show');

// 4. Protected dashboard shortcut
Route::get('/dashboard', function () {
    return route('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 5. Optional redirect helper
Route::get('/redirect', [HomeController::class, 'redirect'])->name('redirect');

// 6. Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kos management
    Route::get('/my-kos',            [KosController::class, 'manage'])->name('kos.manage');
    Route::get('/kos/create',        [KosController::class, 'create'])->name('kos.create');
    Route::post('/kos',              [KosController::class, 'store'])->name('kos.store');
    Route::get('/kos/{id}/edit',     [KosController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{id}',          [KosController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{id}',       [KosController::class, 'destroy'])->name('kos.destroy');
    Route::get('/kos/populer',       [KosController::class, 'populer'])->name('kos.populer');

    // Review routes
    Route::get('/kos/{id}/review/create', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review',                [ReviewController::class, 'store'])->name('review.store');
    Route::get('/kos/{id}/reviews',       [ReviewController::class, 'show'])->name('review.show');
    Route::get('/pemilik/reviews',        [ReviewController::class, 'ownerReviews'])->name('review.owner');
    Route::get('/review/{id}/edit',       [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/{id}',            [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id}',         [ReviewController::class, 'destroy'])->name('review.destroy');

    // KosReviewController route (opsional jika berbeda dengan ReviewController)
    Route::get('/kos/{kos_id}/reviews', [KosReviewController::class, 'index'])->name('kos.reviews.index');
});

// Route dummy untuk testing review
Route::get('/review-dummy', function () {
    return view('review.dummy');
})->name('review.dummy');

// 7. Auth scaffolding
require __DIR__.'/auth.php';
