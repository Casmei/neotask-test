<?php

namespace App\Domain\Repositories\Interfaces;

use App\Domain\Models\User;

interface UserRepositoryInterface
{
    /**
     * Register User
     *
     * @param User $user
     * @return User
     */
    public function register(User $user): User;

    /**
     * Find User by email
     *
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email): ?User;
}
