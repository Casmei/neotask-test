<?php

namespace App\Domain\Repositories\Interfaces;

use App\Domain\Models\Music;

interface MusicRepositoryInterface
{
    /**
     * Get the top musics by views
     *
     * @return array<Music>
     */
    public function getTopMusics(): array;

    /**
     * Get total count of approved musics
     *
     * @return int
     */
    public function getTotalCount(): int;

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
