<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    function run(): void
    {
        $user = User::where('usernm', 'dario@casertano.name')
            ->first();

        if (is_null($user)) {
            $user = new User();
            $user->usernm = 'dario@casertano.name';
        }

        $user->fullname = 'DarCas';
        $user->passwd = 'cd4cb41b1df3269fa09088fe9ece091f39bb09fc';
        $user->save();
    }
}
