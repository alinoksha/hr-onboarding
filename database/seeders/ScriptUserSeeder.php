<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScriptUserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('script_user')->insert([
                'user_id' => 1,
                'script_id' => $i
            ]);
        }
    }
}
