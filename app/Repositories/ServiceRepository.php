<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\ServiceRepositoryInterface;

class ServiceRepository extends BaseRepository implements ServiceRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(app(Service::class));
    }

    public function filterById(string $serviceId): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->where('id', $serviceId));
    }

    public function filterByCategoryId(string $categoryId): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->where('category_id', $categoryId));
    }
}
