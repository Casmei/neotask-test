<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\UseCases\LoginUserUseCase;
use App\Domain\UseCases\RegisterUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

/**
 * @group User management
 *
 * APIs for managing users
 */
class AuthController extends Controller
{
    private RegisterUserUseCase $registerUserUseCase;
    private LoginUserUseCase $loginUserUseCase;

    public function __construct(
        RegisterUserUseCase $registerUserUseCase,
        LoginUserUseCase $loginUserUseCase
    ) {
        $this->registerUserUseCase = $registerUserUseCase;
        $this->loginUserUseCase = $loginUserUseCase;
    }

    /**
     * Register new user and create token
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        $data = $this->registerUserUseCase->execute($request);

        return response()->json(
            new AuthResource(
                $data["user"],
                $data["token"],
                "Registro realizado com sucesso"
            ),
            Response::HTTP_CREATED
        );
    }

    /**
     * Login user and create token
     */
    public function login(AuthLoginRequest $request): JsonResponse
    {
        $data = $this->loginUserUseCase->execute($request);

        return response()->json(
            new AuthResource(
                $data["user"],
                $data["token"],
                "Login realizado com sucesso"
            ),
            Response::HTTP_OK
        );
    }
}
