<?php

declare(strict_types=1);

namespace App\Features\Chat\Http\Controllers;

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Http\Requests\ChatMessageRequest;
use App\Features\Chat\Models\ChatMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Throwable;

final class ChatController extends Controller
{
    public function message(ChatMessageRequest $request, ChatResponder $responder): JsonResponse
    {
        $validated = $request->validated();

        /** @var list<array{role: string, content: string}> $messages */
        $messages = $validated['messages'];
        $conversationId = $validated['conversation_id'] ?? (string) Str::uuid();

        try {
            $answer = $responder->reply($messages);
            $status = 'answered';
        } catch (Throwable $e) {
            report($e);
            $answer = 'ただいまAIアシスタントが応答できません。お手数ですが、下記の「お問い合わせ」からご相談ください。';
            $status = 'error';
        }

        $this->persist($conversationId, $messages, $answer);

        // すべての応答の末尾に、問い合わせ誘導を必ず含める（固定文）
        return response()->json([
            'status' => $status,
            'conversation_id' => $conversationId,
            'answer' => $answer,
            'show_contact_cta' => true,
            'contact_cta' => config('buildone.contact_cta'),
        ]);
    }

    /**
     * 会話をすべて保存する（直近のユーザー発言とアシスタント応答を追記）。
     *
     * @param  list<array{role: string, content: string}>  $messages
     */
    private function persist(string $conversationId, array $messages, string $answer): void
    {
        $last = end($messages);

        if (is_array($last) && $last['role'] === 'user') {
            ChatMessage::query()->create([
                'conversation_id' => $conversationId,
                'role' => 'user',
                'content' => $last['content'],
            ]);
        }

        ChatMessage::query()->create([
            'conversation_id' => $conversationId,
            'role' => 'assistant',
            'content' => $answer,
        ]);
    }
}
