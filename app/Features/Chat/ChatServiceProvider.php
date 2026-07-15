<?php

declare(strict_types=1);

namespace App\Features\Chat;

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Responders\AnthropicResponder;
use App\Features\Chat\Responders\ChainResponder;
use App\Features\Chat\Responders\GeminiResponder;
use App\Features\Chat\Responders\OpenAiResponder;
use App\Infrastructure\Modules\ModuleServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

final class ChatServiceProvider extends ModuleServiceProvider
{
    /**
     * @var array<string, class-string<ChatResponder>>
     */
    private const PROVIDERS = [
        'openai' => OpenAiResponder::class,
        'gemini' => GeminiResponder::class,
        'anthropic' => AnthropicResponder::class,
    ];

    protected function name(): string
    {
        return 'chat';
    }

    protected function bindings(): void
    {
        // config('services.ai.providers') の順に試すフォールバックチェーンを構築する
        $this->app->bind(ChatResponder::class, function (Application $app): ChainResponder {
            $order = Str::of((string) config('services.ai.providers', 'openai,gemini,anthropic'))
                ->explode(',')
                ->map(fn (string $name): string => trim($name))
                ->filter(fn (string $name): bool => isset(self::PROVIDERS[$name]));

            $responders = $order
                ->map(fn (string $name): ChatResponder => $app->make(self::PROVIDERS[$name]))
                ->values()
                ->all();

            return new ChainResponder($responders);
        });
    }
}
