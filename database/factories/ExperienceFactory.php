<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Experience::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'location' => fake()->city(),
            'duration' => fake()->numberBetween(1, 8) . ' horas',
            'price' => fake()->randomFloat(2, 20, 500),
            'includes' => ['Guía profesional', 'Equipo necesario'],
            'not_includes' => ['Comida', 'Transporte'],
            'status' => 'published',
            'is_featured' => false,
        ];
    }

    /**
     * Indicate that the experience is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the experience is hidden.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'hidden',
        ]);
    }

    /**
     * Indicate that the experience is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}

