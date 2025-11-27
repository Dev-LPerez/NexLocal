<?php

use App\Models\User;

uses(Tests\TestCase::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

test('isVerifiedGuide devuelve true solo si cumple todas las condiciones', function () {
    // Caso: Guía verificado correctamente
    $guide = User::factory()->create([
        'role' => 'guide',
        'verification_status' => 'approved',
        'identity_verified_at' => now(),
    ]);
    expect($guide->isVerifiedGuide())->toBeTrue();

    // Caso: Es turista (no debería pasar)
    $tourist = User::factory()->create([
        'role' => 'tourist',
        'verification_status' => 'approved',
        'identity_verified_at' => now(),
    ]);
    expect($tourist->isVerifiedGuide())->toBeFalse();

    // Caso: Guía pendiente (no debería pasar)
    $pendingGuide = User::factory()->create([
        'role' => 'guide',
        'verification_status' => 'pending',
    ]);
    expect($pendingGuide->isVerifiedGuide())->toBeFalse();
});

test('hasPendingVerification detecta correctamente el estado pendiente', function () {
    $guide = User::factory()->create([
        'role' => 'guide',
        'verification_status' => 'pending',
    ]);

    expect($guide->hasPendingVerification())->toBeTrue();
});

test('isSuspended verifica el booleano correctamente', function () {
    $user = User::factory()->create(['is_suspended' => true]);
    expect($user->isSuspended())->toBeTrue();

    $activeUser = User::factory()->create(['is_suspended' => false]);
    expect($activeUser->isSuspended())->toBeFalse();
});

test('suspend method actualiza el estado, razón y fecha', function () {
    $user = User::factory()->create();
    $reason = "Comportamiento inapropiado";

    $user->suspend($reason);
    $user->refresh();

    expect($user->is_suspended)->toBeTrue()
        ->and($user->suspension_reason)->toBe($reason)
        ->and($user->suspended_at)->not->toBeNull();
});

test('restore method limpia el estado de suspensión', function () {
    $user = User::factory()->create([
        'is_suspended' => true,
        'suspension_reason' => 'Test',
        'suspended_at' => now()
    ]);

    $user->restore();
    $user->refresh();

    expect($user->is_suspended)->toBeFalse()
        ->and($user->suspension_reason)->toBeNull()
        ->and($user->suspended_at)->toBeNull();
});
