<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    | Dashboard
    */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    | Profile
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    | Calendar
    */
    Route::get('/calendar', function () {
        return view('calendar.index');
    })->name('calendar.index');

    /*
    | Booking – SLOT JAM (HARUS DI ATAS RESOURCE)
    */
    Route::get(
        '/bookings/full-dates',
        [BookingController::class, 'fullDates']
    )->name('bookings.full-dates');

    Route::get(
        '/bookings/available-slots',
        [BookingController::class, 'availableSlots']
    )->name('bookings.slots');

    /*
    | Booking – ACTION TAMBAHAN
    */
    Route::patch(
        '/bookings/{booking}/status',
        [BookingController::class, 'updateStatus']
    )->name('bookings.updateStatus');

    Route::patch(
        '/bookings/{booking}/complete',
        [BookingController::class, 'complete']
    )->name('bookings.complete');

    Route::get(
        '/bookings/{booking}/invoice',
        [BookingController::class, 'invoice']
    )->name('bookings.invoice');

    /*
    | Booking – RESOURCE (PALING BAWAH)
    */
    Route::resource('bookings', BookingController::class);
});

/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
