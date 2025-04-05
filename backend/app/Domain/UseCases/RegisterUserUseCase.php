<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\User;
use App\Domain\Repositories\Interfaces\UserRepositoryInterface;
use App\Exceptions\UserFriendlyException;
use App\Http\Requests\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Hash;

class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private AuthService $authService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        AuthService $authService
    ) {
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    /**
     * Execute the use case
     *
     * @return array
     */
    public function execute(AuthRegisterRequest $request)
    {
        if ($this->userRepository->findByEmail($request["email"])) {
            throw new UserFriendlyException(
                "Já existe um usuário com esse e-mail.",
                422
            );
        }

        $user = new User(
            $request["name"],
            $request["email"],
            Hash::make($request["password"])
        );

        $this->userRepository->register($user);
        $token = $this->authService->generateToken($user);

        return [
            "user" => $user,
            "token" => $token,
        ];
    }
}
