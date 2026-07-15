<?php

declare(strict_types=1);

namespace App\Features\Chat\Responders;

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Exceptions\ChatUnavailableException;
use Illuminate\Support\Facades\Http;

/**
 * Google Gemini（Generative Language API）でチャット応答を生成する。
 */
final class GeminiResponder implements ChatResponder
{
    public function reply(array $messages): string
    {
        $key = config('services.gemini.key');

        if (! is_string($key) || $key === '') {
            throw new ChatUnavailableException('GOOGLE_GENERATIVE_AI_API_KEY が未設定です。');
        }

        $model = (string) config('services.gemini.model', 'gemini-2.0-flash');

        $contents = array_map(
            static fn (array $message): array => [
                'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $message['content']]],
            ],
            $messages,
        );

        $response = Http::withHeaders(['x-goog-api-key' => $key])
            ->timeout(30)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent", [
                'systemInstruction' => ['parts' => [['text' => (string) config('buildone.chat.system_prompt')]]],
                'contents' => $contents,
            ]);

        if ($response->failed()) {
            throw new ChatUnavailableException('Gemini API が失敗しました（HTTP '.$response->status().'）。');
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            throw new ChatUnavailableException('Gemini API から空の応答が返りました。');
        }

        return $text;
    }
}
