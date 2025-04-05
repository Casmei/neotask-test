<?php

use App\Exceptions\UserFriendlyException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . "/../routes/api.php",
        commands: __DIR__ . "/../routes/console.php",
        health: "/up"
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tratamento para UserFriendlyException
        $exceptions->render(function (UserFriendlyException $e, $request) {
            return response()->json(
                [
                    "message" => $e->getMessage(),
                ],
                $e->getCode() ?: 400
            );
        });

        // Tratamento para outras exceções HTTP
        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            return response()->json(
                [
                    "message" => $e->getMessage() ?: "Erro HTTP",
                ],
                $e->getStatusCode()
            );
        });

        // Tratamento genérico para outras exceções
        $exceptions->render(function (\Throwable $e, $request) {
            return response()->json(
                [
                    "message" => "Erro interno no servidor",
                ],
                500
            );
        });
    })
    ->create();
