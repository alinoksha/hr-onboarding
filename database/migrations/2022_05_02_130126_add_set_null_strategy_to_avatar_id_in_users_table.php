<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_avatar_id_foreign');

            $table
                ->foreign('avatar_id')
                ->references('id')
                ->on('media')
                ->onDelete('SET NULL');
        });
    }
};
