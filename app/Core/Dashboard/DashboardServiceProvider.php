<?php

declare(strict_types=1);

namespace App\Core\Dashboard;

use App\Infrastructure\Modules\ModuleServiceProvider;

final class DashboardServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'dashboard';
    }

    protected function bindings(): void
    {
        $this->app->singleton(WidgetRegistry::class);
    }
}
