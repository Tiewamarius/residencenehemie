<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
use Illuminate\Support\Facades\Route;
use App\Models\Residence;

Route::get('/', function () {
    $residences = Residence::with(['images', 'types'])->get();

    return view('welcomes',compact('residences'));
});

// Route pour la liste des résidences
Route::get('/residences', [ResidenceController::class, 'index'])->name('residences.index');

Route::get('/residences/{residence}', [ResidenceController::class, 'detailsAppart'])->name('residences.detailsAppart');

Route::get('/detailsAppart', function () {
    return view('Pages/detailsAppart');
});

Route::get('/favoris', [ResidenceController::class, 'favoris'])->name('residences.favorites');

Route::get('/favoris', function () {
    return view('favorites.favorites'); // Assurez-vous que vous avez un fichier 'resources/views/favorites/index.blade.php'
})->name('favorites.index');


Route::get('/dashboards', function () {
    $residences = Residence::with(['images', 'types'])->get();

    return view('dashboards',compact('residences'));
})->middleware(['auth', 'verified'])->name('dashboards');


Route::middleware('auth')->group(function () {
    Route::get('/homeUser', [ProfileController::class, 'homeUser'])->name('profile.homeUser');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Route de secours (Fallback) ---

Route::fallback(function () {
    // Vérifie si un utilisateur est authentifié avec le guard 'admin'
    if (auth()->guard('admin')->check()) {
        return redirect('/admin/dashboard');
    }

    // Par défaut, redirige vers la page d'accueil
    return redirect('/');
});
require __DIR__.'/auth.php';
require __DIR__.'/admins-auth.php';
