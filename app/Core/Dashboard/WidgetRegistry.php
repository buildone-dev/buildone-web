<?php

declare(strict_types=1);

namespace App\Core\Dashboard;

use Illuminate\Support\Facades\Gate;

/**
 * ダッシュボードWidgetのレジストリ。各Featureが add() で登録し、
 * ダッシュボードは visible() で権限フィルタ済みの一覧を得る。
 * Core は特定のFeatureに依存しない（Featureが自分の集計を持ち込む）。
 */
final class WidgetRegistry
{
    /**
     * @var list<DashboardWidget>
     */
    private array $widgets = [];

    public function add(DashboardWidget $widget): void
    {
        $this->widgets[] = $widget;
    }

    /**
     * 現在のユーザーが閲覧できるWidgetのみ返す。
     *
     * @return list<DashboardWidget>
     */
    public function visible(): array
    {
        return array_values(array_filter(
            $this->widgets,
            static fn (DashboardWidget $widget): bool => $widget->permission === null || Gate::allows($widget->permission),
        ));
    }
}
