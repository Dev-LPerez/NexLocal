<?php

use App\Models\Experience;
use App\Models\User;

uses(Tests\TestCase::class);
uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'guide']);
});

test('scopePublished solo trae experiencias publicadas', function () {
    // Crear datos de prueba
    Experience::factory()->create(['user_id' => $this->user->id, 'status' => 'published']);
    Experience::factory()->create(['user_id' => $this->user->id, 'status' => 'draft']);
    Experience::factory()->create(['user_id' => $this->user->id, 'status' => 'hidden']);

    $published = Experience::published()->get();

    expect($published)->toHaveCount(1)
        ->and($published->first()->status)->toBe('published');
});

test('scopeFeatured solo trae experiencias destacadas', function () {
    Experience::factory()->create(['user_id' => $this->user->id, 'is_featured' => true]);
    Experience::factory()->create(['user_id' => $this->user->id, 'is_featured' => false]);

    $featured = Experience::featured()->get();

    expect($featured)->toHaveCount(1)
        ->and($featured->first()->is_featured)->toBeTrue();
});

test('scopePubliclyVisible ordena destacadas primero y excluye no publicadas', function () {
    // Crear: 1 Publicada Normal (Antigua), 1 Publicada Destacada (Nueva), 1 Oculta
    $normal = Experience::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'published',
        'is_featured' => false,
        'created_at' => now()->subDay()
    ]);

    $featured = Experience::factory()->create([
        'user_id' => $this->user->id,
        'status' => 'published',
        'is_featured' => true,
        'created_at' => now()
    ]);

    Experience::factory()->create(['user_id' => $this->user->id, 'status' => 'hidden']);

    $results = Experience::publiclyVisible()->get();

    // Debe traer 2, y la primera debe ser la destacada
    expect($results)->toHaveCount(2);
    expect($results->first()->id)->toBe($featured->id);
    expect($results->last()->id)->toBe($normal->id);
});
