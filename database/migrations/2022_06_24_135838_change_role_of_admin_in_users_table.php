<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('users')->where('role_id', 1)->update(['role_id' => 4]);
    }

    public function down()
    {
        DB::table('users')->where('role_id', 4)->update(['role_id' => 1]);
    }
};
