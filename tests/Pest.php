<?php

declare(strict_types=1);
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| tests/ 配下の Feature/Unit テストに TestCase をバインドする。
| モジュール（app/Core, app/Features）のテストは各ファイルで
| uses(Tests\TestCase::class, RefreshDatabase::class) を宣言する。
|
*/

uses(TestCase::class)->in('Feature');

use App\Core\Authorization\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * 指定の権限を持つロールのユーザーを作成する（テスト用ヘルパー）。
 *
 * @param  list<string>  $permissions
 */
function makeUserWith(array $permissions = [], bool $active = true, ?string $roleName = null): User
{
    $role = Role::query()->create([
        'name' => $roleName ?? 'role_'.Str::random(8),
        'label' => 'テストロール',
        'permissions' => $permissions,
    ]);

    return User::factory()->create([
        'role_id' => $role->id,
        'is_active' => $active,
    ]);
}
