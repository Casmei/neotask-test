<?php

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Models\User;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Domain\UseCases\ListPendingMusicsUseCase;
use App\Exceptions\UserFriendlyException;
use PHPUnit\Framework\TestCase;
use Tests\Factories\MusicFactory;
use Tests\Factories\UserFactory;

class ListPendingMusicsUseCaseTest extends TestCase
{
    private $musicRepository;
    private $listPendingMusicsUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->musicRepository = $this->createMock(
            MusicRepositoryInterface::class
        );
        $this->listPendingMusicsUseCase = new ListPendingMusicsUseCase(
            $this->musicRepository
        );
    }

    public function testExecuteShouldListPendingMusicsWhenUserIsAdmin(): void
    {
        $user = UserFactory::make([
            "id" => 1,
            "is_admin" => true,
        ]);

        $filters = [
            "page" => 1,
            "limit" => 10,
        ];

        $pendingMusics = [
            MusicFactory::make([
                "approved" => false,
                "userId" => $user->getId(),
            ]),
            MusicFactory::make([
                "approved" => false,
                "userId" => $user->getId(),
            ]),
        ];

        $this->musicRepository
            ->expects($this->once())
            ->method("getMusics")
            ->with(array_merge($filters, ["approved" => false]))
            ->willReturn($pendingMusics);

        $this->musicRepository
            ->expects($this->once())
            ->method("getTotalCount")
            ->with(array_merge($filters, ["approved" => false]))
            ->willReturn(2);

        $result = $this->listPendingMusicsUseCase->execute($user, $filters);

        $this->assertEquals(
            [
                "musics" => $pendingMusics,
                "total" => 2,
                "current_page" => 1,
                "per_page" => 10,
                "last_page" => 1,
            ],
            $result
        );
    }

    public function testExecuteShouldThrowExceptionIfUserIsNotAdmin(): void
    {
        $user = UserFactory::make([
            "id" => 2,
            "is_admin" => false,
        ]);

        $this->expectException(UserFriendlyException::class);
        $this->expectExceptionMessage(
            "Você não tem permissão para acessar está funcionalidade."
        );
        $this->expectExceptionCode(403);

        $this->listPendingMusicsUseCase->execute($user, [
            "page" => 1,
            "limit" => 10,
        ]);
    }
}
