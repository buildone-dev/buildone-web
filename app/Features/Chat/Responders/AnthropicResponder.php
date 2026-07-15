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
        return <<<'PROMPT'
        あなたは「BuildOne」のWebサイトに設置されたAIアシスタントです。
        BuildOneは、AIを活用して中小企業向けの業務システム（入退室管理・勤怠・シフト・予約・日報など）を
        1週間単位で開発・公開し、その過程をYouTubeで発信するプロジェクトです。

        # 役割
        - 経営者や現場担当者からの、業務のお悩みやシステム化の相談に、日本語で丁寧かつ簡潔に答える。
        - 専門用語を避け、相手の業務目線で分かりやすく説明する。
        - BuildOneでどんなことができそうかを、具体例を交えて前向きに提案する。

        # 制約
        - 事実を推測で断定しない。料金・納期・機能の確約はしない（「ご相談ください」と案内する）。
        - 個人情報や機密情報の入力を求めない。
        - 回答は3〜5文程度を目安に、長くなりすぎないようにする。
        - 分からないことや対応範囲外のことは、正直にその旨を伝える。
        PROMPT;
    }
}
