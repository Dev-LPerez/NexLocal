<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;

// --- Ruta Pública Principal ---
Route::get('/', [ExperienceController::class, 'index'])->name('home');

// --- Ruta Pública para Ver Detalle de Experiencia --- (RF-014)
Route::get('/experiences/{experience}', [ExperienceController::class, 'show'])
    ->where('experience', '[0-9]+')
    ->name('experiences.show');

// --- Ruta Dashboard (requiere auth y email verificado) ---
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// --- Grupo de Rutas Protegidas por Autenticación ---
Route::middleware('auth')->group(function () {
    // Rutas para gestión del perfil del usuario (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para gestión de experiencias (Guías)
    Route::resource('experiences', ExperienceController::class)->except(['index', 'show']);

    // Rutas para la verificación de identidad del Guía
    Route::get('/verify-identity', [VerificationController::class, 'create'])->name('verification.create');
    Route::post('/verify-identity', [VerificationController::class, 'store'])->name('verification.store');

    // --- RUTAS PARA GESTIÓN DE RESERVAS ---
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::patch('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/guide-cancel', [BookingController::class, 'guideCancel'])->name('bookings.guideCancel');
});

// --- Rutas de Autenticación ---
require __DIR__.'/auth.php';
