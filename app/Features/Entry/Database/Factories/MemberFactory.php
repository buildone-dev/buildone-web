<?php

declare(strict_types=1);

namespace App\Features\Entry\Database\Factories;

use App\Features\Entry\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
final class MemberFactory extends Factory
{
    /**
     * @var class-string<Member>
     */
    protected $model = Member::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => 'CARD-'.fake()->unique()->numerify('######'),
            'present' => false,
            'is_active' => true,
        ];
    }
}
