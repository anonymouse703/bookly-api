<?php

namespace App\Cache\User;

use App\Cache\CacheBase;
use App\Cache\WithHelpers;
use App\Models\User as Model;
use App\Repositories\Contracts\UserRepositoryInterface;

/**
 * @method Model|null fetch()
 * @method Model fetchOrFail()
 */
class UserByEmail extends CacheBase
{
    use WithHelpers;

    public function __construct(protected string $email)
    {
        parent::__construct("users.{$email}", now()->addHour());
    }

    protected function cacheMiss()
    {
        return app(UserRepositoryInterface::class)->filterByEmail($this->email)->first();
    }

    protected function errorModelName(): string
    {
        return "User";
    }

    protected function errorModelId()
    {
        return $this->email;
    }
}
