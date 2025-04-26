<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


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
Route::get('/',[HomeController::class,"index"]);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('profile')->middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'show'])->name('profile.show');
    Route::get('/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/update', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/delete', [UserController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/redirect',[HomeController::class,"redirect"]);

require __DIR__.'/auth.php';
