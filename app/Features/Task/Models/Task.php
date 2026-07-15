<?php

declare(strict_types=1);

namespace App\Features\Task\Models;

use App\Features\Task\Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'tasks';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }
}
