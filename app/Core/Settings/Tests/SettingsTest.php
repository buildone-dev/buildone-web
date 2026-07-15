<?php

declare(strict_types=1);

use App\Core\Authorization\Permission;
use App\Core\Settings\Settings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('SettingsManage 権限がないと設定を開けない', function (): void {
    actingAs(makeUserWith([Permission::UsersView->value]))->get('/admin/settings')->assertForbidden();
});

it('設定を保存すると値が反映される', function (): void {
    actingAs(makeUserWith([Permission::SettingsManage->value]))
        ->put('/admin/settings', [
            'youtube_url' => 'https://www.youtube.com/@buildonejp',
            'contact_notify_email' => 'info@buildone.sakura.ne.jp',
            'chat_enabled' => '1',
        ])
        ->assertRedirect('/admin/settings');

    $settings = app(Settings::class);
    expect($settings->get('youtube_url'))->toBe('https://www.youtube.com/@buildonejp');
    expect($settings->get('contact_notify_email'))->toBe('info@buildone.sakura.ne.jp');
    expect($settings->bool('chat_enabled'))->toBeTrue();
});

it('setting ヘルパーが保存値を返す', function (): void {
    app(Settings::class)->set('youtube_url', 'https://example.com/ch');

    expect(setting('youtube_url', 'default'))->toBe('https://example.com/ch');
    expect(setting('unset_key', 'default'))->toBe('default');
});

it('公開トップがYouTube設定を反映する', function (): void {
    app(Settings::class)->set('youtube_url', 'https://www.youtube.com/@buildonejp');

    $this->get('/')->assertOk()->assertSee('https://www.youtube.com/@buildonejp', false);
});
