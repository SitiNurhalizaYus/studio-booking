<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::post('/bookings', [BookingController::class, 'store'])->middleware('auth');



Route::get('/test-booking', function () {
    $request = new \Illuminate\Http\Request([
        'name' => 'Test Customer',
        'phone' => '08123456789',
        'service_id' => \App\Models\Service::first()->id,
        'booking_date' => '2026-02-15',
        'start_time' => '10:00:00',
        'end_time' => '11:00:00',
        'package' => 'Basic',
    ]);

    return app(BookingController::class)->store($request);
})->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/bookings', function () {
    return view('bookings.index');
})->middleware('auth')->name('bookings.index');

Route::get('/payments', function () {
    return view('payments.index');
})->middleware('auth')->name('payments.index');

Route::get('/customers', function () {
    return view('customers.index');
})->middleware('auth')->name('customers.index');

Route::get('/calendar', function () {
    return view('calendar.index');
})->middleware('auth')->name('calendar.index');

Route::resource('bookings', BookingController::class);

Route::patch(
    '/bookings/{booking}/status',
    [BookingController::class, 'updateStatus']
)->name('bookings.updateStatus');

Route::patch('/bookings/{booking}/complete', [BookingController::class, 'complete'])
    ->name('bookings.complete');
Route::get('/bookings/{booking}/invoice', [BookingController::class, 'invoice'])
    ->name('bookings.invoice');
Route::get('/bookings/create', [BookingController::class, 'create'])
    ->name('bookings.create');

Route::post('/bookings', [BookingController::class, 'store'])
    ->name('bookings.store');

