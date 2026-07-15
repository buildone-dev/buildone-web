<?php

declare(strict_types=1);

namespace App\Features\Report\Models;

use App\Features\Report\Database\Factories\ReportFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 業務日報。summary は AI による要約（未生成の間は null）。
 *
 * @property int $user_id
 * @property Carbon $report_date
 * @property string $body
 * @property string|null $summary
 * @property-read User $author
 */
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'report_date',
        'body',
        'summary',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'report_date' => 'date',
        ];
    }

    protected static function newFactory(): ReportFactory
    {
        return ReportFactory::new();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
