<?php

declare(strict_types=1);

namespace App\Features\Task\Infrastructure\Repositories;

use App\Features\Task\Domain\Contracts\TaskRepository;
use App\Features\Task\Domain\Task;
use App\Infrastructure\Persistence\EloquentRepository;

/**
 * @extends EloquentRepository<Task>
 */
final class EloquentTaskRepository extends EloquentRepository implements TaskRepository
{
    protected function model(): string
    {
        return Task::class;
    }
}
