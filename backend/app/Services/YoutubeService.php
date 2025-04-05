<?php
declare(strict_types=1);

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class YoutubeService
{
    /**
     * Get video information from YouTube
     *
     * @param string $videoUrl
     * @return array
     * @throws Exception
     */
    public function getVideoInfo(string $videoId): array
    {
        $html = $this->getVideoRequest($videoId);
        $title = $this->getVideoTitle($html);
        $views = (int) $this->getVideoViews($html);

        return [
            "title" => $title,
            "musicId" => $videoId,
            "views" => $views,
            "thumbnail" => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
        ];
    }

    public function extractVideoId(string $url): ?string
    {
        $videoId = null;
        $patterns = [
            "/youtube\.com\/watch\?v=([^&]+)/",
            "/youtu\.be\/([^?]+)/",
            "/youtube\.com\/embed\/([^?]+)/",
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $videoId = $matches[1];
                break;
            }
        }

        return $videoId;
    }

    private function getVideoRequest(string $videoId): string
    {
        $url = "https://www.youtube.com/watch?v={$videoId}";

        $client = new Client([
            "headers" => [
                "User-Agent" =>
                    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36",
            ],
            "verify" => true,
        ]);

        try {
            $response = $client->get($url);
            $html = $response->getBody()->getContents();
        } catch (GuzzleException $e) {
            throw new \Exception(
                "Erro ao acessar o YouTube: " . $e->getMessage()
            );
        }

        return $html;
    }

    private function getVideoTitle(string $html): string
    {
        if (
            !preg_match(
                "/<title>(.+?) - YouTube<\/title>/",
                $html,
                $titleMatches
            )
        ) {
            throw new \Exception(
                "Não foi possível encontrar o título do vídeo"
            );
        }

        $title = html_entity_decode($titleMatches[1], ENT_QUOTES);

        if ($title === "") {
            throw new \Exception("Vídeo não encontrado ou indisponível");
        }

        return $title;
    }

    private function getVideoViews(string $html): int
    {
        if (preg_match('/"viewCount":\s*"(\d+)"/', $html, $viewMatches)) {
            $views = (int) $viewMatches[1];
        } elseif (
            preg_match(
                '/"viewCount"\s*:\s*{.*?"simpleText"\s*:\s*"([\d,.]+)"/',
                $html,
                $viewMatches
            )
        ) {
            $views = (int) str_replace([",", "."], "", $viewMatches[1]);
        } else {
            $views = 0;
        }

        return $views;
    }
}
