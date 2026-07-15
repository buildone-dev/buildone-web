<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Core\Authorization\Models\Role;
use App\Core\Authorization\Permission;
use Illuminate\Database\Seeder;

/**
 * 既定ロールを冪等に投入する（本番でも安全に再実行できる）。
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->updateOrCreate(
            ['name' => Role::SUPER_ADMIN],
            ['label' => 'スーパー管理者', 'permissions' => Permission::values()],
        );

        Role::query()->updateOrCreate(
            ['name' => 'admin'],
            ['label' => '管理者', 'permissions' => [
                Permission::UsersView->value,
                Permission::UsersManage->value,
                Permission::AuditView->value,
            ]],
        );

        Role::query()->updateOrCreate(
            ['name' => 'viewer'],
            ['label' => '閲覧者', 'permissions' => [
                Permission::UsersView->value,
            ]],
        );
    }
}
