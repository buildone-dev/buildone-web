<?php

declare(strict_types=1);

namespace App\Features\Report\Database\Factories;

use App\Features\Report\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
final class ReportFactory extends Factory
{
    /**
     * @var class-string<Report>
     */
    protected $model = Report::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'report_date' => today(),
            'body' => "・A社訪問、見積提出\n・B社と電話、来週デモ\n・在庫システムの不具合を修正",
            'summary' => null,
        ];
    }
}
