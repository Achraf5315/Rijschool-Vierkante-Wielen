<?php

use App\Http\Controllers\DrivingPackageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AutoController;
use App\Http\Controllers\InstructeurController;
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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auto routes (Admin en Instructeur kunnen toevoegen/bewerken/verwijderen)
|--------------------------------------------------------------------------
*/
// Public routes
Route::get('/autos', [AutoController::class, 'index'])->name('autos.index');
Route::get('/autos/{id}', [AutoController::class, 'show'])->name('autos.show');

// Protected routes (auth + admin/instructor)
Route::middleware(['auth', 'role:admin,instructor'])->group(function () {
    Route::get('/autos/create', [AutoController::class, 'create'])->name('autos.create');
    Route::post('/autos', [AutoController::class, 'store'])->name('autos.store');
    Route::get('/autos/{id}/edit', [AutoController::class, 'edit'])->name('autos.edit');
    Route::put('/autos/{id}', [AutoController::class, 'update'])->name('autos.update');
    Route::delete('/autos/{id}', [AutoController::class, 'destroy'])->name('autos.destroy');
});

/*
|--------------------------------------------------------------------------
| Instructeur routes (Alleen Admin kan toevoegen/bewerken/verwijderen)
|--------------------------------------------------------------------------
*/
// Public routes (index en show voor admin en instructeur)
Route::middleware(['auth', 'role:admin,instructor'])->group(function () {
    Route::get('/instructeurs', [InstructeurController::class, 'index'])->name('instructeurs.index');
    Route::get('/instructeurs/{id}', [InstructeurController::class, 'show'])->name('instructeurs.show');
});

// Protected routes (alleen Admin kan aanmaken/bewerken/verwijderen)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/instructeurs/create', [InstructeurController::class, 'create'])->name('instructeurs.create');
    Route::post('/instructeurs', [InstructeurController::class, 'store'])->name('instructeurs.store');
    Route::get('/instructeurs/{id}/edit', [InstructeurController::class, 'edit'])->name('instructeurs.edit');
    Route::put('/instructeurs/{id}', [InstructeurController::class, 'update'])->name('instructeurs.update');
    Route::delete('/instructeurs/{id}', [InstructeurController::class, 'destroy'])->name('instructeurs.destroy');
});

require __DIR__.'/auth.php';
