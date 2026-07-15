<?php

declare(strict_types=1);

namespace App\Features\Users;

use App\Infrastructure\Modules\ModuleServiceProvider;

final class UsersServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'users';
    }
}
