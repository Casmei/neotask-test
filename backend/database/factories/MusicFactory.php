<?php

namespace Database\Factories;

use App\Models\Music as MusicEloquent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MusicEloquent>
 */
class MusicFactory extends Factory
{
    protected $model = MusicEloquent::class;

    public function definition()
    {
        return [
            "title" => $this->faker->sentence(3),
            "youtube_id" => $this->faker->regexify("[A-Za-z0-9_-]{11}"),
            "views" => $this->faker->numberBetween(1000, 1000000),
            "thumbnail" => $this->faker->imageUrl(),
        ];
    }
}
