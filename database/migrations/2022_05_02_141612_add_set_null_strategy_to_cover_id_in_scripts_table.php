<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('scripts', function (Blueprint $table) {
            $table->dropForeign('scripts_cover_id_foreign');

            $table->unsignedBigInteger('cover_id')->nullable()->change();
            $table
                ->foreign('cover_id')
                ->references('id')
                ->on('media')
                ->onDelete('SET NULL');
        });
    }
};
