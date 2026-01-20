<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/welcome', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('welcome');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('movies', [MovieController::class, 'index'])->name('movies');

Route::get('movies/{movie}/{slug}', [MovieController::class, 'show'])->name(
    'movie.show',
);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__ . '/settings.php';
