<?php

declare(strict_types=1);

namespace App\Features\Chat\Responders;

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Exceptions\ChatUnavailableException;
use Illuminate\Support\Facades\Http;

/**
 * Anthropic Claude（Messages API）でチャット応答を生成する。
 */
final class AnthropicResponder implements ChatResponder
{
    public function reply(array $messages): string
    {
        $key = config('services.anthropic.key');

        if (! is_string($key) || $key === '') {
            throw new ChatUnavailableException('ANTHROPIC_API_KEY が未設定です。');
        }

        $response = Http::withHeaders([
            'x-api-key' => $key,
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
            'model' => config('services.anthropic.model', 'claude-haiku-4-5-20251001'),
            'max_tokens' => 1024,
            'system' => $this->systemPrompt(),
            'messages' => $messages,
        ]);

        if ($response->failed()) {
            throw new ChatUnavailableException('Anthropic API が失敗しました（HTTP '.$response->status().'）。');
        }

        $text = $response->json('content.0.text');

        if (! is_string($text) || trim($text) === '') {
            throw new ChatUnavailableException('Anthropic API から空の応答が返りました。');
        }

        return $text;
    }

    private function systemPrompt(): string
    {
        return (string) config('buildone.chat.system_prompt');
    }
}
