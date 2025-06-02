<?php

use App\Helpers\LocationHelper;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OwnerReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Public landing & listing
Route::get('/', [HomeController::class, 'redirect'])->name('redirect');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/daftar-kos', [HomeController::class, 'daftarKos'])->name('home.daftarkos');

// 2. Public kos listing
Route::get('/kos', [KosController::class, 'index'])
     ->name('kos.index');

// 3. Full‐filter search page
Route::get('/kos/search', [KosController::class, 'search'])
     ->name('kos.search');

// 4. Public detail view (only numeric IDs)
Route::get('/kos/{id_kos}', [KosController::class, 'show'])
     ->whereNumber('id_kos')
     ->name('kos.show');

// 5. Dashboard redirect shortcut (authenticated & verified)
Route::get('/dashboard', function () {
    return redirect()->route('redirect');
})->middleware(['auth', 'verified'])->name('dashboard');

// 6. Authentication (register / login / password / email verification)
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

Route::get('/verify-email', EmailVerificationPromptController::class)->name('verification.notice');
Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
     ->middleware('signed')
     ->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
     ->name('verification.send');

// 7. All routes that require a logged-in user
Route::middleware('auth')->group(function () {
    // Admin dashboard
    Route::get('/admin', [PemilikController::class, 'index'])->name('admin.index');

    // Profile
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kos management
    Route::get('/kos',               [KosController::class, 'index'])->name('kos.index');
    Route::get('/my-kos',            [KosController::class, 'manage'])->name('kos.manage');
    Route::get('/kos/create',        [KosController::class, 'create'])->name('kos.create');
    Route::get('/kos/populer',       [KosController::class, 'populer'])->name('kos.populer');
    Route::get('/kos/{id}',          [KosController::class, 'show'])->name('kos.show');
    Route::post('/kos',              [KosController::class, 'store'])->name('kos.store');
    Route::get('/kos/{id}/edit',     [KosController::class, 'edit'])->name('kos.edit');
    Route::put('/kos/{id}',          [KosController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{id}',       [KosController::class, 'destroy'])->name('kos.destroy');

    // Pemilik dashboard
    Route::get('/pemilik/dashboard', [PemilikController::class, 'index'])->name('pemilik.dashboard');
    Route::get('/pemilik/statistik/{id}', [PemilikController::class, 'statistik'])->name('pemilik.statistik');

    // Review routes
    Route::get('/kos/{id}/review/create', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review',                [ReviewController::class, 'store'])->name('review.store');
    Route::get('/kos/{id}/reviews',       [ReviewController::class, 'show'])->name('review.show');
    Route::get('/pemilik/reviews',        [ReviewController::class, 'ownerReviews'])->name('review.owner');
    Route::get('/review/{id}/edit',       [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/{id}',            [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id}',         [ReviewController::class, 'destroy'])->name('review.destroy');

    Route::get('/owner-review/{ownerId}', [OwnerReviewController::class, 'create'])->name('owner-reviews.create');
    Route::post('/owner-review', [OwnerReviewController::class, 'store'])->name('owner-reviews.store');
    Route::get('/owner-review/show/{ownerId}', [OwnerReviewController::class, 'show'])->name('owner-reviews.show');

    Route::get('/owner-reviews/{id}/edit', [OwnerReviewController::class, 'edit'])->name('owner-reviews.edit');
    Route::put('/owner-reviews/{id}', [OwnerReviewController::class, 'update'])->name('owner-reviews.update');
    Route::delete('/owner-reviews/{id}', [OwnerReviewController::class, 'destroy'])->name('owner-reviews.destroy');
});

// 8. A dummy review page for testing
Route::get('/review-dummy', function () {
    return view('review.dummy');
})->name('review.dummy');

// 9. Optional redirect helper
Route::get('/redirect', [HomeController::class, 'redirect'])->name('redirect');

// 10. Non-auth user profile resource
Route::resource('user_profile', UsersController::class);
