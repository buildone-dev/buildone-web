<?php

declare(strict_types=1);

namespace App\Features\Task\Http\Controllers;

use App\Features\Task\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

final class TaskController extends Controller
{
    public function index(): View
    {
        return view('task::index', [
            'items' => Task::query()->latest()->get(),
        ]);
    }
}
