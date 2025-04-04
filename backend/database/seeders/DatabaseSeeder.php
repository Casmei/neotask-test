<?php

namespace Database\Seeders;

use App\Models\Music as MusicEloquent;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        MusicEloquent::factory()->count(5)->create();
    }
}
