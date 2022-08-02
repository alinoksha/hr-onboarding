<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropForeign('achievements_incomplete_cover_id_foreign');
            $table->dropForeign('achievements_complete_cover_id_foreign');

            $table->foreignId('incomplete_cover_id')->nullable()->change()->constrained('media')->nullOnDelete();
            $table->foreignId('complete_cover_id')->nullable()->change()->constrained('media')->nullOnDelete();
        });
    }
};
