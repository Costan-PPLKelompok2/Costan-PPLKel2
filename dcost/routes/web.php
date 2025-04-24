<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\ReviewController;

Route::get('/review-dummy', function () {
    return view('review.dummy');
})->name('review.dummy');

Route::middleware('auth')->group(function () {

    // 🔹 Create Review (Form review & rating untuk kos yang pernah ditempati)
    Route::get('/kos/{id}/review/create', [ReviewController::class, 'create'])->name('review.create');

    // 🔹 Store Review (Simpan review dari penyewa)
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');

    // 🔹 Show All Reviews for a Kos (PBI 5 - dilihat oleh penyewa sebelum memutuskan kos)
    Route::get('/kos/{id}/reviews', [ReviewController::class, 'show'])->name('review.show');

    // 🔹 Show Reviews to Pemilik (PBI 6 - pemilik kos lihat ulasan tentang kos miliknya)
    Route::get('/pemilik/reviews', [ReviewController::class, 'ownerReviews'])->name('review.owner');

    // 🔹 Update Review (jika penyewa ingin mengubah review)
    Route::put('/review/{id}', [ReviewController::class, 'update'])->name('review.update');

    // 🔹 Edit Review (Form edit review)
Route::get('/review/{id}/edit', [ReviewController::class, 'edit'])->name('review.edit');

    // 🔹 Delete Review
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

});



require __DIR__.'/auth.php';
