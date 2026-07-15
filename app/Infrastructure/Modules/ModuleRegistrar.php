<?php

declare(strict_types=1);

namespace App\Infrastructure\Modules;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * app/Core と app/Features 配下のモジュールを走査し、
 * 各モジュールの ServiceProvider を自動登録する。
 *
 * 命名規約: app/{Group}/{Module}/{Module}ServiceProvider.php
 *           → App\{Group}\{Module}\{Module}ServiceProvider
 */
final class ModuleRegistrar
{
    /**
     * モジュールを格納するグループ（app 直下のディレクトリ名）。
     *
     * @var list<string>
     */
    private array $groups = ['Core', 'Features'];

    public function register(Application $app): void
    {
        foreach ($this->groups as $group) {
            $base = $app->path($group);

            if (! is_dir($base)) {
                continue;
            }

            $dirs = glob($base.'/*', GLOB_ONLYDIR);

            foreach ($dirs !== false ? $dirs : [] as $dir) {
                $module = basename($dir);
                /** @var class-string<ServiceProvider> $provider */
                $provider = "App\\{$group}\\{$module}\\{$module}ServiceProvider";

                if (class_exists($provider)) {
                    $app->register($provider);
                }
            }
        }
    }
}
