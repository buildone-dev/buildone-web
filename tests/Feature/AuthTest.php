<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('未認証は管理画面からログインへリダイレクトされる', function (): void {
    get('/admin')->assertRedirect('/login');
});

it('ログイン画面が表示できる', function (): void {
    get('/login')->assertOk()->assertSee('ログイン');
});

it('正しい認証情報でログインしダッシュボードへ進める', function (): void {
    $user = makeUserWith(active: true);
    $user->update(['password' => Hash::make('secret-password')]);

    post('/login', ['email' => $user->email, 'password' => 'secret-password'])
        ->assertRedirect('/admin');

    expect(auth()->check())->toBeTrue();
});

it('誤ったパスワードは拒否される', function (): void {
    $user = makeUserWith();
    $user->update(['password' => Hash::make('secret-password')]);

    post('/login', ['email' => $user->email, 'password' => 'wrong'])
        ->assertSessionHasErrors('email');

    expect(auth()->check())->toBeFalse();
});

it('無効化されたユーザーはログインできない', function (): void {
    $user = makeUserWith(active: false);
    $user->update(['password' => Hash::make('secret-password')]);

    post('/login', ['email' => $user->email, 'password' => 'secret-password'])
        ->assertSessionHasErrors('email');

    expect(auth()->check())->toBeFalse();
});

it('ログアウトできる', function (): void {
    $user = makeUserWith();

    actingAs($user)->post('/logout')->assertRedirect('/login');

    expect(auth()->check())->toBeFalse();
});
