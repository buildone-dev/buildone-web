<?php

declare(strict_types=1);

namespace App\Features\Task\Presentation\Http\Controllers;

use App\Features\Task\Application\Services\TaskService;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $service,
    ) {}

    public function index(): View
    {
        return view('task::index', [
            'items' => $this->service->list(),
        ]);
    }
}
