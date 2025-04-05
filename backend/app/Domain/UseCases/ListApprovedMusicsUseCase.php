<?php
declare(strict_types=1);

namespace App\Domain\UseCases;

use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;

class ListApprovedMusicsUseCase
{
    private MusicRepositoryInterface $musicRepository;

    public function __construct(MusicRepositoryInterface $musicRepository)
    {
        $this->musicRepository = $musicRepository;
    }

    /**
     * Execute the use case
     *
     * @param array $filters
     * @return array
     */
    public function execute(array $filters = []): array
    {
        $filters["approved"] = true;

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
