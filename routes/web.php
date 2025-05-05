<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Public landing & listing
Route::get('/', [KosController::class, 'index'])
     ->name('home.dashboard');

Route::get('/kos', [KosController::class, 'index'])
     ->name('kos.index');

// 2. Full‐filter search page
Route::get('/kos/search', [KosController::class, 'search'])
     ->name('kos.search');

// 3. All authenticated routes
Route::middleware('auth')->group(function () {
    // profile
    Route::get('/profile',   [ProfileController::class, 'edit'])   ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // user’s own kos management
    Route::get('/my-kos',            [KosController::class, 'manage']) ->name('kos.manage');
    Route::get('/kos/create',        [KosController::class, 'create']) ->name('kos.create');
    Route::post('/kos',              [KosController::class, 'store'])  ->name('kos.store');
    Route::get('/kos/{id_kos}/edit', [KosController::class, 'edit'])   ->name('kos.edit');
    Route::put('/kos/{id_kos}',      [KosController::class, 'update']) ->name('kos.update');
    Route::delete('/kos/{id_kos}',   [KosController::class, 'destroy'])->name('kos.destroy');
});

// 4. Public detail (only numeric IDs)
Route::get('/kos/{id_kos}', [KosController::class, 'show'])
     ->whereNumber('id_kos')
     ->name('kos.show');

// 5. Protected dashboard shortcut
Route::get('/dashboard', [KosController::class, 'index'])
     ->middleware(['auth','verified'])
     ->name('dashboard');

// 6. Optional redirect helper
Route::get('/redirect', [HomeController::class, 'redirect'])
     ->name('redirect');

// 7. Auth scaffolding
require __DIR__ . '/auth.php';
