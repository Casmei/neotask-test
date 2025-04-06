<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Music;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Exceptions\UserFriendlyException;
use App\Services\YoutubeService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class ApproveMusicUseCase
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
     * @param int $musicId
     * @return Music
     */
    public function execute(int $musicId): Music
    {
        $this->authService->getAuthenticatedUser();
        $this->authService->isAdmin();

        $music = $this->musicRepository->findById($musicId);

        if (!$music) {
            throw new UserFriendlyException(
                "Não foi encontrado uma música com esse Id",
                404
            );
        }

        $music->setApproved();

        $this->musicRepository->approve($music);

        return $music;
    }
}
