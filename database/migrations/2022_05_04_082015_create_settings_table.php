<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('type', ['text', 'textarea', 'photo']);
            $table->string('data')->nullable();
            $table->integer('sorting_order')->unsigned();
            $table->timestamps();
            $table
                ->foreignId('setting_group_id')
                ->constrained('setting_groups')
                ->onDelete('CASCADE');
        });

        DB::table('settings')->insert([
            [
                'name' => 'Company Logo',
                'type' => 'photo',
                'data' => null,
                'sorting_order' => 1,
                'setting_group_id' => 1
            ],
            [
                'name' => 'Company Name',
                'type' => 'text',
                'data' => 'Ronas IT',
                'sorting_order' => 2,
                'setting_group_id' => 1
            ],
            [
                'name' => 'Welcome message',
                'type' => 'textarea',
                'data' => 'Welcome aboard!\<br\>Can`t wait for you to start the process.',
                'sorting_order' => 3,
                'setting_group_id' => 1
            ],
            [
                'name' => 'Bot Profile Image',
                'type' => 'photo',
                'data' => null,
                'sorting_order' => 1,
                'setting_group_id' => 2
            ],
            [
                'name' => 'Bot Name',
                'type' => 'text',
                'data' => 'Anna',
                'sorting_order' => 2,
                'setting_group_id' => 2
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
