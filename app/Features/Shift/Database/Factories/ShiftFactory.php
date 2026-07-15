<?php

declare(strict_types=1);

namespace App\Features\Shift\Database\Factories;

use App\Features\Shift\Models\Shift;
use App\Features\Time\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shift>
 */
final class ShiftFactory extends Factory
{
    /**
     * @var class-string<Shift>
     */
    protected $model = Shift::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'work_date' => today(),
            'start_time' => '09:00',
            'end_time' => '18:00',
            'note' => null,
        ];
    }
}
