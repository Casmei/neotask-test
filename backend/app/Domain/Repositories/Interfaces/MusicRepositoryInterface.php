<?php

namespace App\Domain\Repositories\Interfaces;

use App\Domain\Models\Music;

interface MusicRepositoryInterface
{
    /**
     * Get musics with pagination
     *
     * @param int $limit
     * @param int $page
     * @param array $filters
     * @return array<Music>
     */
    public function getMusics(array $filters): array;

    /**
     * Get total count of musics
     *
     * @param array $filters
     * @return int
     */
    public function getTotalCount(array $filters): int;

    /**
     * Save a new music
     *
     * @param Music $music
     * @return Music
     */
    public function save(Music $music): Music;

    /**
     * Find music by YouTube ID
     *
     * @param string $youtubeId
     * @return Music|null
     */
    public function findByYoutubeId(string $youtubeId): ?Music;
}
