<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AccountSuspendedController;
use Illuminate\Support\Facades\Artisan;

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
    // Ruta para cuenta suspendida (debe estar antes del middleware check.suspended)
    Route::get('/account/suspended', [AccountSuspendedController::class, 'index'])->name('account.suspended');

Route::middleware('auth')->group(function () {
    // Rutas para gestión del perfil del usuario (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para gestión de experiencias (Guías) - Requiere verificación y cuenta no suspendida
    Route::resource('experiences', ExperienceController::class)
        ->except(['index', 'show'])
        ->middleware(['verified.guide', 'check.suspended']);

    // Rutas para la verificación de identidad del Guía
    Route::get('/verify-identity', [VerificationController::class, 'create'])->name('verification.create');
    Route::post('/verify-identity', [VerificationController::class, 'store'])->name('verification.store');

    // --- RUTAS PARA GESTIÓN DE RESERVAS (protegidas contra usuarios suspendidos) ---
    Route::middleware('check.suspended')->group(function () {
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::patch('/bookings/{booking}/guide-cancel', [BookingController::class, 'guideCancel'])->name('bookings.guideCancel');
        Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
        Route::patch('/bookings/{booking}/mark-completed', [BookingController::class, 'markAsCompleted'])->name('bookings.markAsCompleted');

        // Rutas de checkout
        Route::get('/checkout', [BookingController::class, 'showCheckout'])->name('checkout.show');
        Route::post('/checkout/process', [BookingController::class, 'processPayment'])->name('checkout.process');
        Route::get('/checkout/success/{booking}', [BookingController::class, 'checkoutSuccess'])->name('checkout.success');

        // Rutas de reseñas
        Route::get('/reviews/create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    });

    // --- RUTAS PARA NOTIFICACIONES ---
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // --- RUTAS PARA CHAT ---
    Route::get('/chat/conversations', [ChatController::class, 'getConversations'])->name('chat.conversations');
    Route::get('/chat/{bookingId}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/{bookingId}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat/unread-count', [ChatController::class, 'getUnreadCount'])->name('chat.unreadCount');
    Route::delete('/chat/{bookingId}/conversation', [ChatController::class, 'deleteConversation'])->name('chat.deleteConversation');
});

// --- GRUPO DE RUTAS DE ADMINISTRADOR ---
// Estas rutas están protegidas por autenticación y el middleware 'admin'
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard principal
        Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');

        // Verificación de guías
        Route::get('/verification', [\App\Http\Controllers\AdminController::class, 'verificationQueue'])->name('verification');
        Route::post('/verification/{id}/approve', [\App\Http\Controllers\AdminController::class, 'approveGuide'])->name('approve_guide');
        Route::post('/verification/{id}/reject', [\App\Http\Controllers\AdminController::class, 'rejectGuide'])->name('reject_guide');
        Route::get('/document/{id}/{type?}', [\App\Http\Controllers\AdminController::class, 'downloadDocument'])
            ->where('type', 'front|back')
            ->name('download_document');

        // Gestión de Usuarios
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
        Route::post('/users/{id}/suspend', [\App\Http\Controllers\AdminController::class, 'suspendUser'])->name('users.suspend');
        Route::post('/users/{id}/restore', [\App\Http\Controllers\AdminController::class, 'restoreUser'])->name('users.restore');
        Route::post('/users/{id}/change-role', [\App\Http\Controllers\AdminController::class, 'changeUserRole'])->name('users.changeRole');

        // Moderación de Experiencias
        Route::get('/experiences', [\App\Http\Controllers\AdminController::class, 'experiences'])->name('experiences');
        Route::post('/experiences/{id}/status', [\App\Http\Controllers\AdminController::class, 'changeExperienceStatus'])->name('experiences.status');
        Route::post('/experiences/{id}/toggle-featured', [\App\Http\Controllers\AdminController::class, 'toggleFeatured'])->name('experiences.toggleFeatured');

        // Moderación de Reseñas
        Route::get('/reviews', [\App\Http\Controllers\AdminController::class, 'reviews'])->name('reviews');
        Route::delete('/reviews/{id}', [\App\Http\Controllers\AdminController::class, 'deleteReview'])->name('reviews.delete');

        // Auditoría y Logs
        Route::get('/audit/bookings', [\App\Http\Controllers\AdminController::class, 'bookingsAudit'])->name('audit.bookings');
    });

    Route::get('/deploy-data', function () {


        // Opción B: Ejecutar tu importador (Solo si subiste los JSONs al repo)
        Artisan::call('db:import-data');

        return 'Resultado: <br><pre>' . Artisan::output() . '</pre>';
    });

// --- Rutas de Autenticación ---
require __DIR__.'/auth.php';
