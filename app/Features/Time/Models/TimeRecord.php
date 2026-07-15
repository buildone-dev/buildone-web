<?php

declare(strict_types=1);

namespace App\Features\Time\Models;

use App\Features\Time\Database\Factories\TimeRecordFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 1回の勤務（出勤〜退勤）。clock_out が null の間は勤務中。
 *
 * @property int $employee_id
 * @property Carbon $clock_in
 * @property Carbon|null $clock_out
 * @property int|null $worked_minutes
 * @property-read Employee $employee
 */
class TimeRecord extends Model
{
    /** @use HasFactory<TimeRecordFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'clock_in',
        'clock_out',
        'worked_minutes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
        ];
    }

    protected static function newFactory(): TimeRecordFactory
    {
        return TimeRecordFactory::new();
    }

    public function workedLabel(): string
    {
        if ($this->worked_minutes === null) {
            return '勤務中';
        }

        return intdiv($this->worked_minutes, 60).'時間'.($this->worked_minutes % 60).'分';
    }

    /**
     * @return BelongsTo<Employee, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
