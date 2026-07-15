<?php

declare(strict_types=1);

use App\Features\Task\Presentation\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
