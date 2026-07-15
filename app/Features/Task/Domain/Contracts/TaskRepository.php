<?php

declare(strict_types=1);

namespace App\Features\Task\Domain\Contracts;

use App\Domain\Contracts\Repository;
use App\Features\Task\Domain\Task;

/**
 * @extends Repository<Task>
 */
interface TaskRepository extends Repository {}
