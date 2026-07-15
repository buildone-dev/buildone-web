<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Eloquent を用いた Repository の共通実装。各 Feature はこれを継承し model() だけ実装する。
 *
 * @template TModel of Model
 *
 * @implements Repository<TModel>
 */
abstract class EloquentRepository implements Repository
{
    /**
     * このリポジトリが扱うモデルのクラス名。
     *
     * @return class-string<TModel>
     */
    abstract protected function model(): string;

    /**
     * @return TModel
     */
    protected function newModel(): Model
    {
        $class = $this->model();

        return new $class;
    }

    public function all(): Collection
    {
        /** @var Collection<int, TModel> $result */
        $result = $this->newModel()->newQuery()->get();

        return $result;
    }

    public function find(int|string $id): ?Model
    {
        /** @var TModel|null $result */
        $result = $this->newModel()->newQuery()->find($id);

        return $result;
    }

    public function create(array $attributes): Model
    {
        /** @var TModel $result */
        $result = $this->newModel()->newQuery()->create($attributes);

        return $result;
    }

    public function update(int|string $id, array $attributes): bool
    {
        $model = $this->find($id);

        if ($model === null) {
            return false;
        }

        return $model->update($attributes);
    }

    public function delete(int|string $id): bool
    {
        $model = $this->find($id);

        if ($model === null) {
            return false;
        }

        return (bool) $model->delete();
    }
}
