<?php

namespace App\Cache\Service;

use App\Cache\CacheBase;
use App\Models\Service as Model;

/**
 * @method Model|null fetch()
 * @method Model fetchOrFail()
 */
class ServiceById extends CacheBase
{
    public function __construct(protected int $id)
    {
        parent::__construct("services.{$id}", now()->addHour());
    }

    protected function cacheMiss()
    {
        return Model::find($this->id);
    }

    protected function errorModelName(): string
    {
        return "Service";
    }

    protected function errorModelId()
    {
        return $this->id;
    }
}
