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

    // Route home
    Route::get('dashboard', [AdminController::class, 'homes'])
        ->name('adminauth.homes');


    // Route All resiences
    Route::get('residences', [AdminController::class, 'residences'])
        ->name('admin.residences');


    // Route booking
    Route::get('bookings', [AdminController::class, 'index'])
        ->name('admin.bookings');

    // Route to show a single booking.
    Route::get('bookings/{booking}', [AdminController::class, 'show'])->name('bookings.show');

    // Route to show the form for editing a booking.
    Route::get('bookings/{booking}/edit', [AdminController::class, 'edit'])->name('bookings.edit');

    // Route to handle the form submission for updating a booking
    Route::put('bookings/{booking}', [AdminController::class, 'update'])->name('bookings.update');

    // Route to delete a booking. Use the DELETE HTTP method.
    Route::delete('bookings/{booking}', [AdminController::class, 'destroy'])->name('bookings.destroy');





    Route::get('verify-email', AdminEmailVerificationPromptController::class)
        ->name('admin.verification.notice');

    Route::get('verify-email/{id}/{hash}', AdminVerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('admin.verification.verify');

    Route::post('email/verification-notification', [AdminEmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('admin.verification.send');

    Route::get('confirm-password', [AdminConfirmablePasswordController::class, 'show'])
        ->name('admin.password.confirm');

    Route::post('confirm-password', [AdminConfirmablePasswordController::class, 'store']);

    Route::put('admin.password', [AdminPasswordController::class, 'update'])->name('admin.password.update');






    Route::post('admin/logout', [AdminAuthenticatedSessionController::class, 'destroy'])
        ->name('admin.logout');
});
