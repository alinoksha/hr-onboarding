<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        $this->createTable();
        $this->addRoles();
    }

    public function createTable()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    public function addRoles()
    {
        $roles = [
            [
                'id' => 1,
                'name' => 'admin',
            ],
            [
                'id' => 2,
                'name' => 'manager',
            ],
            [
                'id' => 3,
                'name' => 'employee',
            ]
        ];
        DB::table('roles')->insert($roles);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
