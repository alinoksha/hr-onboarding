<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setting_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('setting_groups')->insert([
            [
                'id' => 1,
                'name' => 'Company'
            ],
            [
                'id' => 2,
                'name' => 'Chat Bot'
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('setting_groups');
    }
};
