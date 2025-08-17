<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\auth\SocialiteController;
use Illuminate\Support\Facades\Route;
use App\Models\Residence;

// Route pour la page d'accueil avec les résidences
Route::get('/', function () {
    $residences = Residence::with(['images', 'types'])->get();
    return view('welcomes', compact('residences'));
});

// Routes d'authentification sociale avec Google
Route::get('/auth/google/redirect', [SocialiteController::class, 'redirect'])
    ->name('socialite.google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback'])
    ->name('socialite.google.callback');

// Routes pour l'affichage des pages (requêtes GET)
Route::get('/residences', [ResidenceController::class, 'index'])->name('residences.index');
Route::get('/detailsAppart', function () {
    return view('Pages/detailsAppart');
});

// Route pour la page des favoris
Route::get('/favoris', function () {
    return view('favorites.favorites');
})->name('favorites.index');

// Route pour l'affichage des détails d'un appartement (page publique)
Route::get('/residences/{residence}', [ResidenceController::class, 'detailsAppart'])
    ->name('residences.detailsAppart');

// Route pour la page de réservation (qui affiche le formulaire, GET)
Route::post('/residences/{residence}/bookguest', [ResidenceController::class, 'bookguest']);

// Route pour la recherche d'appartements via une API (requête POST)
Route::post('/api/search-apartments', [ResidenceController::class, 'search'])->name('api.apartments.search');

// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Routes de profil utilisateur
    Route::get('/homeUser', [ProfileController::class, 'homeUser'])->name('profile.homeUser');
    // Route pour la page de réservation (qui affiche le formulaire, GET)
    Route::post('/residences/{residence}/reserver', [BookingController::class, 'reserver'])
        ->name('residences.reserver');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route pour soumettre le formulaire de réservation (requête POST)
    // Route::post('/residences/{residence}/book', [BookingController::class, 'store'])->name('residences.book');

    // Route pour la gestion des favoris
    Route::get('/favorites', [ResidenceController::class, 'index'])->name('favorites.index');
    Route::get('/favoris', [ResidenceController::class, 'favoris'])->name('residences.favorites');
});

// Routes pour les réservations d'invités (requête POST)
Route::post('/bookings/guest', [BookingController::class, 'storeGuestBooking'])->name('residences.bookguest');

// Routes du tableau de bord
Route::get('/dashboards', function () {
    $residences = Residence::with(['images', 'types'])->get();
    return view('dashboards', compact('residences'));
})->middleware(['auth', 'verified'])->name('dashboards');

// Routes d'authentification (générées par Laravel Breeze/Jetstream)
require __DIR__ . '/auth.php';
require __DIR__ . '/admins-auth.php';

// Route de secours (Fallback) pour les URLs non trouvées
Route::fallback(function () {
    if (auth()->guard('admin')->check()) {
        return redirect('/admin/dashboard');
    }
    return redirect('/');
});
