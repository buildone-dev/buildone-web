<?php

declare(strict_types=1);

namespace App\Features\Time;

use App\Core\Authorization\Permission;
use App\Core\Dashboard\DashboardWidget;
use App\Core\Dashboard\WidgetRegistry;
use App\Core\Menu\MenuItem;
use App\Core\Menu\MenuRegistry;
use App\Features\Time\Models\Employee;
use App\Infrastructure\Modules\ModuleServiceProvider;

final class TimeServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'time';
    }

    protected function registerModule(): void
    {
        /** @var MenuRegistry $menu */
        $menu = $this->app->make(MenuRegistry::class);

        $menu->add(new MenuItem(
            '勤怠打刻',
            'time.kiosk',
            'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            Permission::TimeView->value,
        ));

        $menu->add(new MenuItem(
            '勤怠一覧',
            'time.index',
            'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5',
            Permission::TimeView->value,
        ));

        $menu->add(new MenuItem(
            '従業員',
            'time.employees.index',
            'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
            Permission::TimeView->value,
        ));

        /** @var WidgetRegistry $widgets */
        $widgets = $this->app->make(WidgetRegistry::class);
        $widgets->add(new DashboardWidget(
            '現在の出勤者',
            static fn (): int => Employee::query()->where('working', true)->count(),
            Permission::TimeView->value,
            'time.index',
        ));
    }
}
