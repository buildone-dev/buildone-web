<?php

declare(strict_types=1);

namespace App\Features\Time\Models;

use App\Features\Time\Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 勤怠を打刻する従業員。working = 現在出勤中かどうか。
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property bool $working
 * @property bool $is_active
 */
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'working',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'working' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function newFactory(): EmployeeFactory
    {
        return EmployeeFactory::new();
    }

    /**
     * @return HasMany<TimeRecord, $this>
     */
    public function records(): HasMany
    {
        return $this->hasMany(TimeRecord::class);
    }
}
