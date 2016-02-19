<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
    /**
     * Find a user by id
     *
     * @param  $userId
     * @return Integer
     */
    public function find($userId)
    {
        return User::find($userId);
    }

    /*
     * Return all banned users
     */
    public function banned()
    {
        return User::where('banned', 1)
                    ->get();
    }
}
