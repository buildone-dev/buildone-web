<?php

declare(strict_types=1);

namespace App\Console\Commands\BuildOne;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * BuildOne 環境診断。デプロイ先が正しく動く状態かを安全に確認する。
 *
 *   php artisan buildone:doctor
 */
final class DoctorCommand extends Command
{
    protected $signature = 'buildone:doctor';

    protected $description = 'BuildOne の稼働環境を診断する（PHP・拡張・書込権限・DB・キャッシュ・設定）';

    public function handle(): int
    {
        /** @var list<array{label: string, ok: bool, note: string}> $checks */
        $checks = [];

        $checks[] = ['label' => 'PHP 8.3 以上', 'ok' => version_compare(PHP_VERSION, '8.3.0', '>='), 'note' => PHP_VERSION];

        foreach (['pdo', 'mbstring', 'openssl', 'tokenizer', 'json', 'curl', 'fileinfo'] as $ext) {
            $checks[] = ['label' => "PHP拡張 {$ext}", 'ok' => extension_loaded($ext), 'note' => ''];
        }

        $checks[] = ['label' => 'APP_KEY 設定', 'ok' => is_string(config('app.key')) && config('app.key') !== '', 'note' => ''];
        $checks[] = ['label' => 'APP_DEBUG=false（本番）', 'ok' => ! app()->isProduction() || config('app.debug') === false, 'note' => app()->environment()];
        $checks[] = ['label' => 'storage 書込可', 'ok' => is_writable(storage_path()), 'note' => ''];
        $checks[] = ['label' => 'bootstrap/cache 書込可', 'ok' => is_writable(base_path('bootstrap/cache')), 'note' => ''];

        try {
            DB::connection()->getPdo();
            $dbOk = true;
        } catch (Throwable) {
            $dbOk = false;
        }
        $checks[] = ['label' => 'データベース接続', 'ok' => $dbOk, 'note' => (string) config('database.default')];

        try {
            Cache::put('buildone_doctor', '1', 5);
            $cacheOk = Cache::get('buildone_doctor') === '1';
        } catch (Throwable) {
            $cacheOk = false;
        }
        $checks[] = ['label' => 'キャッシュ', 'ok' => $cacheOk, 'note' => (string) config('cache.default')];

        $checks[] = ['label' => 'AIプロバイダのキー設定', 'ok' => $this->anyAiKey(), 'note' => (string) config('services.ai.providers')];

        $failures = 0;
        foreach ($checks as $check) {
            $mark = $check['ok'] ? '<fg=green>  OK  </>' : '<fg=red;options=bold> NG   </>';
            $note = $check['note'] !== '' ? " <fg=gray>({$check['note']})</>" : '';
            $this->line("{$mark} {$check['label']}{$note}");
            $failures += $check['ok'] ? 0 : 1;
        }

        $this->newLine();

        if ($failures > 0) {
            $this->components->error("{$failures} 件の問題が見つかりました。");

            return self::FAILURE;
        }

        $this->components->info('すべて正常です。');

        return self::SUCCESS;
    }

    private function anyAiKey(): bool
    {
        foreach (['services.openai.key', 'services.gemini.key', 'services.anthropic.key'] as $key) {
            $value = config($key);
            if (is_string($value) && $value !== '') {
                return true;
            }
        }

        return false;
    }
}
