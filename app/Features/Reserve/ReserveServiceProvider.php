<?php

declare(strict_types=1);

namespace App\Features\Reserve;

use App\Core\Authorization\Permission;
use App\Core\Dashboard\DashboardWidget;
use App\Core\Dashboard\WidgetRegistry;
use App\Core\Menu\MenuItem;
use App\Core\Menu\MenuRegistry;
use App\Features\Reserve\Models\Reservation;
use App\Infrastructure\Modules\ModuleServiceProvider;

final class ReserveServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'reserve';
    }

    protected function registerModule(): void
    {
        /** @var MenuRegistry $menu */
        $menu = $this->app->make(MenuRegistry::class);

        $menu->add(new MenuItem(
            '予約',
            'reserve.index',
            'M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z',
            Permission::ReserveView->value,
        ));

        /** @var WidgetRegistry $widgets */
        $widgets = $this->app->make(WidgetRegistry::class);
        $widgets->add(new DashboardWidget(
            '本日の予約',
            static fn (): int => Reservation::query()->whereDate('reserved_at', today())->where('status', '!=', Reservation::CANCELLED)->count(),
            Permission::ReserveView->value,
            'reserve.index',
        ));
    }
}
