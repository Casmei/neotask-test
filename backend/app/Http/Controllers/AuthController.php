<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Exceptions\UserAlreadyExistsException;
use App\Domain\UseCases\RegisterUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Resources\AuthRegisterResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @group User management
 *
 * APIs for managing users
 */
class AuthController extends Controller
{
    private RegisterUserUseCase $registerUserUseCase;

    public function __construct(RegisterUserUseCase $registerUserUseCase)
    {
        $this->registerUserUseCase = $registerUserUseCase;
    }

    /**
     * Register new user and create token
     */
    public function register(AuthRegisterRequest $request): JsonResponse
    {
        try {
            $data = $this->registerUserUseCase->execute($request);

            return response()->json(
                new AuthRegisterResource($data["user"], $data["token"]),
                Response::HTTP_CREATED
            );
        } catch (UserAlreadyExistsException $e) {
            /**
             * Já existe um usuário com esse e-mail.
             *
             * @status 409
             */
            return response()->json(
                [
                    "message" => $e->getMessage(),
                ],
                Response::HTTP_CONFLICT
            );
        } catch (\Throwable $e) {
            return response()->json(
                [
                    "message" => "Erro interno no servidor.",
                    "error" => $e->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Login user and create token
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        if (!Auth::attempt($request->only("email", "password"))) {
            throw ValidationException::withMessages([
                "email" => ["Credenciais inválidas"],
            ]);
        }

        $user = User::where("email", $request->email)->firstOrFail();
        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "user" => $user,
            "token" => $token,
            "message" => "Login realizado com sucesso",
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "Logout realizado com sucesso",
        ]);
    }
}
