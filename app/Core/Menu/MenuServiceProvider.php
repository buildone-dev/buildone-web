<?php

declare(strict_types=1);

namespace App\Core\Menu;

use App\Core\Authorization\Permission;
use App\Infrastructure\Modules\ModuleServiceProvider;

final class MenuServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'menu';
    }

    protected function bindings(): void
    {
        $this->app->singleton(MenuRegistry::class);
    }

    protected function registerModule(): void
    {
        /** @var MenuRegistry $menu */
        $menu = $this->app->make(MenuRegistry::class);

        $menu->add(new MenuItem(
            'ダッシュボード',
            'dashboard',
            'M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75',
        ));

        $menu->add(new MenuItem(
            'ユーザー',
            'users.index',
            'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
            Permission::UsersView->value,
        ));

        $menu->add(new MenuItem(
            '監査ログ',
            'audit.index',
            'M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z',
            Permission::AuditView->value,
        ));
    }
}
