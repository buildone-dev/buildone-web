<?php

declare(strict_types=1);

namespace App\Core\Menu;

use Illuminate\Support\Facades\Gate;

/**
 * メニュー項目のレジストリ。Feature が add() で自分のメニューを登録し、
 * サイドバーは visible() で権限フィルタ済みの一覧を得る。
 * Blade に大量のメニューを直書きしないための仕組み。
 */
final class MenuRegistry
{
    /**
     * @var list<MenuItem>
     */
    private array $items = [];

    public function add(MenuItem $item): void
    {
        $this->items[] = $item;
    }

    /**
     * 現在のユーザーが閲覧できる項目のみを返す。
     *
     * @return list<MenuItem>
     */
    public function visible(): array
    {
        return array_values(array_filter(
            $this->items,
            static fn (MenuItem $item): bool => $item->permission === null || Gate::allows($item->permission),
        ));
    }
}
