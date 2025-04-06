<?php

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Models\Music;
use App\Domain\Models\User;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Domain\UseCases\AddMusicUseCase;
use App\Services\YoutubeService;
use App\Exceptions\UserFriendlyException;
use PHPUnit\Framework\TestCase;
use Tests\Factories\UserFactory;

class AddMusicUseCaseTest extends TestCase
{
    private $musicRepository;
    private $youtubeService;
    private $addMusicUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->musicRepository = $this->createMock(
            MusicRepositoryInterface::class
        );
        $this->youtubeService = $this->createMock(YoutubeService::class);

        $this->addMusicUseCase = new AddMusicUseCase(
            $this->musicRepository,
            $this->youtubeService
        );
    }

    public function testExecuteShouldAddMusic(): void
    {
        $youtubeUrl = "https://www.youtube.com/watch?v=123456";
        $youtubeId = "123456";

        $user = UserFactory::make([
            "id" => 1,
            "is_admin" => false,
        ]);

        $request = [
            "youtube_url" => $youtubeUrl,
        ];

        $videoInfo = [
            "title" => "Test Music Title",
            "musicId" => $youtubeId,
            "views" => 1000,
            "thumbnail" => "https://img.youtube.com/vi/123456/hqdefault.jpg",
        ];

        $expectedMusic = new Music(
            $videoInfo["title"],
            $videoInfo["musicId"],
            $videoInfo["views"],
            $videoInfo["thumbnail"],
            $user->getIsAdmin(),
            $user->getId()
        );

        $this->youtubeService
            ->expects($this->once())
            ->method("extractVideoId")
            ->with($youtubeUrl)
            ->willReturn($youtubeId);

        $this->musicRepository
            ->expects($this->once())
            ->method("findByYoutubeId")
            ->with($youtubeId)
            ->willReturn(null);

        $this->youtubeService
            ->expects($this->once())
            ->method("getVideoInfo")
            ->with($youtubeId)
            ->willReturn($videoInfo);

        $this->musicRepository
            ->expects($this->once())
            ->method("save")
            ->willReturn($expectedMusic);

        $result = $this->addMusicUseCase->execute($user, $request);

        $this->assertEquals($expectedMusic, $result);
    }

    public function testExecuteShouldAddMusicAlreadyApproved(): void
    {
        $youtubeUrl = "https://www.youtube.com/watch?v=123456";
        $youtubeId = "123456";

        $user = UserFactory::make([
            "id" => 1,
            "is_admin" => true,
        ]);

        $request = [
            "youtube_url" => $youtubeUrl,
        ];

        $videoInfo = [
            "title" => "Test Music Title",
            "musicId" => $youtubeId,
            "views" => 1000,
            "thumbnail" => "https://img.youtube.com/vi/123456/hqdefault.jpg",
        ];

        $expectedMusic = new Music(
            $videoInfo["title"],
            $videoInfo["musicId"],
            $videoInfo["views"],
            $videoInfo["thumbnail"],
            $user->getIsAdmin(),
            $user->getId()
        );

        $expectedMusic->setApproved();

        $this->youtubeService
            ->expects($this->once())
            ->method("extractVideoId")
            ->with($youtubeUrl)
            ->willReturn($youtubeId);

        $this->musicRepository
            ->expects($this->once())
            ->method("findByYoutubeId")
            ->with($youtubeId)
            ->willReturn(null);

        $this->youtubeService
            ->expects($this->once())
            ->method("getVideoInfo")
            ->with($youtubeId)
            ->willReturn($videoInfo);

        $this->musicRepository
            ->expects($this->once())
            ->method("save")
            ->willReturn($expectedMusic);

        $result = $this->addMusicUseCase->execute($user, $request);

        $this->assertEquals($expectedMusic, $result);
        $this->assertTrue($result->isApproved());
    }

    public function testExecuteShouldThrowExceptionWhenYoutubeUrlIsInvalid(): void
    {
        $youtubeUrl = "https://www.youtube.com/invalid";

        $user = UserFactory::make([
            "id" => 1,
            "is_admin" => false,
        ]);

        $request = [
            "youtube_url" => $youtubeUrl,
        ];

        $this->youtubeService
            ->expects($this->once())
            ->method("extractVideoId")
            ->with($youtubeUrl)
            ->willReturn(null);

        $this->expectException(UserFriendlyException::class);
        $this->expectExceptionMessage("URL do YouTube inválida");

        $this->addMusicUseCase->execute($user, $request);
    }

    public function testExecuteShouldThrowExceptionWhenMusicAlreadyExists(): void
    {
        $youtubeUrl = "https://www.youtube.com/watch?v=123456";
        $youtubeId = "123456";

        $user = new User("Tiago", "tiago@example.com", "senha123");
        $user->setId(1)->setIsAdmin(false);

        $request = [
            "youtube_url" => $youtubeUrl,
        ];

        $existingMusic = new Music(
            "Existing Title",
            "123456",
            1000,
            "https://img.youtube.com/vi/123456/hqdefault.jpg",
            true,
            2
        );

        $this->youtubeService
            ->expects($this->once())
            ->method("extractVideoId")
            ->with($youtubeUrl)
            ->willReturn($youtubeId);

        $this->musicRepository
            ->expects($this->once())
            ->method("findByYoutubeId")
            ->with($youtubeId)
            ->willReturn($existingMusic);

        $this->expectException(UserFriendlyException::class);
        $this->expectExceptionMessage("Esta música já foi cadastrada");

        $this->addMusicUseCase->execute($user, $request);
    }
}
