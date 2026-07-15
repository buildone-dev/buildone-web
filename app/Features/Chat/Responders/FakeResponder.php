<?php

declare(strict_types=1);

namespace App\Features\Chat\Responders;

use App\Features\Chat\Contracts\ChatResponder;

/**
 * テスト用のダミー応答。外部APIを呼ばない。
 */
final class FakeResponder implements ChatResponder
{
    public function __construct(private readonly string $canned = 'ご相談ありがとうございます。') {}

    public function reply(array $messages): string
    {
        return $this->canned;
    }
}
