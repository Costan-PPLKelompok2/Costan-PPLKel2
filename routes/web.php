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

// 1. Landing page: daftar kos publik dengan filter
Route::get('/', [KosController::class, 'index'])
     ->name('home.dashboard');

// 2. Dashboard (auth & verified): sama menampilkan daftar kos publik
Route::get('/dashboard', [KosController::class, 'index'])
     ->middleware(['auth','verified'])
     ->name('dashboard');

// 3. Profil user (CRUD profile)
Route::middleware('auth')->group(function () {
    Route::get('/profile',   [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. Kos management (hanya untuk pemilik): daftar milik user, tambah, edit, hapus
Route::middleware(['auth'])->group(function () {
    // Daftar kos milik user
    Route::get('/my-kos', [KosController::class, 'manage'])
         ->name('kos.manage');

    // CRUD milik user
    Route::get('/kos/create',        [KosController::class, 'create'])  ->name('kos.create');
    Route::post('/kos',              [KosController::class, 'store'])   ->name('kos.store');
    Route::get('/kos/{id_kos}/edit', [KosController::class, 'edit'])    ->name('kos.edit');
    Route::put('/kos/{id_kos}',      [KosController::class, 'update'])  ->name('kos.update');
    Route::delete('/kos/{id_kos}',   [KosController::class, 'destroy']) ->name('kos.destroy');
});

// 5. Detail kos publik
Route::get('/kos/{id_kos}', [KosController::class, 'show'])
     ->name('kos.show');

// 6. Redirect helper (jika masih perlu)
Route::get('/redirect', [HomeController::class, 'redirect'])
     ->name('redirect');

// 7. Auth scaffolding (login, register, dll.)
require __DIR__ . '/auth.php';
