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

// Halaman landing: daftar kos (mengirim $kosList)
Route::get('/', [KosController::class, 'index'])
     ->name('home.dashboard');

// Halaman dashboard (protected): juga daftar kos
Route::get('/dashboard', [KosController::class, 'index'])
     ->middleware(['auth','verified'])
     ->name('dashboard');

// CRUD profile (auth only)
Route::middleware('auth')->group(function () {
    Route::get('/profile',   [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update']) ->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Resource controller untuk Kos
Route::resource('kos', KosController::class)
     ->parameters(['kos' => 'id_kos'])
     ->names([
         'index'   => 'kos.index',
         'create'  => 'kos.create',
         'store'   => 'kos.store',
         'show'    => 'kos.show',
         'edit'    => 'kos.edit',
         'update'  => 'kos.update',
         'destroy' => 'kos.destroy',
     ]);

// Rute tambahan untuk redirect (jika masih dipakai)
Route::get('/redirect', [HomeController::class, 'redirect'])
     ->name('redirect');

// Auth scaffolding (login, register, dll.)
require __DIR__ . '/auth.php';
