<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('roles')->insert([
            'id' => 4,
            'name' => 'super_admin',
            'display_name' => 'Super Administrator'
        ]);
    }

    public function down()
    {
        DB::table('roles')->where('id', 4)->delete();
    }
};
