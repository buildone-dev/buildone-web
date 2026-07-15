<?php

declare(strict_types=1);

namespace App\Features\Chat\Responders;

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Exceptions\ChatUnavailableException;
use Throwable;

/**
 * 複数のプロバイダを優先順に試し、最初に成功した応答を返す。
 * あるプロバイダが残高不足やエラーで失敗しても、次のプロバイダへ自動フォールバックする。
 */
final class ChainResponder implements ChatResponder
{
    /**
     * @param  list<ChatResponder>  $responders  優先順のプロバイダ
     */
    public function __construct(private readonly array $responders) {}

    public function reply(array $messages): string
    {
        $last = null;

        foreach ($this->responders as $responder) {
            try {
                return $responder->reply($messages);
            } catch (Throwable $e) {
                $last = $e;
            }
        }

        throw new ChatUnavailableException('すべてのAIプロバイダが応答できませんでした。', 0, $last);
    }
}
