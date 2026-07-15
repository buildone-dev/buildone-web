<?php

declare(strict_types=1);

namespace App\Infrastructure\Modules;

use Illuminate\Support\ServiceProvider;
use ReflectionClass;

/**
 * BuildOne の全モジュール（Core / Features）が継承する基底プロバイダ。
 *
 * モジュールのディレクトリ（Feature First の縦割りスライス）を規約に従って自動配線する:
 *   - Infrastructure/Database/Migrations  → マイグレーション
 *   - Presentation/Views                  → ビュー（名前空間 = モジュール名）
 *   - Presentation/Routes/web.php         → Web ルート
 *
 * サブクラスは name() と、必要なら bindings() / registerModule() のみ実装する。
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

        $migrations = $path.'/Infrastructure/Database/Migrations';
        if (is_dir($migrations)) {
            $this->loadMigrationsFrom($migrations);
        }

        $views = $path.'/Presentation/Views';
        if (is_dir($views)) {
            $this->loadViewsFrom($views, $this->name());
        }

        $routes = $path.'/Presentation/Routes/web.php';
        if (is_file($routes)) {
            $this->loadRoutesFrom($routes);
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
