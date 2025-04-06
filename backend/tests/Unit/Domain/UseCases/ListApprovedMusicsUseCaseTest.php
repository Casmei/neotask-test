<?php

namespace Tests\Unit\Domain\UseCases;

use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Domain\UseCases\ListApprovedMusicsUseCase;
use PHPUnit\Framework\TestCase;
use Tests\Factories\MusicFactory;

class ListApprovedMusicsUseCaseTest extends TestCase
{
    private $musicRepository;
    private $listApprovedMusicsUseCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->musicRepository = $this->createMock(
            MusicRepositoryInterface::class
        );

        $this->listApprovedMusicsUseCase = new ListApprovedMusicsUseCase(
            $this->musicRepository
        );
    }

    public function testExecuteShouldListApprovedMusics(): void
    {
        $filters = [
            "page" => 1,
            "limit" => 10,
        ];

        $expectedFilters = $filters;
        $expectedFilters["approved"] = true;

        $musics = [
            MusicFactory::make(["title" => "Music 1", "userId" => 1]),
            MusicFactory::make(["title" => "Music 2", "userId" => 1]),
        ];
        $total = 2;

        $this->musicRepository
            ->expects($this->once())
            ->method("getMusics")
            ->with($expectedFilters)
            ->willReturn($musics);

        $this->musicRepository
            ->expects($this->once())
            ->method("getTotalCount")
            ->with($expectedFilters)
            ->willReturn($total);

        $result = $this->listApprovedMusicsUseCase->execute($filters);

        $this->assertEquals($musics, $result["musics"]);
        $this->assertEquals($total, $result["total"]);
        $this->assertEquals(1, $result["current_page"]);
        $this->assertEquals(10, $result["per_page"]);
        $this->assertEquals(1, $result["last_page"]);
    }
}
