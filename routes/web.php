<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
use Illuminate\Support\Facades\Route;

 
Route::get('/', [HomeController::class, 'HomePage'])->name('HomePage');
// Route pour la liste des résidences
Route::get('/residences', [ResidenceController::class, 'index'])->name('residences.index');

// Route pour afficher les détails d'une résidence spécifique
Route::get('/residences/{residence}', [HomeController::class, 'detailsAppart'])->name('residences.detailsAppart');

Route::get('/detailsAppart', function () {
    return view('Pages/detailsAppart');
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
