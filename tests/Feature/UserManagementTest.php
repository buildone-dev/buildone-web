<?php

declare(strict_types=1);

use App\Core\Audit\Models\AuditLog;
use App\Core\Authorization\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('権限のないユーザーはユーザー一覧を見られない', function (): void {
    actingAs(makeUserWith([]))
        ->get('/admin/users')
        ->assertForbidden();
});

it('閲覧権限があればユーザー一覧を見られる', function (): void {
    actingAs(makeUserWith([Permission::UsersView->value]))
        ->get('/admin/users')
        ->assertOk()
        ->assertSee('ユーザー');
});

it('管理権限がなければ作成画面にアクセスできない', function (): void {
    actingAs(makeUserWith([Permission::UsersView->value]))
        ->get('/admin/users/create')
        ->assertForbidden();
});

it('ユーザーを作成でき監査ログに記録される', function (): void {
    actingAs(makeUserWith([Permission::UsersManage->value]))
        ->post('/admin/users', [
            'name' => '新規 太郎',
            'email' => 'shinki@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ])
        ->assertRedirect('/admin/users');

    expect(User::query()->where('email', 'shinki@example.com')->exists())->toBeTrue();
    expect(AuditLog::query()->where('action', 'user.created')->exists())->toBeTrue();
});

it('作成時に必須項目を検証する', function (): void {
    actingAs(makeUserWith([Permission::UsersManage->value]))
        ->post('/admin/users', [])
        ->assertSessionHasErrors(['name', 'email', 'password']);
});

it('ユーザーの有効・無効を切り替えられ監査に残る', function (): void {
    $admin = makeUserWith([Permission::UsersManage->value]);
    $target = makeUserWith([], active: true);

    actingAs($admin)
        ->patch("/admin/users/{$target->id}/toggle")
        ->assertRedirect('/admin/users');

    expect($target->fresh()->is_active)->toBeFalse();
    expect(AuditLog::query()->where('action', 'user.disabled')->exists())->toBeTrue();
});

it('監査ログ画面は権限が要る', function (): void {
    actingAs(makeUserWith([]))->get('/admin/audit')->assertForbidden();
    actingAs(makeUserWith([Permission::AuditView->value]))->get('/admin/audit')->assertOk();
});
