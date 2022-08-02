<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use RonasIT\Support\Traits\MigrationTrait;

class ScriptsCreateTable extends Migration
{
    use MigrationTrait;

    public function up()
    {
        Schema::create('scripts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('description');
            $table->unsignedBigInteger('cover_id')->nullOnDelete();
            $table->foreign('cover_id')->references('id')->on('media');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scripts');
    }
}
