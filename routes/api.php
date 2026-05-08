<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocalBusinessController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ==========================================
// Autenticación API
// ==========================================
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (! $user || ! \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }

    return response()->json([
        'success' => true,
        'token' => $user->createToken('API_TOKEN')->plainTextToken
    ]);
});

// ==========================================
// Grupo Público (Marketplace)
// ==========================================
Route::prefix('marketplace')->group(function () {
    // Lista de todos los negocios (feed "Descubre tu ciudad")
    Route::get('/businesses', [MarketplaceController::class, 'index']);
    
    // Detalles de un negocio en específico y sus productos
    Route::get('/businesses/{id}', [MarketplaceController::class, 'show']);
});


// ==========================================
// Rutas Protegidas (Requieren Autenticación)
// ==========================================
Route::middleware('auth:sanctum')->group(function () {

    // ------------------------------------------
    // Grupo Propietario
    // ------------------------------------------
    
    // Gestión del Negocio
    Route::prefix('my-business')->group(function () {
        Route::get('/', [LocalBusinessController::class, 'show']); // Ver mi negocio
        Route::post('/', [LocalBusinessController::class, 'store']); // Crear mi negocio
        Route::put('/', [LocalBusinessController::class, 'update']); // Actualizar mi negocio
        
        // Gestión de Pedidos del Negocio
        Route::get('/orders', [OrderController::class, 'indexOwner']); // Ver pedidos recibidos
        Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']); // Cambiar estado del pedido
    });

    // Gestión del Catálogo de Productos
    Route::prefix('my-products')->group(function () {
        Route::get('/', [ProductController::class, 'index']); // Listar mis productos
        Route::post('/', [ProductController::class, 'store']); // Crear producto
        Route::get('/{id}', [ProductController::class, 'show']); // Ver producto
        Route::put('/{id}', [ProductController::class, 'update']); // Actualizar producto
        Route::delete('/{id}', [ProductController::class, 'destroy']); // Eliminar producto
    });

    // ------------------------------------------
    // Grupo Turista
    // ------------------------------------------
    
    // Gestión de Pedidos (Carrito/Compras)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'indexTourist']); // Ver mis pedidos (historial)
        Route::post('/', [OrderController::class, 'store']); // Crear un pedido (checkout)
    });

});
