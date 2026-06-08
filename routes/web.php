<?php

use App\Http\Controllers\DrivingLessonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('rijlessen')->name('driving-lessons.')->group(function () {
        Route::get('/', [DrivingLessonController::class, 'index'])->name('index');
        Route::get('/toevoegen', [DrivingLessonController::class, 'create'])->name('create');
        Route::post('/', [DrivingLessonController::class, 'store'])->name('store');
        Route::get('/{lesson}/wijzigen', [DrivingLessonController::class, 'edit'])->name('edit');
        Route::get('/{lesson}/verwijderen', [DrivingLessonController::class, 'destroy'])->name('destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
