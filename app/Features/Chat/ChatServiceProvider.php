<?php

declare(strict_types=1);

namespace App\Features\Chat;

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Responders\AnthropicResponder;
use App\Infrastructure\Modules\ModuleServiceProvider;

final class ChatServiceProvider extends ModuleServiceProvider
{
    protected function name(): string
    {
        return 'chat';
    }

    protected function bindings(): void
    {
        $this->app->bind(ChatResponder::class, AnthropicResponder::class);
    }
}
