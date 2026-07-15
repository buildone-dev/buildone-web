<?php

declare(strict_types=1);

namespace App\Features\Entry;

use App\Core\Authorization\Permission;
use App\Core\Dashboard\DashboardWidget;
use App\Core\Dashboard\WidgetRegistry;
use App\Core\Menu\MenuItem;
use App\Core\Menu\MenuRegistry;
use App\Features\Entry\Models\AttendanceLog;
use App\Infrastructure\Modules\ModuleServiceProvider;

final class EntryServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'entry';
    }

    protected function registerModule(): void
    {
        /** @var MenuRegistry $menu */
        $menu = $this->app->make(MenuRegistry::class);

        $menu->add(new MenuItem(
            '打刻',
            'entry.kiosk',
            'M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z',
            Permission::EntryView->value,
        ));

        $menu->add(new MenuItem(
            '入退室状況',
            'entry.index',
            'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z',
            Permission::EntryView->value,
        ));

        $menu->add(new MenuItem(
            '利用者',
            'entry.members.index',
            'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
            Permission::EntryView->value,
        ));

        /** @var WidgetRegistry $widgets */
        $widgets = $this->app->make(WidgetRegistry::class);
        $widgets->add(new DashboardWidget(
            '本日の入室',
            static fn (): int => AttendanceLog::query()->where('direction', AttendanceLog::IN)->whereDate('recorded_at', today())->count(),
            Permission::EntryView->value,
            'entry.index',
        ));
    }
}
