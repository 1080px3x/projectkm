<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/komik', [ComicController::class, 'index'])->name('comics.index');
Route::get('/komik/{slug}', [ComicController::class, 'show'])->name('comics.show');
Route::post('/komik/{comic}/rating', [ComicController::class, 'rate'])->middleware('auth')->name('comics.rate');
Route::get('/komik/{slug}/chapter/{number}', [ChapterController::class, 'show'])
    ->whereNumber('number')->name('chapters.show');

Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.store');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

    Route::get('/pustaka', [LibraryController::class, 'index'])->name('library.index');
    Route::post('/pustaka/{comic}', [LibraryController::class, 'store'])->name('library.store');
    Route::delete('/pustaka/{comic}', [LibraryController::class, 'destroy'])->name('library.destroy');

    Route::get('/profil', [PageController::class, 'profile'])->name('profile');
    Route::put('/profil', [PageController::class, 'updateProfile'])->name('profile.update');
    Route::get('/pengaturan', [PageController::class, 'settings'])->name('settings');
    Route::put('/pengaturan/password', [PageController::class, 'updatePassword'])->name('settings.password');
});

Route::get('/komunitas', [CommunityController::class, 'index'])->name('community.index');
Route::post('/komunitas', [CommunityController::class, 'store'])->name('community.store');

Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
Route::get('/bantuan', [PageController::class, 'help'])->name('help');
