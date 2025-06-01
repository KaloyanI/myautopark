<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;

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

    // Car routes
    Route::resource('cars', CarController::class);
    
    // Custom car routes
    Route::get('cars/{car}/availability', [CarController::class, 'checkAvailability'])->name('cars.availability');
    Route::get('api/cars/available', [CarController::class, 'getAvailableCars'])->name('api.cars.available');
});

require __DIR__.'/auth.php';
