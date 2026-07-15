<?php

declare(strict_types=1);

namespace App\Features\Task;

use App\Features\Task\Domain\Contracts\TaskRepository;
use App\Features\Task\Infrastructure\Repositories\EloquentTaskRepository;
use App\Infrastructure\Modules\ModuleServiceProvider;

final class TaskServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'task';
    }

    protected function bindings(): void
    {
        $this->app->bind(TaskRepository::class, EloquentTaskRepository::class);
    }
}
