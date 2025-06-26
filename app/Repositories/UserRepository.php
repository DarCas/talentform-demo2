<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    function login(string $usernm, string $passwd): User|null
    {
        /**
         * @var \Illuminate\Database\Eloquent\Builder $builder
         */
        $builder = User::where('usernm', $usernm);
        $builder->where('passwd', sha1($passwd));

        return $builder->first();
    }
}
