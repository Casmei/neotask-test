<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\User;
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
     * @param User $user
     * @param array $filters
     * @return array
     */
    public function execute(User $user, array $filters = []): array
    {
        if (!$user->getIsAdmin()) {
            throw new UserFriendlyException(
                "Você não tem permissão para acessar está funcionalidade.",
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
