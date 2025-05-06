<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\KosReviewController; // Tambahkan ini
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[HomeController::class,"index"])->name('dashboard');

Route::get('/dashboard', function () {
    return route('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// Default setelah login langsung ke /kos

// Semua route ini butuh user sudah login
Route::middleware(['auth'])->group(function () {
    
    // Dashboard utama setelah login (kalau tetap mau dashboard)
    Route::get('/dashboard', function () {
        return view('home.dashboard');
    })->name('dashboard');

    // Route kelola kos
    Route::get('/kos', [KosController::class, 'index'])->name('kos.index');
    Route::get('/kos/create', [KosController::class, 'create'])->name('kos.create');
    Route::post('/kos', [KosController::class, 'store'])->name('kos.store');
    Route::get('/kos/{id}/edit', [KosController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{id}', [KosController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{id}', [KosController::class, 'destroy'])->name('kos.destroy');
    Route::get('/kos/populer', [KosController::class, 'populer'])->name('kos.populer');
    Route::get('/kos/show/{id}', [KosController::class, 'show'])->name('kos.show');

    // Route untuk review kos
    Route::get('/kos/{kos_id}/reviews', action: [KosReviewController::class, 'index'])->name('kos.reviews.index'); // Tambahkan ini
    // Route::resource('kos.reviews', KosReviewController::class); // Opsi lain, tapi mungkin berlebihan

    // Route untuk profile user
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
