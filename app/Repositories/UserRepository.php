<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function getUserByLogin(?string $login)
    {
        if (!$login) {
            return null;
        }

        return User::query()->where('login', $login)->first();
    }

    public function exist(?string $email): ?bool
    {
        if (!$email) {
            return null;
        }

        return User::query()->where('email', $email)->exists();
    }

    public function create(array $attrs)
    {
        User::query()->create($attrs);
    }
}
