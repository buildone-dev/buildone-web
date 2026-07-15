<?php

declare(strict_types=1);

namespace App\Features\Time\Database\Factories;

use App\Features\Time\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
final class EmployeeFactory extends Factory
{
    /**
     * @var class-string<Employee>
     */
    protected $model = Employee::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'code' => 'EMP-'.fake()->unique()->numerify('######'),
            'working' => false,
            'is_active' => true,
        ];
    }
}
