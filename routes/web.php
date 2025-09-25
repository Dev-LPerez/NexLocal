<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExperienceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Ruta Pública Principal ---
// Aquí está la corrección: hacemos que la página de inicio vuelva a ser
// manejada por nuestro ExperienceController para que cargue los datos.
Route::get('/', [ExperienceController::class, 'index'])->name('home');


// --- Rutas que requieren autenticación (Breeze y nuestras rutas protegidas) ---

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
    // Solo usuarios logueados podrán crear, editar, etc.
    Route::resource('experiences', ExperienceController::class);
});


// Esta línea es fundamental, carga todas las rutas de autenticación (login, register, etc.)
require __DIR__.'/auth.php';