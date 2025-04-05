<?php
declare(strict_types=1);

namespace App\Services;

use App\Domain\Models\User as DomainUser;
use App\Domain\Repositories\Mappers\UserMapper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class AuthService
{
    public function generateToken(DomainUser $user): string
    {
        $eloquentUser = UserMapper::toEloquent($user);

        return $eloquentUser->createToken("auth_token")->plainTextToken;
    }

    public function checkValidCredential(string $email, string $password): bool
    {
        $isValidCredentials = true;
        if (!Auth::attempt(["email" => $email, "password" => $password])) {
            $isValidCredentials = false;
        }

        return $isValidCredentials;
    }

    public function getAuthenticatedUser(): DomainUser
    {
        $eloquentUser = Auth::user();

        if (!$eloquentUser) {
            throw new UnauthorizedException("Usuário não autenticado.");
        }

        return UserMapper::toDomain($eloquentUser);
    }
}
