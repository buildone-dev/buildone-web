<?php

declare(strict_types=1);

namespace App\Features\Entry\Services;

use App\Features\Entry\Models\AttendanceLog;
use App\Features\Entry\Models\Member;

/**
 * 打刻（入退室）のユースケース。present 状態を見て入室／退室を切り替える。
 */
final class AttendanceService
{
    public function punch(Member $member, string $method = 'manual'): AttendanceLog
    {
        $direction = $member->present ? AttendanceLog::OUT : AttendanceLog::IN;

        /** @var AttendanceLog $log */
        $log = $member->logs()->create([
            'direction' => $direction,
            'method' => $method,
            'recorded_at' => now(),
        ]);

        $member->update(['present' => ! $member->present]);

        return $log;
    }
}
