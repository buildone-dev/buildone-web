<?php

declare(strict_types=1);

namespace App\Core\Audit;

use App\Infrastructure\Modules\ModuleServiceProvider;

final class AuditServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'audit';
    }

    protected function bindings(): void
    {
        $this->app->singleton(AuditLogger::class);
    }
}
