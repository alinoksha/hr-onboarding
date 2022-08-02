<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ScriptSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('scripts')->insert([
                'title' => Str::random(20),
                'description' => Str::random(50),
                'cover_id' => $i,
                'company_id' => 1
            ]);
        }
    }
}
