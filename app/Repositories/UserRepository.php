<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(app(User::class));
    }

    public function filterByEmail(string $email): static
    {
        return $this->filter(static fn(Builder $builder) => $builder->where('email', $email));
    }
}
