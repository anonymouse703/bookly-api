<?php

namespace App\Cache\User;

use App\Cache\CacheBase;
use App\Models\User as Model;

/**
 * @method Model|null fetch()
 * @method Model fetchOrFail()
 */
class UserById extends CacheBase
{
    public function __construct(protected int $id)
    {
        parent::__construct("users.{$id}", now()->addHour());
    }

    protected function cacheMiss()
    {
        return Model::find($this->id);
    }

    protected function errorModelName(): string
    {
        return "User";
    }

    protected function errorModelId()
    {
        return $this->id;
    }
}
