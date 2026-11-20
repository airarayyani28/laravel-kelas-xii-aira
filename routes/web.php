<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KritikController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

Route::get('/', [FilmController::class, 'movieHome'])->name('home');
Route::get('/movies', [FilmController::class, 'movies'])->name('movies');
Route::get('/movies/{film}', [FilmController::class, 'show'])->name('movies.show');
Route::get('/movies/genre/{genre}', [FilmController::class, 'moviesByGenre'])->name('genre');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/movies/{film}/kritik', [KritikController::class, 'store'])->middleware('auth')->name('kritik.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route Dashboard & CRUD Administrator
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // Genre
        Route::post('/genres', [AdminController::class, 'storeGenre'])->name('genres.store');
        Route::put('/genres/{genre}', [AdminController::class, 'updateGenre'])->name('genres.update');
        Route::delete('/genres/{genre}', [AdminController::class, 'destroyGenre'])->name('genres.destroy');

        // Cast
        Route::post('/casts', [AdminController::class, 'storeCast'])->name('casts.store');
        Route::put('/casts/{cast}', [AdminController::class, 'updateCast'])->name('casts.update');
        Route::delete('/casts/{cast}', [AdminController::class, 'destroyCast'])->name('casts.destroy');

        // Film
        Route::post('/films', [AdminController::class, 'storeFilm'])->name('films.store');
        Route::put('/films/{film}', [AdminController::class, 'updateFilm'])->name('films.update');
        Route::delete('/films/{film}', [AdminController::class, 'destroyFilm'])->name('films.destroy');

        // Peran
        Route::post('/perans', [AdminController::class, 'storePeran'])->name('perans.store');
        Route::put('/perans/{peran}', [AdminController::class, 'updatePeran'])->name('perans.update');
        Route::delete('/perans/{peran}', [AdminController::class, 'destroyPeran'])->name('perans.destroy');
    });
});