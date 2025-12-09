<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🏠 Halaman utama (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 📊 Dashboard (untuk user login/admin)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 🔒 Routes yang hanya bisa diakses jika sudah login
Route::middleware('auth')->group(function () {

    // ===================== ✂️ BOOKING ROUTES ===================== //

    // Halaman riwayat booking
    Route::get('/bookings', [BookingController::class, 'index'])
        ->name('bookings.index');

    // Form booking multi-step
    Route::get('/booking', [BookingController::class, 'create'])
        ->name('book.create');

    // Proses simpan booking
    Route::post('/booking', [BookingController::class, 'store'])
        ->name('book.store');

    // AJAX: cek slot waktu tersedia berdasarkan barber + tanggal
    Route::get('/booking/available-times', [BookingController::class, 'getAvailableTimes'])
        ->name('booking.available-times');

    // ===================== 👤 PROFILE ROUTES ===================== //

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ===================== 🔑 GOOGLE AUTH ===================== //
Route::prefix('auth/google')->name('auth.google.')->group(function () {
    Route::get('/', [GoogleController::class, 'redirect'])->name('redirect');
    Route::get('/callback', [GoogleController::class, 'callback'])->name('callback');
});


// Default Breeze/Jetstream auth
require __DIR__ . '/auth.php';
