<?php

declare(strict_types=1);

use App\Core\Audit\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('未認証はプロフィールを開けない', function (): void {
    get('/profile')->assertRedirect('/login');
});

it('認証済みユーザーはプロフィールを開ける', function (): void {
    actingAs(makeUserWith())
        ->get('/profile')
        ->assertOk()
        ->assertSee('パスワード変更');
});

it('プロフィール（名前・メール）を更新できる', function (): void {
    $user = makeUserWith();

    actingAs($user)
        ->put('/profile', ['name' => '新しい名前', 'email' => 'new@example.com'])
        ->assertRedirect('/profile');

    expect($user->fresh()->name)->toBe('新しい名前');
    expect($user->fresh()->email)->toBe('new@example.com');
});

it('正しい現在のパスワードでパスワードを変更でき監査に残る', function (): void {
    $user = makeUserWith();
    $user->update(['password' => Hash::make('current-password')]);

    actingAs($user)
        ->put('/profile/password', [
            'current_password' => 'current-password',
            'password' => 'BrandNew123!',
            'password_confirmation' => 'BrandNew123!',
        ])
        ->assertRedirect('/profile');

    expect(Hash::check('BrandNew123!', $user->fresh()->password))->toBeTrue();
    expect(AuditLog::query()->where('action', 'profile.password_changed')->exists())->toBeTrue();
});

it('現在のパスワードが誤っていると変更できない', function (): void {
    $user = makeUserWith();
    $user->update(['password' => Hash::make('current-password')]);

    actingAs($user)
        ->put('/profile/password', [
            'current_password' => 'wrong-password',
            'password' => 'BrandNew123!',
            'password_confirmation' => 'BrandNew123!',
        ])
        ->assertSessionHasErrors('current_password', errorBag: 'updatePassword');

    expect(Hash::check('current-password', $user->fresh()->password))->toBeTrue();
});
