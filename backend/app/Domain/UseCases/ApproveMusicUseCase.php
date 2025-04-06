<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Music;
use App\Domain\Models\User;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Exceptions\UserFriendlyException;

class ApproveMusicUseCase
{
    private MusicRepositoryInterface $musicRepository;

    public function __construct(MusicRepositoryInterface $musicRepository)
    {
        $this->musicRepository = $musicRepository;
    }

    /**
     * Execute the use case
     * @param User $user
     * @param int $musicId
     * @return Music
     */
    public function execute(User $user, int $musicId): Music
    {
        if (!$user->getIsAdmin()) {
            throw new UserFriendlyException(
                "Você não tem permissão para acessar está funcionalidade.",
                403
            );
        }

        $music = $this->musicRepository->findById($musicId);

        if (!$music) {
            throw new UserFriendlyException(
                "Não foi encontrado uma música com esse Id",
                404
            );
        }

        if (!$music->isApproved()) {
            $music->setApproved();
            $this->musicRepository->approve($music);
        }

        return $music;
    }
}
