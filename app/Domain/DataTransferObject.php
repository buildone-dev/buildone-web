<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * レイヤー間で受け渡す不変データの基底。
 *
 * Presentation（FormRequest）で検証済みの値を Application/Domain へ渡すときに使う。
 * Eloquent モデルを層をまたいで持ち回らないための境界オブジェクト。
 */
abstract class DataTransferObject
{
    /**
     * @param  array<string, mixed>  $data
     */
    abstract public static function fromArray(array $data): static;

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;
}
