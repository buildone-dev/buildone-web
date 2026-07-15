<?php

declare(strict_types=1);

use App\Core\Settings\Settings;

if (! function_exists('setting')) {
    /**
     * 管理画面で保存された設定を取得する（未設定なら $default）。
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(Settings::class)->get($key, $default);
    }
}

if (! function_exists('setting_bool')) {
    function setting_bool(string $key, bool $default = false): bool
    {
        return app(Settings::class)->bool($key, $default);
    }
}
