<?php

namespace App\Repositories;

use App\Cache\CacheBase;
use App\Cache\Paginated\CachePaginatedBase;
use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Conditionable;

abstract class BaseRepository implements RepositoryInterface
{
    use Conditionable;

    private Builder $queryBuilder;
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->queryBuilder = $this->model
            ->newQuery()
            // Added the default base select to have the ability to add more selects using builder addSelect()
            ->select($model->getTable().".*");
    }

    protected function filter(callable $filter): static
    {
        $cloned = clone $this;
        $filter($cloned->queryBuilder);

        return $cloned;
    }

    protected function query(): Builder
    {
        return clone $this->queryBuilder;
    }

    protected function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }

    public function find(mixed $id): Model|null
    {
        return $this->query()->find($id);
    }

    public function save(Model $model): bool
    {
        return $model->save();
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    public function limit(int $limit): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->limit($limit));
    }

    public function pluck($column): \Illuminate\Support\Collection
    {
        return $this->queryBuilder->pluck($column);
    }

    public function orderBy($column, $direction = 'asc'): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->orderBy($column, $direction));
    }

    public function get(): Collection
    {
        return $this->queryBuilder->get();
    }

    public function paginate($perPage = 10): LengthAwarePaginator
    {
        return $this->queryBuilder->paginate($perPage);
    }

    public function simplePaginate($perPage = 10): Paginator
    {
        return $this->queryBuilder->simplePaginate($perPage);
    }

    public function with(array $relationships): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->with($relationships));
    }

    public function withCount(mixed $relations): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->withCount($relations));
    }

    public function chunkById(int $count, callable $callback): bool
    {
        return $this->query()->chunkById($count, $callback);
    }

    public function exists(): bool
    {
        return $this->queryBuilder->exists();
    }

    public function count(): int
    {
        return $this->query()->count();
    }

    public function sum(Expression|string $column): mixed
    {
        return $this->queryBuilder->sum($column);
    }

    public function toRawSql(): string
    {
        return $this->query()->toRawSql();
    }

    public function first(): ?Model
    {
        return $this->query()->first();
    }

    public function batchDelete(): int
    {
        return $this->query()->delete();
    }

    public function random(): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->inRandomOrder());
    }

    public function fromCache(CacheBase|CachePaginatedBase $cacheProvider) : Collection|Paginator|LengthAwarePaginator|Model
    {
        return $cacheProvider->fetch();
    }

    public function distinct(): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->distinct());
    }
}
