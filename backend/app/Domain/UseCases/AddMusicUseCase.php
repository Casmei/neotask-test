<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Music;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Exceptions\UserFriendlyException;
use App\Services\YoutubeService;
use App\Http\Requests\AddMusicRequest;
use App\Services\AuthService;

class AddMusicUseCase
{
    private MusicRepositoryInterface $musicRepository;
    private AuthService $authService;
    private YoutubeService $youtubeService;

    public function __construct(
        MusicRepositoryInterface $musicRepository,
        AuthService $authService,
        YoutubeService $youtubeService
    ) {
        $this->musicRepository = $musicRepository;
        $this->authService = $authService;
        $this->youtubeService = $youtubeService;
    }

    /**
     * Execute the use case
     *
     * @param array<AddMusicRequest>
     * @return Music
     */
    public function execute(array $request): Music
    {
        $user = $this->authService->getAuthenticatedUser();

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
