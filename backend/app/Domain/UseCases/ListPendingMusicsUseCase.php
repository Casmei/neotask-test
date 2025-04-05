<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ListPendingMusicsUseCase
{
    private MusicRepositoryInterface $musicRepository;
    private AuthService $authService;

    public function __construct(
        MusicRepositoryInterface $musicRepository,
        AuthService $authService
    ) {
        $this->musicRepository = $musicRepository;
        $this->authService = $authService;
    }

    /**
     * Execute the use case
     *
     * @param array $filters
     * @return array
     */
    public function execute(array $filters = []): array
    {
        $user = $this->authService->getAuthenticatedUser();

        if (!$user->getIsAdmin()) {
            throw ValidationException::withMessages([
                "Você não tem permissão para acessar esta funcionalidade.",
            ])->status(403);
        }

        $filters["approved"] = false;

        $musics = $this->musicRepository->getMusics($filters);
        $total = $this->musicRepository->getTotalCount($filters);

        return [
            "musics" => $musics,
            "total" => $total,
            "current_page" => $filters["page"],
            "per_page" => $filters["limit"],
            "last_page" => ceil($total / $filters["limit"]),
        ];
    }
}
