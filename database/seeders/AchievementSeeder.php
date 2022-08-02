<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('achievements')->insert([
                'script_id' => $i,
                'title' => Str::random(30),
                'incomplete_cover_id' => $i,
                'complete_cover_id' => $i,
                'incomplete_message' => 'msg',
                'complete_message' => 'msg'
            ]);
        }
    }
}
