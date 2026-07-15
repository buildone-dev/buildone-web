<?php

declare(strict_types=1);

namespace App\Features\Entry\Models;

use App\Features\Entry\Database\Factories\MemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 入退室の利用者。code は QR / IC カードの識別子（ユニーク）。
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property bool $present
 * @property bool $is_active
 */
class Member extends Model
{
    /** @use HasFactory<MemberFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'present',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'present' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function newFactory(): MemberFactory
    {
        return MemberFactory::new();
    }

    /**
     * @return HasMany<AttendanceLog, $this>
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
