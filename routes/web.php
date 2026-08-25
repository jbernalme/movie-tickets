<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\ShoppingCartController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/test', function () {
    abort(500);
    throw new \App\Exceptions\PaymentPlatformNotConfiguredException(
        'Tarjeta rechazada',
    );
});

Route::get('/test-env', function () {
    dd(app()->environment(), config('app.env'), env('APP_ENV'));
});

Route::get('/welcome', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('welcome');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/movie/{movie}/{slug?}', [MovieController::class, 'show'])->name(
    'movie.show',
);

Route::get('/booking/{slug?}/{screening}', [
    SeatController::class,
    'select',
])->name('seat.select');

Route::get('movies', [MovieController::class, 'index'])->name('movies');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

Route::get('/reservations', [ReservationController::class, 'index'])
    ->middleware('auth')
    ->name('reservations.index');

// rutas payment
Route::middleware('auth')->group(function () {
    Route::post('payment/process', [PaymentController::class, 'process'])->name(
        'payment.process',
    );
    Route::get('payment/approval', [PaymentController::class, 'approval'])->name(
        'payment.approval',
    );
    Route::get('payment/cancelled', [PaymentController::class, 'cancelled'])->name(
        'payment.cancelled',
    );
    Route::get('payment/pending', [PaymentController::class, 'pending'])->name(
        'payment.pending',
    );
});

Route::post('checkout', [CheckoutController::class, 'index'])->name(
    'checkout.index',
);

require __DIR__ . '/settings.php';
