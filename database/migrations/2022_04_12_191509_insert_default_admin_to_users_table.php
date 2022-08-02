<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'last_name' => 'HR',
            'email' => 'admin@hr-onboarding.com',
            'password' => bcrypt('RZ1eJFIu'),
            'role_id' => 1
        ]);
    }

    public function down()
    {
        DB::table('users')->where('email', 'admin@hr-onboarding.com')->delete();
    }
};
