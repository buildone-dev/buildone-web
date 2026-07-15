<?php

declare(strict_types=1);

namespace App\Features\Task\Application\Services;

use App\Features\Task\Domain\Contracts\TaskRepository;
use App\Features\Task\Domain\Task;
use Illuminate\Support\Collection;

/**
 * Task のユースケースを束ねるアプリケーションサービス。
 */
final class TaskService
{
    public function __construct(
        private readonly TaskRepository $repository,
    ) {}

    /**
     * @return Collection<int, Task>
     */
    public function list(): Collection
    {
        return $this->repository->all();
    }
}
