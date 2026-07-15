<?php

declare(strict_types=1);

namespace App\Core\Menu;

use Illuminate\Support\Str;

/**
 * サイドバー等に表示するメニュー1項目。
 */
final class MenuItem
{
    public function __construct(
        public readonly string $label,
        public readonly string $route,
        public readonly string $icon,
        public readonly ?string $permission = null,
    ) {}

    public function url(): string
    {
        return route($this->route);
    }

    public function isActive(): bool
    {
        $prefix = Str::before($this->route, '.');

        return request()->routeIs($this->route) || request()->routeIs($prefix.'.*');
    }
}
