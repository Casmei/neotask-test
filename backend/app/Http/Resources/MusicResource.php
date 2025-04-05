<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MusicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->resource->getId(),
            "title" => $this->resource->getTitle(),
            "youtube_id" => $this->resource->getYoutubeId(),
            "formatted_views" => $this->formatViews(
                $this->resource->getViews()
            ),
            "thumbnail" => $this->resource->getThumbnail(),
            "url" => "https://www.youtube.com/watch?v={$this->resource->getYoutubeId()}",
        ];
    }

    /**
     * Format views number
     */
    private function formatViews(int $views): string
    {
        if ($views >= 1000000) {
            return round($views / 1000000, 1) . "M";
        }

        if ($views >= 1000) {
            return round($views / 1000, 1) . "K";
        }

        return (string) $views;
    }
}
