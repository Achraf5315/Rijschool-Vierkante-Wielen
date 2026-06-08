<?php

use App\Http\Controllers\DrivingLessonController;
use App\Http\Controllers\DrivingPackageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Publieke routes (gastgebruiker)
|--------------------------------------------------------------------------
*/

// Homepagina met bedrijfsinfo en voorbeeld-lesrijpakketten.
Route::get('/', [HomeController::class, 'index'])->name('home');

// Openbaar overzicht van alle beschikbare lesrijpakketten.
Route::get('/lesrijpakketten', [DrivingPackageController::class, 'index'])
    ->name('driving-packages.index');

/*
|--------------------------------------------------------------------------
| Beheer-routes lesrijpakketten (administrator en instructeur)
|--------------------------------------------------------------------------
| Eigen beheerpagina + volledige CRUD voor het toevoegen, bewerken en
| verwijderen van pakketten. Afgeschermd met auth + `role:admin,instructor`.
*/
Route::middleware(['auth', 'role:admin,instructor'])
    ->prefix('lesrijpakketten')
    ->name('driving-packages.')
    ->group(function () {
        // Aparte beheerpagina "Lesrijpakketten beheren".
        Route::get('/beheren', [DrivingPackageController::class, 'manage'])->name('manage');
        Route::get('/toevoegen', [DrivingPackageController::class, 'create'])->name('create');
        Route::post('/', [DrivingPackageController::class, 'store'])->name('store');
        Route::get('/{drivingPackage}/bewerken', [DrivingPackageController::class, 'edit'])->name('edit');
        Route::put('/{drivingPackage}', [DrivingPackageController::class, 'update'])->name('update');
        Route::delete('/{drivingPackage}', [DrivingPackageController::class, 'destroy'])->name('destroy');
    });

/*
|--------------------------------------------------------------------------
| Ingelogde gebruikers
|--------------------------------------------------------------------------
*/

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
