<?php

declare(strict_types=1);

namespace App\Core\Auth;

use App\Infrastructure\Modules\ModuleServiceProvider;

final class AuthServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'auth';
    }
}
