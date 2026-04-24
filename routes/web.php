<?php

use App\Http\Controllers\Admin\WorkshopController as AdminWorkshopController;
use App\Http\Controllers\Employee\WorkshopController as EmployeeWorkshopController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::prefix('admin/workshops')->name('admin.workshops.')->group(function () {
        Route::get('/', [AdminWorkshopController::class, 'index'])->name('index');
        Route::get('/create', [AdminWorkshopController::class, 'create'])->name('create');
        Route::post('/', [AdminWorkshopController::class, 'store'])->name('store');
        Route::get('/{workshop}/edit', [AdminWorkshopController::class, 'edit'])->name('edit');
        Route::put('/{workshop}', [AdminWorkshopController::class, 'update'])->name('update');
        Route::delete('/{workshop}', [AdminWorkshopController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware(['auth', 'verified', 'employee'])->group(function () {
    Route::prefix('workshops')->name('workshops.')->group(function () {
        Route::get('/', [EmployeeWorkshopController::class, 'index'])->name('index');
        Route::get('/{workshop}', [EmployeeWorkshopController::class, 'show'])->name('show');
    });

    Route::post('/workshops/{workshop}/register', [RegistrationController::class, 'store'])->name('register');
    Route::delete('/workshops/{workshop}/register', [RegistrationController::class, 'destroy'])->name('unregister');
});

require __DIR__.'/auth.php';