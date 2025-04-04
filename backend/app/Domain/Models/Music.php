<?php

namespace App\Domain\Models;

class Music
{
    private int $id;
    private string $title;
    private string $youtubeId;
    private int $views;
    private string $thumbnail;
    private ?bool $approved;
    private ?int $userId;

    public function __construct(
        int $id,
        string $title,
        string $youtubeId,
        int $views,
        string $thumbnail,
        ?bool $approved = null,
        ?int $userId = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->youtubeId = $youtubeId;
        $this->views = $views;
        $this->thumbnail = $thumbnail;
        $this->approved = $approved;
        $this->userId = $userId;
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

    public function isApproved(): ?bool
    {
        return $this->approved;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
