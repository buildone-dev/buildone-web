<?php

declare(strict_types=1);

namespace App\Features\Task;

use App\Infrastructure\Modules\ModuleServiceProvider;

final class TaskServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'task';
    }

    // 永続化の差し替えや複雑な境界が必要になったら bindings() で
    // Repository 契約 → 実装 を束ねる（App\Domain\Contracts\Repository を参照）。
}
