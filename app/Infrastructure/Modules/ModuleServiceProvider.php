<?php

declare(strict_types=1);

namespace App\Infrastructure\Modules;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

/**
 * BuildOne の全モジュール（Core / Features）が継承する基底プロバイダ。
 *
 * モジュールのディレクトリ（Laravel標準寄りの Feature First 構成）を規約に従って自動配線する:
 *   - Database/Migrations  → マイグレーション
 *   - Views                → ビュー（名前空間 = モジュール名）
 *   - Routes/web.php        → Web ルート
 *
 * サブクラスは name() と、必要なら bindings() / registerModule() のみ実装する。
 * Repository / DTO / Service は「必要な機能でだけ」使う（強制しない）。
 */
abstract class ModuleServiceProvider extends ServiceProvider
{
    /**
     * ビュー名前空間などに使うモジュール識別子（小文字）。
     */
    abstract protected function name(): string;

    public function register(): void
    {
        $this->bindings();
    }

    public function boot(): void
    {
        $path = $this->modulePath();

        $migrations = $path.'/Database/Migrations';
        if (is_dir($migrations)) {
            $this->loadMigrationsFrom($migrations);
        }

        $views = $path.'/Views';
        if (is_dir($views)) {
            $this->loadViewsFrom($views, $this->name());
        }

        $routes = $path.'/Routes/web.php';
        if (is_file($routes)) {
            Route::middleware('web')->group($routes);
        }

        $this->registerModule();
    }

    /**
     * 具象プロバイダが置かれたディレクトリ（= モジュールのルート）。
     */
    protected function modulePath(): string
    {
        $file = (new ReflectionClass(static::class))->getFileName();

        return dirname($file !== false ? $file : '');
    }

    /**
     * DI バインディング（Repository 契約 → 実装 など）。サブクラスで上書きする。
     */
    protected function bindings(): void
    {
        //
    }

    /**
     * 追加のブート処理。サブクラスで上書きする。
     */
    protected function registerModule(): void
    {
        //
    }
}
