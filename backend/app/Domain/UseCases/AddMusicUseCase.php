<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Models\Music;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Services\YoutubeService;
use App\Http\Requests\AddMusicRequest;
use App\Services\AuthService;
use Illuminate\Validation\ValidationException;

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
     * @param AddMusicRequest
     * @return Music
     */
    public function execute(AddMusicRequest $request): Music
    {
        $user = $this->authService->getAuthenticatedUser();

        $musicId = $this->youtubeService->extractVideoId(
            $request["youtube_url"]
        );

        if ($this->musicRepository->findByYoutubeId($musicId)) {
            throw ValidationException::withMessages([
                "youtube_url" => ["Está música já foi cadastrada."],
            ]);
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
