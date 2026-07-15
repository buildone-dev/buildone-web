<?php

declare(strict_types=1);

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(TestCase::class, RefreshDatabase::class);

it('パスワード再設定リクエスト画面が表示できる', function (): void {
    get('/forgot-password')->assertOk()->assertSee('パスワードの再設定');
});

it('登録メールに再設定リンクが送られる', function (): void {
    Notification::fake();
    $user = makeUserWith();

    post('/forgot-password', ['email' => $user->email])->assertSessionHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

it('有効なトークンでパスワードを再設定できる', function (): void {
    $user = makeUserWith();
    $token = Password::createToken($user);

    post('/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'BrandNew123!',
        'password_confirmation' => 'BrandNew123!',
    ])->assertRedirect('/login');

    expect(Hash::check('BrandNew123!', $user->fresh()->password))->toBeTrue();
});

it('不正なトークンでは再設定できない', function (): void {
    $user = makeUserWith();
    $user->update(['password' => Hash::make('original-password')]);

    post('/reset-password', [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'BrandNew123!',
        'password_confirmation' => 'BrandNew123!',
    ])->assertSessionHasErrors('email');

    expect(Hash::check('original-password', $user->fresh()->password))->toBeTrue();
});
