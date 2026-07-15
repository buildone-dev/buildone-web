<?php

declare(strict_types=1);

use App\Features\Chat\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::post('/chat/message', [ChatController::class, 'message'])
    ->middleware('throttle:20,1')
    ->name('chat.message');
