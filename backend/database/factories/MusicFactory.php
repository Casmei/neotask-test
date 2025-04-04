<?php

namespace Database\Factories;

use App\Models\Music;
use Illuminate\Database\Eloquent\Factories\Factory;

class MusicFactory extends Factory
{
    protected $model = Music::class;

    public function definition(): array
    {
        $youtubeId = $this->faker->regexify("[A-Za-z0-9]{11}");

        return [
            "title" => $this->faker->sentence(),
            "youtube_id" => $youtubeId,
            "views" => $this->faker->numberBetween(100, 1000000),
            "thumbnail" => "https://img.youtube.com/vi/{$youtubeId}/hqdefault.jpg",
            "approved" => $this->faker->boolean(70),
            "user_id" => \App\Models\User::factory(),
        ];
    }

    /**
     * Indicate that the music is approved.
     */
    public function approved(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                "approved" => true,
            ];
        });
    }

    /**
     * Indicate that the music is pending approval.
     */
    public function pending(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                "approved" => false,
            ];
        });
    }
}
