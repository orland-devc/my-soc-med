<?php

use App\Http\Controllers\InboxController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/like', [PostController::class, 'toggle'])->name('posts.like');
    Route::get('/posts', [PostController::class, 'index'])->name('posts');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('/new', [PostController::class, 'create'])->name('new');

    Route::get('/inbox', [InboxController::class, 'index'])->name('inbox');

    Route::get('/search', [PostController::class, 'search'])->name('search');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // Route::get('/user/{id}', function ($id) {
    //     return view('user.profile', ['userId' => $id]);
    // })->name('user.profile');

    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::post('/updateProfilePhoto', [UserController::class, 'updateProfilePhoto'])->name('user.update_picture');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::fallback(fn() => response()->view('not-found', [], 404));

require __DIR__.'/auth.php';