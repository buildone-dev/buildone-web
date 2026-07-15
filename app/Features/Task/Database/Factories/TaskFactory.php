<?php

declare(strict_types=1);

namespace App\Features\Task\Database\Factories;

use App\Features\Task\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
final class TaskFactory extends Factory
{
    /**
     * @var class-string<Task>
     */
    protected $model = Task::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
        ];
    }
}
