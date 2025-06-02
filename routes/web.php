<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    UsersController,
    ProfileController,
    KosController,
    ReviewController,
    OwnerReviewController,
    ChatController,
    FavoriteController,
    Auth\RegisteredUserController,
    Auth\AuthenticatedSessionController,
    Auth\PasswordResetLinkController,
    Auth\NewPasswordController,
    Auth\EmailVerificationPromptController,
    Auth\VerifyEmailController,
    Auth\EmailVerificationNotificationController
};

// 1. Public landing & listing
Route::get('/', [HomeController::class, 'redirect'])->name('redirect');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::get('/daftar-kos', [HomeController::class, 'daftarKos'])->name('home.daftarkos');

// 2. Public kos listing
Route::get('/kos', [KosController::class, 'index'])->name('kos.index');

// 3. Full‐filter search page
Route::get('/kos/search', [KosController::class, 'search'])->name('kos.search');

// 4. Public detail view (only numeric IDs)
Route::get('/kos/{id_kos}', [KosController::class, 'show'])->whereNumber('id_kos')->name('kos.show');

// 5. Dashboard redirect shortcut
Route::get('/dashboard', fn () => redirect()->route('redirect'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 6. Authentication
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
Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware('signed')->name('verification.verify');
Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('verification.send');

// 7. Protected routes
Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::post('/profile/set-role', [ProfileController::class, 'setRole'])->name('profile.setRole');

    // Kos Management
    Route::get('/my-kos', [KosController::class, 'manage'])->name('kos.manage');
    Route::get('/kos/create', [KosController::class, 'create'])->name('kos.create');
    Route::get('/kos/populer', [KosController::class, 'populer'])->name('kos.populer');
    Route::get('/kos/{id}/edit', [KosController::class, 'edit'])->name('kos.edit');
    Route::post('/kos', [KosController::class, 'store'])->name('kos.store');
    Route::put('/kos/{id}', [KosController::class, 'update'])->name('kos.update');
    Route::delete('/kos/{id}', [KosController::class, 'destroy'])->name('kos.destroy');
    Route::get('/kos/{kosId}/initiate-chat', [KosController::class, 'initiateChatWithOwner'])->name('kos.initiateChat');

    // Review
    Route::get('/kos/{id}/review/create', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/kos/{id}/reviews', [ReviewController::class, 'show'])->name('review.show');
    Route::get('/pemilik/reviews', [ReviewController::class, 'ownerReviews'])->name('review.owner');
    Route::get('/review/{id}/edit', [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/{id}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

    // Owner Review
    Route::get('/owner-review/{ownerId}', [OwnerReviewController::class, 'create'])->name('owner-reviews.create');
    Route::post('/owner-review', [OwnerReviewController::class, 'store'])->name('owner-reviews.store');
    Route::get('/owner-review/show/{ownerId}', [OwnerReviewController::class, 'show'])->name('owner-reviews.show');
    Route::get('/owner-reviews/{id}/edit', [OwnerReviewController::class, 'edit'])->name('owner-reviews.edit');
    Route::put('/owner-reviews/{id}', [OwnerReviewController::class, 'update'])->name('owner-reviews.update');
    Route::delete('/owner-reviews/{id}', [OwnerReviewController::class, 'destroy'])->name('owner-reviews.destroy');

    // Favorites
    Route::get('/favorit', [FavoriteController::class, 'index'])->name('kos.favorites'); // Add this line

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chatRoom}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chatRoom}/messages', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/notifications/count', [ChatController::class, 'getNotificationCount'])->name('chat.notifications.count');
    Route::put('/chat/messages/{message}', [ChatController::class, 'updateMessage'])->name('chat.message.update');
    Route::delete('/chat/messages/{message}', [ChatController::class, 'destroyMessage'])->name('chat.message.destroy');
});

// Chat API (authenticated)
Route::middleware('auth:sanctum')->prefix('chat')->name('api.chat.')->group(function () {
    Route::get('/rooms', [ChatController::class, 'getChatRooms']);
    Route::get('/messages/{chatRoom}', [ChatController::class, 'getMessages']);
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/notification-count', [ChatController::class, 'getNotificationCount'])->name('notificationCount');
    Route::post('/mark-room-read/{chatRoom}', [ChatController::class, 'markRoomAsRead']);
    Route::post('/notifications/mark-read', [ChatController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::delete('/delete-room/{chatRoom}', [ChatController::class, 'deleteRoom']);
});

// 8. Dummy page for testing
Route::get('/review-dummy', fn () => view('review.dummy'))->name('review.dummy');

// 9. Redirect helper
Route::get('/redirect', [HomeController::class, 'redirect'])->name('redirect');

// 10. Public user profile resource
Route::resource('user_profile', UsersController::class);
