<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Exceptions\UserFriendlyException;
use App\Services\AuthService;

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
            throw new UserFriendlyException(
                "Você não tem permissão para acessar esta funcionalidade.",
                403
            );
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
