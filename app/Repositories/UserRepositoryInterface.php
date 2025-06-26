<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    function login(string $usernm, string $passwd): User|null;
}
