<?php

declare(strict_types=1);

use App\Features\Task\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('task 一覧が表示できる', function (): void {
    Task::factory()->count(3)->create();

    $this->get('/tasks')->assertOk();
});
