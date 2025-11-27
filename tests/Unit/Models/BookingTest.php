<?php

use App\Models\Booking;
use App\Models\User;
use App\Models\Experience;
use Carbon\Carbon;

uses(Tests\TestCase::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('booking casts attributes correctly', function () {
    // Crear dependencias requeridas
    $user = User::factory()->create();
    $experience = Experience::factory()->create();

    // Crear y guardar el booking en la BD para que los casts se apliquen
    $booking = Booking::create([
        'user_id' => $user->id,
        'experience_id' => $experience->id,
        'booking_date' => '2025-11-27 10:00:00',
        'paid_at' => '2025-11-27 09:00:00',
        'total_amount' => '150000.50',
        'num_travelers' => 2,
        'status' => 'confirmed',
        'payment_status' => 'paid',
    ]);

    // Refrescar desde la BD para asegurar que los casts se apliquen
    $booking->refresh();

    // Verificar Fechas (deben ser instancias de Carbon)
    expect($booking->booking_date)->toBeInstanceOf(Carbon::class)
        ->and($booking->paid_at)->toBeInstanceOf(Carbon::class);

    // Verificar Decimales
    expect($booking->total_amount)->toEqual('150000.50');

    // Verificar que el booking fue creado correctamente
    expect($booking->user_id)->toBe($user->id)
        ->and($booking->experience_id)->toBe($experience->id)
        ->and($booking->status)->toBe('confirmed');
});
