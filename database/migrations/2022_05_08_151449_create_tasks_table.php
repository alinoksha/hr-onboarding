<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->enum('response_type', ['radio', 'checkbox', 'text', 'media']);
            $table->jsonb('response_options')->nullable();
            $table->jsonb('expected_response')->nullable();
            $table->foreignId('script_id')->constrained()->onDelete('CASCADE');
            $table->unique(['title', 'script_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
