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

require __DIR__.'/auth.php';

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
