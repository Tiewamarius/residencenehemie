<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidenceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaiementController;
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



// Route pour la recherche d'appartements via une (requête POST)
Route::get('search-apartments', [ResidenceController::class, 'searchAppart'])->name('search-apartments.search');

Route::post('/residences/search', [ResidenceController::class, 'search'])->name('residences.search');

// === Routes pour user non connecté ===

// Créer une réservation en tant qu'invité
Route::post('/residences/{residence}/guestReserver', [BookingController::class, 'guestReserver'])
    ->name('residences.guestReserver');

// Route correcte pour afficher la page de paiement d'une réservation
Route::get('/paiement/{booking}/guestReserver', [PaiementController::class, 'showGuestPaymentPage'])
    ->name('paiements.showGuest');

// Nouvelle route pour traiter le paiement
Route::post('/paiements/finaliser', [PaiementController::class, 'finaliser'])->name('paiements.finaliser');



// Details
Route::get('/bookings/{booking}', [BookingController::class, 'details'])->name('bookings.details');




// Routes protégées par l'authentification
Route::middleware('auth')->group(function () {
    // Routes de profil utilisateur
    Route::get('/homeUser', [ProfileController::class, 'homeUser'])->name('profile.homeUser');

    // Route pour la page de réservation (qui affiche le formulaire, )
    Route::post('/residences/{residence}/reserver', [BookingController::class, 'reserver'])
        ->name('residences.reserver');

    // Route correcte pour afficher la page de paiement d'une réservation
    Route::get('/paiement/{booking}', [PaiementController::class, 'showPaymentPage'])
        ->name('paiements.show');

    // Nouvelle route pour traiter le paiement
    Route::post('/paiement/process', [PaiementController::class, 'process'])
        ->name('paiements.process');


    // Details
    Route::get('/bookings/{booking}', [BookingController::class, 'details'])->name('bookings.details');

    // Facture : page HTML ou PDF
    Route::get('/bookings/{id}/invoice', [BookingController::class, 'invoice'])
        ->name('bookings.invoice');

    // Annulation de réservation
    Route::patch('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');

    // Check-in
    Route::get('/bookings/{id}/checkin', [BookingController::class, 'checkin'])
        ->name('bookings.checkin');

    // Route pour la page de confirmation de succès
    Route::get('/paiement/success', function () {
        return view('Pages.success'); // Créez une vue 'success.blade.php' pour cette page
    })->name('paiements.success');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route pour soumettre le formulaire de réservation (requête POST)
    // Route::post('/residences/{residence}/book', [BookingController::class, 'store'])->name('residences.book');

    // Route pour la gestion des favoris
    Route::post('/favorites/add/{residence}', [ResidenceController::class, 'storefavoris'])->name('favorites.storefavoris');

    // Mise à jour de la route pour qu'elle pointe vers la méthode correcte
    Route::delete('/favorites/remove/{residence}', [ResidenceController::class, 'destroyfavoris'])->name('favorites.deletefavoris');
});

// Routes pour les réservations d'invités (requête POST)
// Route::post('/bookings/guest', [BookingController::class, 'storeGuestBooking'])->name('residences.bookguest');

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
