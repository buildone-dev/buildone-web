<?php

declare(strict_types=1);

use App\Features\Chat\Contracts\ChatResponder;
use App\Features\Chat\Exceptions\ChatUnavailableException;
use App\Features\Chat\Responders\ChainResponder;
use App\Features\Chat\Responders\FakeResponder;

function failingResponder(): ChatResponder
{
    return new class implements ChatResponder
    {
        public function reply(array $messages): string
        {
            throw new ChatUnavailableException('残高不足');
        }
    };
}

it('最初のプロバイダが失敗したら次にフォールバックする', function (): void {
    $chain = new ChainResponder([
        failingResponder(),
        new FakeResponder('OpenAIの回答'),
    ]);

    expect($chain->reply([['role' => 'user', 'content' => 'test']]))->toBe('OpenAIの回答');
});

it('全プロバイダが失敗したら例外を投げる', function (): void {
    $chain = new ChainResponder([failingResponder(), failingResponder()]);

    $chain->reply([['role' => 'user', 'content' => 'test']]);
})->throws(ChatUnavailableException::class);

it('最初のプロバイダが成功したらそれを返す', function (): void {
    $chain = new ChainResponder([
        new FakeResponder('最優先の回答'),
        new FakeResponder('使われない'),
    ]);

    expect($chain->reply([['role' => 'user', 'content' => 'test']]))->toBe('最優先の回答');
});
