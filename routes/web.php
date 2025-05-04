<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda mendefinisikan rute aplikasi web. Rute-rute ini
| akan dimuat oleh RouteServiceProvider dan berada dalam grup
| "web" middleware.
|
*/

// 1. Landing page & public kos listing (halaman utama)
Route::get('/', [KosController::class, 'index'])
     ->name('home.dashboard');

// 2. Daftar umum kos (kos.index)
Route::get('/kos', [KosController::class, 'index'])
     ->name('kos.index');

// 3. Halaman khusus pencarian dengan filter lengkap
Route::get('/kos/search', [KosController::class, 'search'])
     ->name('kos.search');

// 4. Detail kos publik (harus setelah /kos/search)
Route::get('/kos/{id_kos}', [KosController::class, 'show'])
     ->name('kos.show');

// 5. Dashboard protected (auth & verified)
Route::get('/dashboard', [KosController::class, 'index'])
     ->middleware(['auth','verified'])
     ->name('dashboard');

// 6. Rute-rute yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Profil user
    Route::get('/profile',   [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // Manajemen kos milik user
    Route::get('/my-kos',              [KosController::class, 'manage']) ->name('kos.manage');
    Route::get('/kos/create',          [KosController::class, 'create']) ->name('kos.create');
    Route::post('/kos',                [KosController::class, 'store'])  ->name('kos.store');
    Route::get('/kos/{id_kos}/edit',   [KosController::class, 'edit'])   ->name('kos.edit');
    Route::put('/kos/{id_kos}',        [KosController::class, 'update']) ->name('kos.update');
    Route::delete('/kos/{id_kos}',     [KosController::class, 'destroy'])->name('kos.destroy');
});

// 7. (Optional) Redirect helper
Route::get('/redirect', [HomeController::class, 'redirect'])
     ->name('redirect');

// 8. Auth scaffolding (login, register, dll.)
require __DIR__ . '/auth.php';
