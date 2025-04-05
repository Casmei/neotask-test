<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Repositories\Interfaces\UserRepositoryInterface;
use App\Http\Requests\AuthLoginRequest;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;

class LoginUserUseCase
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
    public function execute(AuthLoginRequest $request)
    {
        $isValidCredentials = $this->authService->checkValidCredential(
            $request["email"],
            $request["password"]
        );

        if (!$isValidCredentials) {
            throw ValidationException::withMessages([
                "email" => ["Credenciais inválidas"],
            ]);
        }

        $user = $this->userRepository->findByEmail($request["email"]);
        $token = $this->authService->generateToken($user);

        return [
            "user" => $user,
            "token" => $token,
        ];
    }
}
