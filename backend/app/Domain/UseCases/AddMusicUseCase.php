<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Music;
use App\Domain\Models\User;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Exceptions\UserFriendlyException;
use App\Services\YoutubeService;
use App\Http\Requests\AddMusicRequest;

class AddMusicUseCase
{
    private MusicRepositoryInterface $musicRepository;
    private YoutubeService $youtubeService;

    public function __construct(
        MusicRepositoryInterface $musicRepository,
        YoutubeService $youtubeService
    ) {
        $this->musicRepository = $musicRepository;
        $this->youtubeService = $youtubeService;
    }

    /**
     * Execute the use case
     * @param User
     * @param array<AddMusicRequest>
     * @return Music
     */
    public function execute(User $user, array $request): Music
    {
        $musicId = $this->youtubeService->extractVideoId(
            $request["youtube_url"]
        );

        if (!$musicId) {
            throw new UserFriendlyException("URL do YouTube inválida", 403);
        }

        if ($this->musicRepository->findByYoutubeId($musicId)) {
            throw new UserFriendlyException(
                "Esta música já foi cadastrada",
                403
            );
        }

        $musicInfo = $this->youtubeService->getVideoInfo($musicId);

        $music = new Music(
            $musicInfo["title"],
            $musicInfo["musicId"],
            $musicInfo["views"],
            $musicInfo["thumbnail"],
            $user->getIsAdmin(),
            $user->getId()
        );

        return $this->musicRepository->save($music);
    }
}
