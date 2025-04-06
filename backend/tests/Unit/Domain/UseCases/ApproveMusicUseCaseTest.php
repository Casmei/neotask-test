<?php

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Domain\UseCases\ApproveMusicUseCase;
use App\Exceptions\UserFriendlyException;
use PHPUnit\Framework\TestCase;
use Tests\Factories\MusicFactory;
use Tests\Factories\UserFactory;

class ApproveMusicUseCaseTest extends TestCase
{
    private $musicRepository;
    private $approveMusicUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->musicRepository = $this->createMock(
            MusicRepositoryInterface::class
        );

        $this->approveMusicUseCase = new ApproveMusicUseCase(
            $this->musicRepository
        );
    }

    public function testExecuteShouldApproveMusic(): void
    {
        $youtubeId = "123456";

        $user = UserFactory::make([
            "id" => 1,
            "is_admin" => true,
        ]);

        $music = MusicFactory::make([
            "userId" => $user->getId(),
            "youtubeId" => $youtubeId,
        ]);

        $expectedMusic = MusicFactory::approve([
            "userId" => $user->getId(),
            "youtubeId" => $youtubeId,
        ]);

        $this->musicRepository
            ->expects($this->once())
            ->method("findById")
            ->with($youtubeId)
            ->willReturn($music);

        $this->musicRepository
            ->expects($this->once())
            ->method("approve")
            ->with($music);

        $result = $this->approveMusicUseCase->execute($user, $youtubeId);

        $this->assertEquals($expectedMusic, $result);
    }

    public function testExecuteShouldThrowExceptionWhenUserIsNotAdmin(): void
    {
        $youtubeId = "123456";

        $user = UserFactory::make([
            "id" => 1,
            "is_admin" => false,
        ]);

        $this->musicRepository->expects($this->never())->method("findById");

        $this->musicRepository->expects($this->never())->method("approve");

        $this->expectException(UserFriendlyException::class);
        $this->expectExceptionMessage(
            "Você não tem permissão para acessar está funcionalidade."
        );

        $this->approveMusicUseCase->execute($user, $youtubeId);
    }

    public function testExecuteShouldThrowExceptionWhenMusicNotFound(): void
    {
        $youtubeId = "123456";

        $user = UserFactory::make([
            "id" => 1,
            "is_admin" => true,
        ]);

        $this->musicRepository
            ->expects($this->once())
            ->method("findById")
            ->with($youtubeId)
            ->willReturn(null);

        $this->musicRepository->expects($this->never())->method("approve");

        $this->expectException(UserFriendlyException::class);
        $this->expectExceptionMessage(
            "Não foi encontrado uma música com esse Id"
        );

        $this->approveMusicUseCase->execute($user, $youtubeId);
    }

    public function testExecuteShouldNotUpadateWhenMusicAlreadyApproved(): void
    {
        $youtubeId = "123456";

        $user = UserFactory::admin([
            "id" => 1,
        ]);

        $expectedResult = MusicFactory::approve([
            "userId" => $user->getId(),
            "youtubeId" => $youtubeId,
        ]);

        $this->musicRepository
            ->expects($this->once())
            ->method("findById")
            ->with($youtubeId)
            ->willReturn($expectedResult);

        $this->musicRepository->expects($this->never())->method("approve");

        $result = $this->approveMusicUseCase->execute($user, $youtubeId);

        $this->assertEquals($result, $expectedResult);
    }
}
