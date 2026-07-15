<?php

declare(strict_types=1);

namespace App\Core\Dashboard;

use Closure;

/**
 * ダッシュボードに表示する集計カード1枚。各Featureが自分の主要数値を登録する。
 * value は表示時に評価される Closure（DBアクセスを遅延させる）。
 */
final class DashboardWidget
{
    /**
     * @param  Closure(): (int|string)  $value  表示する値を返す
     */
    public function __construct(
        public readonly string $label,
        public readonly Closure $value,
        public readonly ?string $permission = null,
        public readonly ?string $route = null,
    ) {}

    public function resolve(): int|string
    {
        return ($this->value)();
    }

    public function url(): ?string
    {
        return $this->route !== null ? route($this->route) : null;
    }
}
