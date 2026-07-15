<?php

declare(strict_types=1);

namespace App\Features\Chat\Contracts;

use App\Features\Chat\Exceptions\ChatUnavailableException;

interface ChatResponder
{
    /**
     * 会話履歴を受け取り、アシスタントの返答テキストを返す。
     *
     * @param  list<array{role: string, content: string}>  $messages
     *
     * @throws ChatUnavailableException 応答できないとき
     */
    public function reply(array $messages): string;
}
