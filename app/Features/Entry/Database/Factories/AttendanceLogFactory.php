<?php

declare(strict_types=1);

namespace App\Features\Entry\Database\Factories;

use App\Features\Entry\Models\AttendanceLog;
use App\Features\Entry\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttendanceLog>
 */
final class AttendanceLogFactory extends Factory
{
    /**
     * @var class-string<AttendanceLog>
     */
    protected $model = AttendanceLog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_id' => Member::factory(),
            'direction' => AttendanceLog::IN,
            'method' => 'manual',
            'recorded_at' => now(),
        ];
    }
}
