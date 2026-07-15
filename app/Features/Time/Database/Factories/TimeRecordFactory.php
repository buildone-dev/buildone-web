<?php

declare(strict_types=1);

namespace App\Features\Time\Database\Factories;

use App\Features\Time\Models\Employee;
use App\Features\Time\Models\TimeRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeRecord>
 */
final class TimeRecordFactory extends Factory
{
    /**
     * @var class-string<TimeRecord>
     */
    protected $model = TimeRecord::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'clock_in' => now()->subHours(8),
            'clock_out' => now(),
            'worked_minutes' => 480,
        ];
    }
}
