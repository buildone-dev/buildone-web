<?php

declare(strict_types=1);

namespace App\Features\Roles;

use App\Core\Authorization\Permission;
use App\Core\Menu\MenuItem;
use App\Core\Menu\MenuRegistry;
use App\Infrastructure\Modules\ModuleServiceProvider;

final class RolesServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'roles';
    }

    protected function registerModule(): void
    {
        /** @var MenuRegistry $menu */
        $menu = $this->app->make(MenuRegistry::class);

        $menu->add(new MenuItem(
            'ロール・権限',
            'roles.index',
            'M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.249-8.25-3.286Z',
            Permission::RolesManage->value,
        ));
    }
}
