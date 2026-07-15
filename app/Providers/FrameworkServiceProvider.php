<?php

declare(strict_types=1);

namespace App\Providers;

use App\Infrastructure\Modules\ModuleRegistrar;
use Illuminate\Support\ServiceProvider;

/**
 * BuildOne Framework のカーネル。モジュール自動ディスカバリを起動する。
 */
class FrameworkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        (new ModuleRegistrar)->register($this->app);
    }
}
