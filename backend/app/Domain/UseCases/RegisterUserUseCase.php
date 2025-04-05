<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Exceptions\UserAlreadyExistsException;
use App\Domain\Models\User;
use App\Domain\Repositories\Interfaces\UserRepositoryInterface;
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
        $user = new User(
            $request["name"],
            $request["email"],
            Hash::make($request["password"])
        );

        $existingUser = $this->userRepository->findByEmail($user->getEmail());

        if ($existingUser) {
            throw new UserAlreadyExistsException();
        }

        $this->userRepository->register($user);
        $token = $this->authService->generateToken($user);

        return [
            "user" => $user,
            "token" => $token,
        ];
    }
}
