<?php
declare(strict_types=1);

namespace App\Services;

use App\Domain\Models\User as DomainUser;
use App\Domain\Repositories\Mappers\UserMapper;

class AuthService
{
    public function generateToken(DomainUser $user): string
    {
        $eloquentUser = UserMapper::toEloquent($user);

        return $eloquentUser->createToken("auth_token")->plainTextToken;
    }
}
