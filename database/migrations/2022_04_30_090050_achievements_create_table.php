<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RonasIT\Support\Traits\MigrationTrait;

class AchievementsCreateTable extends Migration
{
    use MigrationTrait;

    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('script_id')->constrained()->onDelete('cascade');
            $table->string('title')->unique();
            $table->foreignId('incomplete_cover_id')->constrained('media')->nullOnDelete();
            $table->foreignId('complete_cover_id')->constrained('media')->nullOnDelete();
            $table->text('incomplete_message');
            $table->text('complete_message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('achievements');
    }
}
