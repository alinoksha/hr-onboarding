<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement('
            DELETE FROM achievements
            WHERE id NOT IN
                (SELECT MAX(id) FROM achievements GROUP BY script_id);
        ');

        Schema::table('achievements', function (Blueprint $table) {
            $table->unique('script_id');
        });
    }

    public function down()
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropUnique('achievements_script_id_unique');
        });
    }
};
