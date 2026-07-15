<?php

declare(strict_types=1);

namespace App\Features\Shift\Models;

use App\Features\Shift\Database\Factories\ShiftFactory;
use App\Features\Time\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 1件のシフト。従業員（勤怠のEmployeeを再利用）に日付と時間帯を割り当てる。
 *
 * @property int $employee_id
 * @property Carbon $work_date
 * @property string $start_time
 * @property string $end_time
 * @property string|null $note
 * @property-read Employee $employee
 */
class Shift extends Model
{
    /** @use HasFactory<ShiftFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'work_date',
        'start_time',
        'end_time',
        'note',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'work_date' => 'date',
        ];
    }

    protected static function newFactory(): ShiftFactory
    {
        return ShiftFactory::new();
    }

    public function timeRange(): string
    {
        return $this->start_time.' 〜 '.$this->end_time;
    }

    /**
     * @return BelongsTo<Employee, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
