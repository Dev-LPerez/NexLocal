<?php

use App\Helpers\NotificationHelper;
use App\Models\User;
use App\Models\Notification;
use App\Models\Booking;
use App\Models\Experience;

uses(Tests\TestCase::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('bookingConfirmed crea notificacion correcta', function () {
    $user = User::factory()->create();
    $guide = User::factory()->create();

    // Mockear estructura de Booking y Experience
    $experience = Experience::factory()->create(['user_id' => $guide->id, 'title' => 'Tour Test']);
    $booking = Booking::factory()->create([
        'user_id' => $user->id,
        'experience_id' => $experience->id
    ]);

    // Ejecutar Helper
    NotificationHelper::bookingConfirmed($user, $booking);

    // Verificar base de datos
    expect(Notification::where('user_id', $user->id)
        ->where('type', 'booking_confirmed')
        ->where('icon', '✅')
        ->where('title', '¡Reserva Confirmada!')
        ->exists()
    )->toBeTrue();
});

test('custom notification crea registro con datos personalizados', function () {
    $user = User::factory()->create();

    NotificationHelper::custom(
        $user,
        'system_alert',
        'Alerta de Sistema',
        'Mantenimiento programado',
        '⚠️',
        '/status'
    );

    expect(Notification::where('user_id', $user->id)
        ->where('type', 'system_alert')
        ->where('title', 'Alerta de Sistema')
        ->where('message', 'Mantenimiento programado')
        ->where('icon', '⚠️')
        ->where('link', '/status')
        ->exists()
    )->toBeTrue();
});
