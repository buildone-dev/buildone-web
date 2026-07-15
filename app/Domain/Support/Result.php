<?php

declare(strict_types=1);

namespace App\Domain\Support;

/**
 * ユースケース（Action / Service）の結果を表す軽量な値オブジェクト。
 *
 * 例外に頼らず成功・失敗を明示的に扱うことで、呼び出し側の分岐を型で導く。
 */
final class Result
{
    private function __construct(
        public readonly bool $ok,
        public readonly mixed $value = null,
        public readonly ?string $error = null,
    ) {}

    public static function success(mixed $value = null): self
    {
        return new self(true, $value);
    }

    public static function failure(string $error): self
    {
        return new self(false, null, $error);
    }

    public function failed(): bool
    {
        return ! $this->ok;
    }
}
