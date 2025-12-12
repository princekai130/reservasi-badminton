<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/test-ping', function () {
    return "Routing berhasil dimuat! Masalah ada di Controller atau Model.";
});

Route::get('/', function () {
    return view('layouts.app');
});

// Rute PUBLIC untuk melihat daftar lapangan
Route::get('/fields', [FieldController::class, 'index'])->name('fields.index');

// Rute untuk USER (auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $fields = \App\Models\Field::all();
        return view('dashboard', compact('fields'));
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::get('/booking/create/{field}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/history', [BookingController::class, 'userHistory'])->name('user.history');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rute untuk ADMIN (auth + admin check)
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('fields', App\Http\Controllers\Admin\FieldController::class);
    Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);
    
    // Laporan pendapatan (booking)
    Route::get('reports', [App\Http\Controllers\Admin\BookingManagementController::class, 'reportIndex'])->name('reports.index');

    // opsi: route aksi konfirmasi / batal (jika controller pakai method confirm/cancel)
    Route::patch('bookings/{booking}/confirm', [App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('bookings/{booking}/cancel', [App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
});

// DEBUG TEMP: buka admin bookings tanpa middleware untuk cek view keberadaan
Route::get('/debug-admin-bookings', function () {
    return view('admin.bookings.index');
});

require __DIR__.'/auth.php';
