<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/posts', [PostController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('posts');

Route::post('/posts', [PostController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('posts.store');

Route::get('/posts/{id}', [PostController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('posts.show');

Route::post('/posts/{post}/like', [PostController::class, 'toggle'])
    ->middleware(['auth', 'verified'])
    ->name('posts.like');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
