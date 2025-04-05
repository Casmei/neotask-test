<?php

namespace Database\Seeders;

use App\Models\Music as MusicEloquent;
use App\Models\User as UserEloquent;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // cria um admin
        UserEloquent::factory()
            ->admin()
            ->create([
                "email" => "admin@example.com",
            ]);

        // cria um usuário comum
        $user = UserEloquent::factory()->create([
            "email" => "user@example.com",
        ]);

        // cria 5 músicas para esse usuário
        MusicEloquent::factory()
            ->count(5)
            ->create([
                "user_id" => $user->id,
            ]);
    }
}
