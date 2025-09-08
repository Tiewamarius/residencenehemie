<?php

use App\Http\Controllers\AdminAuth\AdminAuthenticatedSessionController;
use App\Http\Controllers\AdminAuth\AdminConfirmablePasswordController;
use App\Http\Controllers\AdminAuth\AdminEmailVerificationNotificationController;
use App\Http\Controllers\AdminAuth\AdminEmailVerificationPromptController;
use App\Http\Controllers\AdminAuth\AdminNewPasswordController;
use App\Http\Controllers\AdminAuth\AdminController;
use App\Http\Controllers\AdminAuth\AdminPasswordController;
use App\Http\Controllers\AdminAuth\AdminPasswordResetLinkController;
use App\Http\Controllers\AdminAuth\RegisteredAdminController;
use App\Http\Controllers\AdminAuth\AdminVerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('register', [RegisteredAdminController::class, 'create'])
        ->name('admin.register');

    Route::post('register', [RegisteredAdminController::class, 'store']);

    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])
        ->name('admin.login');

    Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [AdminPasswordResetLinkController::class, 'create'])
        ->name('admin.password.request');

    Route::post('forgot-password', [AdminPasswordResetLinkController::class, 'store'])
        ->name('admin.password.email');

    Route::get('reset-password/{token}', [AdminNewPasswordController::class, 'create'])
        ->name('admin.password.reset');

    Route::post('reset-password', [AdminNewPasswordController::class, 'store'])
        ->name('admin.password.store');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    // Tableau de bord
    Route::get('dashboard', [AdminController::class, 'homes'])
        ->name('admin.dashboard');

    /**
     * Résidences
     */
    Route::get('residences', [AdminController::class, 'residences'])
        ->name('admin.residences.index');
    Route::get('residences/create', [AdminController::class, 'createResidence'])
        ->name('admin.residences.create');
    Route::post('residences', [AdminController::class, 'storeResidence'])
        ->name('admin.residences.store');
    Route::get('residences/{residence}/edit', [AdminController::class, 'editResidence'])
        ->name('admin.residences.edit');
    Route::put('residences/{residence}', [AdminController::class, 'updateResidence'])
        ->name('admin.residences.update');
    Route::delete('residences/{residence}', [AdminController::class, 'destroyResidence'])
        ->name('admin.residences.destroy');

    /**
     * Réservations
     */
    Route::get('bookings', [AdminController::class, 'index'])
        ->name('admin.bookings.index');
    Route::get('bookings/{booking}', [AdminController::class, 'showBooking'])
        ->name('admin.bookings.show');
    Route::get('bookings/{booking}/edit', [AdminController::class, 'editBooking'])
        ->name('admin.bookings.edit');
    Route::put('bookings/{booking}', [AdminController::class, 'updateBooking'])
        ->name('admin.bookings.update');
    Route::delete('bookings/{booking}', [AdminController::class, 'destroyBooking'])
        ->name('admin.bookings.destroy');
    Route::post('bookings/{booking}/approve', [AdminController::class, 'approveBooking'])
        ->name('admin.bookings.approve');
    Route::post('bookings/{booking}/reject', [AdminController::class, 'rejectBooking'])
        ->name('admin.bookings.reject');

    /**
     * Clients
     */
    Route::get('clients', [AdminController::class, 'clients'])
        ->name('admin.clients.index');

    Route::get('clients/{client}/bookings', [AdminController::class, 'getClientBookings'])
        ->name('admin.clients.bookings');

    Route::get('clients/{client}', [AdminController::class, 'showClient'])
        ->name('admin.clients.show');

    Route::delete('clients/{client}', [AdminController::class, 'destroyClient'])
        ->name('admin.clients.destroy');

    /**
     * Paiements
     */
    Route::get('payments', [AdminController::class, 'payments'])
        ->name('admin.payments.index');
    Route::get('payments/{payment}', [AdminController::class, 'showPayment'])
        ->name('admin.payments.show');

    /**
     * Utilisateurs (Admins)
     */
    Route::get('users', [AdminController::class, 'users'])
        ->name('admin.users.index');
    Route::get('users/create', [AdminController::class, 'createUser'])
        ->name('admin.users.create');
    Route::post('users', [AdminController::class, 'storeUser'])
        ->name('admin.users.store');
    Route::get('users/{user}/edit', [AdminController::class, 'editUser'])
        ->name('admin.users.edit');
    Route::put('users/{user}', [AdminController::class, 'updateUser'])
        ->name('admin.users.update');
    Route::delete('users/{user}', [AdminController::class, 'destroyUser'])
        ->name('admin.users.destroy');

    /**
     * Rapports
     */
    Route::get('reports', [AdminController::class, 'reports'])
        ->name('admin.reports.index');
    Route::get('reports/{report}', [AdminController::class, 'showReport'])
        ->name('admin.reports.show');

    /**
     * Paramètres / Profil admin
     */
    Route::get('profile', [AdminController::class, 'profile'])
        ->name('admin.profile');
    Route::put('profile', [AdminController::class, 'updateProfile'])
        ->name('admin.profile.update');

    /**
     * Déconnexion
     */
    Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])
        ->name('admin.logout');
});
