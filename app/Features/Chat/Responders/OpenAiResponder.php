<?php

declare(strict_types=1);

namespace App\Features\Chat\Responders;

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Exceptions\ChatUnavailableException;
use Illuminate\Support\Facades\Http;

/**
 * OpenAI（Chat Completions API）でチャット応答を生成する。
 */
final class OpenAiResponder implements ChatResponder
{
    public function reply(array $messages): string
    {
        $key = config('services.openai.key');

        if (! is_string($key) || $key === '') {
            throw new ChatUnavailableException('OPENAI_API_KEY が未設定です。');
        }

        $response = Http::withToken($key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'max_tokens' => 1024,
                'messages' => array_merge(
                    [['role' => 'system', 'content' => (string) config('buildone.chat.system_prompt')]],
                    $messages,
                ),
            ]);

        if ($response->failed()) {
            throw new ChatUnavailableException('OpenAI API が失敗しました（HTTP '.$response->status().'）。');
        }

        $text = $response->json('choices.0.message.content');

        if (! is_string($text) || trim($text) === '') {
            throw new ChatUnavailableException('OpenAI API から空の応答が返りました。');
        }

        return $text;
    }
}
