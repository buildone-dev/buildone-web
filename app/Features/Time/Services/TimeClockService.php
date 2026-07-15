<?php

declare(strict_types=1);

namespace App\Features\Time\Services;

use App\Features\Time\Models\Employee;
use App\Features\Time\Models\TimeRecord;

/**
 * 出退勤の打刻ユースケース。未退勤の記録があれば退勤、なければ出勤として扱う。
 */
final class TimeClockService
{
    /**
     * @return array{record: TimeRecord, direction: 'in'|'out'}
     */
    public function punch(Employee $employee): array
    {
        $open = $employee->records()->whereNull('clock_out')->latest('clock_in')->first();

        if ($open !== null) {
            $out = now();
            $open->update([
                'clock_out' => $out,
                'worked_minutes' => (int) abs($open->clock_in->diffInMinutes($out)),
            ]);
            $employee->update(['working' => false]);

            return ['record' => $open, 'direction' => 'out'];
        }

        /** @var TimeRecord $record */
        $record = $employee->records()->create(['clock_in' => now()]);
        $employee->update(['working' => true]);

        return ['record' => $record, 'direction' => 'in'];
    }
}
