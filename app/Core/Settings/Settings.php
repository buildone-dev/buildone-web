<?php

declare(strict_types=1);

namespace App\Core\Settings;

use App\Core\Settings\Models\Setting;
use Throwable;

/**
 * アプリ設定の読み書き。管理画面で編集した値を環境変数より優先して返す。
 * 1リクエスト内はキャッシュする。
 */
final class Settings
{
    /**
     * @var array<string, string|null>|null
     */
    private ?array $cache = null;

    /**
     * @return array<string, string|null>
     */
    public function all(): array
    {
        if ($this->cache === null) {
            try {
                /** @var array<string, string|null> $values */
                $values = Setting::query()->pluck('value', 'key')->all();
                $this->cache = $values;
            } catch (Throwable) {
                // マイグレーション前などテーブルが無い場合は空扱い
                $this->cache = [];
            }
        }

        return $this->cache;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->all()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    public function bool(string $key, bool $default = false): bool
    {
        $value = $this->all()[$key] ?? null;

        if ($value === null) {
            return $default;
        }

        return in_array($value, ['1', 'true', 'on', 'yes'], true);
    }

    public function set(string $key, ?string $value): void
    {
        Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        $this->cache = null;
    }
}
