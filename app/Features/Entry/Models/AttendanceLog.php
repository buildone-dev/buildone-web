<?php

declare(strict_types=1);

namespace App\Features\Entry\Models;

use App\Features\Entry\Database\Factories\AttendanceLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 入退室の1記録。direction は 'in'（入室）または 'out'（退室）。
 *
 * @property int $member_id
 * @property string $direction
 * @property string $method
 * @property Carbon $recorded_at
 * @property-read Member $member
 */
class AttendanceLog extends Model
{
    /** @use HasFactory<AttendanceLogFactory> */
    use HasFactory;

    public const IN = 'in';

    public const OUT = 'out';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'member_id',
        'direction',
        'method',
        'recorded_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
        ];
    }

    protected static function newFactory(): AttendanceLogFactory
    {
        return AttendanceLogFactory::new();
    }

    public function isEntry(): bool
    {
        return $this->direction === self::IN;
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
