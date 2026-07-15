<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * 永続化の抽象。Domain 層は具体的な Eloquent 実装ではなくこの契約に依存する。
 *
 * @template TModel of Model
 */
interface Repository
{
    /**
     * @return Collection<int, TModel>
     */
    public function all(): Collection;

    /**
     * @return TModel|null
     */
    public function find(int|string $id): ?Model;

    /**
     * @param  array<string, mixed>  $attributes
     * @return TModel
     */
    public function create(array $attributes): Model;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(int|string $id, array $attributes): bool;

    public function delete(int|string $id): bool;
}
