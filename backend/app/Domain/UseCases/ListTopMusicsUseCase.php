<?php

namespace App\Domain\UseCases;

use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;

class ListTopMusicsUseCase
{
    private MusicRepositoryInterface $musicRepository;

    public function __construct(MusicRepositoryInterface $musicRepository)
    {
        $this->musicRepository = $musicRepository;
    }

    /**
     * Execute the use case
     *
     * @return array
     */
    public function execute(): array
    {
        $musics = $this->musicRepository->getTopMusics();
        $total = $this->musicRepository->getTotalCount();

        return [
            "musics" => $musics,
            "total" => $total,
        ];
    }
}
