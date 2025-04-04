<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Music;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Models\Music as EloquentMusic;
use Illuminate\Support\Collection;

class MusicRepository implements MusicRepositoryInterface
{
    public function getTopMusics(): array
    {
        $musics = EloquentMusic::orderBy("views", "desc")->get();

        return $this->mapToDomainModels($musics);
    }

    public function getTotalCount(): int
    {
        return EloquentMusic::where("approved", true)->count();
    }

    public function save(Music $music): Music
    {
        $eloquentMusic = EloquentMusic::updateOrCreate(
            ["youtube_id" => $music->getYoutubeId()],
            [
                "title" => $music->getTitle(),
                "views" => $music->getViews(),
                "thumbnail" => $music->getThumbnail(),
            ]
        );

        return $this->mapToDomainModel($eloquentMusic);
    }

    public function findByYoutubeId(string $youtubeId): ?Music
    {
        $music = EloquentMusic::where("youtube_id", $youtubeId)->first();

        if (!$music) {
            return null;
        }

        return $this->mapToDomainModel($music);
    }

    /**
     * Map Eloquent model to domain model
     */
    private function mapToDomainModel(EloquentMusic $eloquentMusic): Music
    {
        return new Music(
            $eloquentMusic->id,
            $eloquentMusic->title,
            $eloquentMusic->youtube_id,
            $eloquentMusic->views,
            $eloquentMusic->thumbnail
        );
    }

    /**
     * Map collection of Eloquent models to array of domain models
     */
    private function mapToDomainModels(Collection $eloquentMusics): array
    {
        return $eloquentMusics
            ->map(function ($eloquentMusic) {
                return $this->mapToDomainModel($eloquentMusic);
            })
            ->toArray();
    }
}
