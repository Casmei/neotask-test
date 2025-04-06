<?php
declare(strict_types=1);

namespace Tests\Factories;

use App\Domain\Models\Music;
use Illuminate\Support\Str;

class MusicFactory
{
    public static function make(array $overrides = []): Music
    {
        $title = $overrides["title"] ?? "Música teste";
        $youtubeId = $overrides["youtubeId"] ?? "j_OFdn3Sc8I";
        $views = $overrides["views"] ?? 298482;
        $thumbnail =
            $overrides["thumbnail"] ??
            "https://img.youtube.com/vi/j_OFdn3Sc8I/hqdefault.jpg";
        $approved = $overrides["approved"] ?? false;
        $userId = $overrides["userId"];

        $music = new Music(
            $title,
            $youtubeId,
            $views,
            $thumbnail,
            $approved,
            $userId
        );

        if (isset($overrides["id"])) {
            $music->setId($overrides["id"]);
        }

        return $music;
    }

    public static function approve(array $overrides = []): Music
    {
        return self::make(
            array_merge(
                [
                    "approved" => true,
                ],
                $overrides
            )
        );
    }
}
