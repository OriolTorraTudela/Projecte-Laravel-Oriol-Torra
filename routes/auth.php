<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutes d'autenticació — Laravel Breeze
|--------------------------------------------------------------------------
|
| Aquestes rutes gestionen tot el flux d'autenticació:
|  - Registre d'usuaris nous
|  - Login / Logout
|  - Restabliment de contrasenya
|  - Verificació d'email
|
*/

// ── Rutes per a usuaris NO autenticats (guest) ──────────────────────────────
Route::middleware('guest')->group(function () {

    // Formulari de registre
    Route::get('register', [RegisteredUserController::class, 'create'])
         ->name('register');

    // Processament del formulari de registre
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Formulari de login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
         ->name('login');

    // Processament del formulari de login
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Formulari per sol·licitar restabliment de contrasenya
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
         ->name('password.request');

    // Enviament de l'email de restabliment
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
         ->name('password.email');

    // Formulari per establir la nova contrasenya (amb token)
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
         ->name('password.reset');

    // Processament de la nova contrasenya
    Route::post('reset-password', [NewPasswordController::class, 'store'])
         ->name('password.store');
});

// ── Rutes per a usuaris AUTENTICATS ────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Pàgina de verificació d'email
    Route::get('verify-email', EmailVerificationPromptController::class)
         ->name('verification.notice');

    // Verificació via link de l'email
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
         ->middleware(['signed', 'throttle:6,1'])
         ->name('verification.verify');

    // Reenviar email de verificació
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
         ->middleware('throttle:6,1')
         ->name('verification.send');

    // Formulari de confirmació de contrasenya
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
         ->name('password.confirm');

    // Processament de la confirmació
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
         ->name('logout');
});
