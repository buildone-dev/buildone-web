<?php

declare(strict_types=1);

use App\Core\Authorization\Models\Role;
use App\Core\Authorization\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('RolesManage 権限がないとロール管理を開けない', function (): void {
    actingAs(makeUserWith([Permission::UsersView->value]))->get('/admin/roles')->assertForbidden();
});

it('権限があればロール一覧を見られる', function (): void {
    actingAs(makeUserWith([Permission::RolesManage->value]))
        ->get('/admin/roles')
        ->assertOk()
        ->assertSee('ロール・権限');
});

it('ロールを作成でき権限が保存される', function (): void {
    actingAs(makeUserWith([Permission::RolesManage->value]))
        ->post('/admin/roles', [
            'name' => 'manager',
            'label' => '店長',
            'permissions' => [Permission::EntryView->value, Permission::TimeView->value],
        ])
        ->assertRedirect('/admin/roles');

    $role = Role::query()->where('name', 'manager')->first();
    expect($role)->not->toBeNull();
    expect($role->permissions)->toContain(Permission::EntryView->value);
});

it('ロールの権限を更新できる', function (): void {
    $role = Role::query()->create(['name' => 'staff', 'label' => 'スタッフ', 'permissions' => []]);

    actingAs(makeUserWith([Permission::RolesManage->value]))
        ->put("/admin/roles/{$role->id}", ['label' => 'スタッフ', 'permissions' => [Permission::ReserveView->value]])
        ->assertRedirect('/admin/roles');

    expect($role->fresh()->permissions)->toBe([Permission::ReserveView->value]);
});

it('super_admin の権限は変更されない（常に全権限）', function (): void {
    $role = Role::query()->create(['name' => Role::SUPER_ADMIN, 'label' => '管理者', 'permissions' => Permission::values()]);

    actingAs(makeUserWith([Permission::RolesManage->value]))
        ->put("/admin/roles/{$role->id}", ['label' => '最高管理者', 'permissions' => []]);

    // ラベルは更新されるが権限は空にされない
    expect($role->fresh()->label)->toBe('最高管理者');
    expect($role->fresh()->hasPermission(Permission::UsersManage->value))->toBeTrue();
});

it('super_admin ロールは削除できない', function (): void {
    $role = Role::query()->create(['name' => Role::SUPER_ADMIN, 'label' => '管理者', 'permissions' => []]);

    actingAs(makeUserWith([Permission::RolesManage->value]))
        ->delete("/admin/roles/{$role->id}")
        ->assertSessionHas('role_error');

    expect(Role::query()->whereKey($role->id)->exists())->toBeTrue();
});

it('ユーザーが割り当てられたロールは削除できない', function (): void {
    $role = Role::query()->create(['name' => 'used', 'label' => '使用中', 'permissions' => []]);
    User::factory()->create(['role_id' => $role->id]);

    actingAs(makeUserWith([Permission::RolesManage->value]))
        ->delete("/admin/roles/{$role->id}")
        ->assertSessionHas('role_error');

    expect(Role::query()->whereKey($role->id)->exists())->toBeTrue();
});
