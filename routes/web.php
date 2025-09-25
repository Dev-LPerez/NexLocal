<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\VerificationController; // <-- Añade esta línea

// --- Ruta Pública Principal ---
Route::get('/', [ExperienceController::class, 'index'])->name('home');

// Esta es la ruta del dashboard que crea Breeze. La dejamos como está.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Este grupo de rutas requiere que el usuario haya iniciado sesión
Route::middleware('auth')->group(function () {
    // Rutas para el perfil de usuario (creadas por Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Mantenemos nuestras rutas de experiencias aquí para que estén protegidas.
    Route::resource('experiences', ExperienceController::class);

    // --- RUTAS PARA LA VERIFICACIÓN DE IDENTIDAD ---
    Route::get('/verify-identity', [VerificationController::class, 'create'])->name('verification.create');
    Route::post('/verify-identity', [VerificationController::class, 'store'])->name('verification.store');
});


// Esta línea es fundamental, carga todas las rutas de autenticación (login, register, etc.)
require __DIR__.'/auth.php';