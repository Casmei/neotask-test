<?php

namespace App\Domain\Models;

class Music
{
    private int $id;
    private string $title;
    private string $youtubeId;
    private int $views;
    private string $thumbnail;

    public function __construct(
        int $id,
        string $title,
        string $youtubeId,
        int $views,
        string $thumbnail
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->youtubeId = $youtubeId;
        $this->views = $views;
        $this->thumbnail = $thumbnail;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getYoutubeId(): string
    {
        return $this->youtubeId;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function getThumbnail(): string
    {
        return $this->thumbnail;
    }
}
