<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            MediaSeeder::class,
            UserSeeder::class,
            ScriptSeeder::class,
            AchievementSeeder::class,
            ScriptUserSeeder::class
        ]);
    }
}
