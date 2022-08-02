<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaSeeder extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('media')->insert([
                'user_id' => 1,
                'link' => '',
                'name' => 'name',
                'company_id' => 1
            ]);
        }
    }
}
