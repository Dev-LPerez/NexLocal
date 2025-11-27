<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Experience;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'experience_id' => Experience::factory(),
            'booking_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => null,
            'payment_intent_id' => null,
            'paid_at' => null,
            'total_amount' => fake()->randomFloat(2, 50, 1000),
            'num_travelers' => fake()->numberBetween(1, 5),
        ];
    }

    /**
     * Indicate that the booking is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    /**
     * Indicate that the booking is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'payment_status' => 'paid',
            'paid_at' => now()->subDays(7),
            'booking_date' => now()->subDays(3),
        ]);
    }

    /**
     * Indicate that the booking is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }
}

