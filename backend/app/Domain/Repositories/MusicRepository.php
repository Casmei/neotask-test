<?php
declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Models\Music;
use App\Domain\Repositories\Interfaces\MusicRepositoryInterface;
use App\Models\Music as EloquentMusic;
use Illuminate\Support\Collection;

class MusicRepository implements MusicRepositoryInterface
{
    public function getMusics(array $filters = []): array
    {
        $query = EloquentMusic::query();

        if (isset($filters["approved"])) {
            $query->where("approved", $filters["approved"]);
        }

        if (isset($filters["orderBy"])) {
            $query->orderBy(
                $filters["orderBy"],
                $filters["direction"] ?? "desc"
            );
        }

        $limit = $filters["limit"] ?? 5;
        $page = $filters["page"] ?? 1;
        $offset = ($page - 1) * $limit;

        $musics = $query->skip($offset)->take($limit)->get();

        return $this->mapToDomainModels($musics);
    }

    public function getTotalCount(array $filters): int
    {
        $query = EloquentMusic::query();

        if (isset($filters["approved"])) {
            $query->where("approved", $filters["approved"]);
        }

        return $query->count();
    }

    public function save(Music $music): Music
    {
        $eloquentMusic = EloquentMusic::create([
            "title" => $music->getTitle(),
            "views" => $music->getViews(),
            "youtube_id" => $music->getYoutubeId(),
            "thumbnail" => $music->getThumbnail(),
            "approved" => $music->isApproved(),
            "user_id" => $music->getUserId(),
        ]);

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

    private function mapToDomainModel(EloquentMusic $eloquentMusic): Music
    {
        $music = new Music(
            $eloquentMusic->title,
            $eloquentMusic->youtube_id,
            $eloquentMusic->views,
            $eloquentMusic->thumbnail,
            $eloquentMusic->approved,
            $eloquentMusic->user_id
        );

        $music->setId($eloquentMusic->id);

        return $music;
    }

    private function mapToDomainModels(Collection $eloquentMusics): array
    {
        return $eloquentMusics
            ->map(function ($eloquentMusic) {
                return $this->mapToDomainModel($eloquentMusic);
            })
            ->toArray();
    }
}
