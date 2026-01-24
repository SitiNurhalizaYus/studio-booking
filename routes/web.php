<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');


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



Route::middleware('auth')->group(function () {

    // PAYMENT INDEX
    Route::get('/payments', [PaymentController::class, 'index'])
        ->name('payments.index');

    // PAYMENT DETAIL
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])
        ->name('payments.show');

    // EDIT PEMBAYARAN (DP / LUNAS)
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])
        ->name('payments.edit');

    // UPDATE PEMBAYARAN
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])
        ->name('payments.update');

    // INVOICE
    Route::get('/payments/{payment}/invoice', [PaymentController::class, 'invoice'])
    ->name('payments.invoice');
});

Route::get('/reports/finance', [PaymentController::class, 'financeReport'])
    ->name('reports.finance');


Route::get('/bookings/{booking}/whatsapp', 
    [BookingController::class, 'sendWhatsapp']
)->name('bookings.whatsapp');

Route::get(
    '/bookings/{booking}/reminder',
    [BookingController::class, 'sendReminder']
)->name('bookings.reminder');



Route::resource('services', ServiceController::class);



/*
|--------------------------------------------------------------------------
| Auth routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
