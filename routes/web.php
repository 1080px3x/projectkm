<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ChapterController as AdminChapterController;
use App\Http\Controllers\Admin\ChapterImageController as AdminChapterImageController;
use App\Http\Controllers\Admin\ComicController as AdminComicController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\Admin\ThreadController as AdminThreadController;
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

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store']);
    });

    Route::middleware('admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('comics', [AdminComicController::class, 'index'])->name('comics.index');
        Route::get('comics/create', [AdminComicController::class, 'create'])->name('comics.create');
        Route::post('comics', [AdminComicController::class, 'store'])->name('comics.store');
        Route::get('comics/{comic}/edit', [AdminComicController::class, 'edit'])->name('comics.edit');
        Route::put('comics/{comic}', [AdminComicController::class, 'update'])->name('comics.update');
        Route::delete('comics/{comic}', [AdminComicController::class, 'destroy'])->name('comics.destroy');

        Route::post('comics/{comic}/chapters', [AdminChapterController::class, 'store'])->name('chapters.store');
        Route::put('chapters/{chapter}', [AdminChapterController::class, 'update'])->name('chapters.update');
        Route::delete('chapters/{chapter}', [AdminChapterController::class, 'destroy'])->name('chapters.destroy');
        Route::get('chapters/{chapter}/images', [AdminChapterImageController::class, 'index'])->name('chapters.images');
        Route::post('chapters/{chapter}/images', [AdminChapterImageController::class, 'store'])->name('chapters.images.store');
        Route::delete('chapter-images/{image}', [AdminChapterImageController::class, 'destroy'])->name('chapter-images.destroy');

        Route::get('genres', [AdminGenreController::class, 'index'])->name('genres.index');
        Route::post('genres', [AdminGenreController::class, 'store'])->name('genres.store');
        Route::delete('genres/{genre}', [AdminGenreController::class, 'destroy'])->name('genres.destroy');

        Route::get('threads', [AdminThreadController::class, 'index'])->name('threads.index');
        Route::delete('threads/{thread}', [AdminThreadController::class, 'destroy'])->name('threads.destroy');
    });
});
