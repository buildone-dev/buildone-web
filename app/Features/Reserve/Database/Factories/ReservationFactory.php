<?php

declare(strict_types=1);

namespace App\Features\Reserve\Database\Factories;

use App\Features\Reserve\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
final class ReservationFactory extends Factory
{
    /**
     * @var class-string<Reservation>
     */
    protected $model = Reservation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'phone' => fake()->optional()->phoneNumber(),
            'email' => fake()->optional()->safeEmail(),
            'reserved_at' => now()->addDay()->setTime(18, 0),
            'party_size' => fake()->numberBetween(1, 6),
            'note' => null,
            'status' => Reservation::PENDING,
        ];
    }
}
