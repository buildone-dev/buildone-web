<?php

declare(strict_types=1);

namespace App\Features\Reserve\Models;

use App\Features\Reserve\Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 予約1件。status は pending（予約待ち）/ confirmed（確定）/ cancelled（キャンセル）。
 *
 * @property string $customer_name
 * @property string|null $phone
 * @property string|null $email
 * @property Carbon $reserved_at
 * @property int $party_size
 * @property string|null $note
 * @property string $status
 */
class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    public const PENDING = 'pending';

    public const CONFIRMED = 'confirmed';

    public const CANCELLED = 'cancelled';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'customer_name',
        'phone',
        'email',
        'reserved_at',
        'party_size',
        'note',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reserved_at' => 'datetime',
            'party_size' => 'integer',
        ];
    }

    protected static function newFactory(): ReservationFactory
    {
        return ReservationFactory::new();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::CONFIRMED => '確定',
            self::CANCELLED => 'キャンセル',
            default => '予約待ち',
        };
    }

    public function statusVariant(): string
    {
        return match ($this->status) {
            self::CONFIRMED => 'success',
            self::CANCELLED => 'danger',
            default => 'warning',
        };
    }
}
