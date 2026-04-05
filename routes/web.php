<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — InstaLaravel
|--------------------------------------------------------------------------
|
| IMPORTANT: L'ordre de les rutes importa molt a Laravel.
| Les rutes estàtiques (/images/create) han d'anar ABANS
| que les rutes dinàmiques (/images/{image}), perquè si no
| Laravel interpreta "create" com un ID d'imatge i retorna 404.
|
*/

// ── Pàgina principal ───────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Rutes protegides per autenticació ──────────────────────────────────────
Route::middleware('auth')->group(function () {

    // ── CRUD Imatges ──────────────────────────────────────────────────────
    // IMPORTANT: create ABANS de {image} per evitar conflicte de rutes
    Route::get('/images/create', [ImageController::class, 'create'])->name('images.create');
    Route::post('/images', [ImageController::class, 'store'])->name('images.store');
    Route::get('/images/{image}/edit', [ImageController::class, 'edit'])->name('images.edit');
    Route::put('/images/{image}', [ImageController::class, 'update'])->name('images.update');
    Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');

    // ── CRUD Comentaris ───────────────────────────────────────────────────
    Route::post('/images/{image}/comments', [CommentController::class, 'store'])
         ->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])
         ->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])
         ->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
         ->name('comments.destroy');

    // ── Likes (AJAX) ──────────────────────────────────────────────────────
    Route::post('/images/{image}/like', [LikeController::class, 'toggle'])
         ->name('likes.toggle');

    // ── Perfil ────────────────────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ── Rutes públiques d'imatges (sense autenticació) ─────────────────────────
// Ha d'anar DESPRÉS del grup auth per evitar que capturi /images/create
Route::get('/images/{image}', [ImageController::class, 'show'])->name('images.show');

// ── Rutes d'autenticació (Breeze) ──────────────────────────────────────────
require __DIR__.'/auth.php';