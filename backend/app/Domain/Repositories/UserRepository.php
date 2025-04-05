<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\User;
use App\Models\User as EloquentUser;
use App\Domain\Repositories\Interfaces\UserRepositoryInterface;
use App\Domain\Repositories\Mappers\UserMapper;

class UserRepository implements UserRepositoryInterface
{
    public function register(User $user): User
    {
        $eloquentUser = EloquentUser::create([
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword(),
        ]);

        $user->setId($eloquentUser->id);

        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        $eloquentUser = EloquentUser::where("email", $email)->first();

        if (isset($eloquentUser)) {
            return UserMapper::toDomain($eloquentUser);
        }

        return null;
    }
}
