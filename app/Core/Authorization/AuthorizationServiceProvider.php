<?php

declare(strict_types=1);

namespace App\Core\Authorization;

use App\Infrastructure\Modules\ModuleServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

/**
 * 認可の中核。super_admin を全許可し、各 Permission を Gate に登録する。
 */
final class AuthorizationServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'authorization';
    }

    protected function registerModule(): void
    {
        // super_admin はすべての認可を通す
        Gate::before(static function (User $user): ?bool {
            return $user->isSuperAdmin() ? true : null;
        });

        // 各権限を Gate 定義（ability 名 = Permission の値）
        foreach (Permission::cases() as $permission) {
            Gate::define($permission->value, static fn (User $user): bool => $user->hasPermission($permission->value));
        }
    }
}
