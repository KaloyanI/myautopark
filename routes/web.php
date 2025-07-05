<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CarExpenseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    if (auth()->check()) {
        return view('welcome');
    }
    return redirect()->route('login');
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

    Route::post('/layout-preference', function (Request $request) {
        \App\Helpers\LayoutHelper::setLayoutPreference($request->view, $request->layout);
        return back();
    })->name('layout.toggle');

    // Car Expenses Routes
    Route::get('/cars/{car}/expenses', [CarExpenseController::class, 'index'])->name('cars.expenses.index');
    Route::get('/cars/{car}/expenses/create', [CarExpenseController::class, 'create'])->name('cars.expenses.create');
    Route::post('/cars/{car}/expenses', [CarExpenseController::class, 'store'])->name('cars.expenses.store');
    Route::get('/cars/{car}/expenses/{expense}', [CarExpenseController::class, 'show'])->name('cars.expenses.show');
    Route::get('/cars/{car}/expenses/{expense}/edit', [CarExpenseController::class, 'edit'])->name('cars.expenses.edit');
    Route::put('/cars/{car}/expenses/{expense}', [CarExpenseController::class, 'update'])->name('cars.expenses.update');
    Route::delete('/cars/{car}/expenses/{expense}', [CarExpenseController::class, 'destroy'])->name('cars.expenses.destroy');
    Route::get('/cars/{car}/expenses/{expense}/documents/{index}', [CarExpenseController::class, 'downloadDocument'])->name('cars.expenses.document.download');
});

require __DIR__.'/auth.php';
