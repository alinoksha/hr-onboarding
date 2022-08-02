<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->string('position')->nullable();
            $table->timestamp('starts_on')->nullable();
            $table->boolean('is_onboarding_required')->nullable();
            $table->foreignId('hr_id')->nullable()->constrained('roles');
            $table->foreignId('manager_id')->nullable()->constrained('roles');
            $table->foreignId('lead_id')->nullable()->constrained('roles');
            $table->foreignId('avatar_id')->nullable()->constrained('media');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('hr_id');
            $table->dropConstrainedForeignId('manager_id');
            $table->dropConstrainedForeignId('lead_id');
            $table->dropConstrainedForeignId('avatar_id');
            $table->dropColumn([
                'date_of_birth',
                'phone',
                'position',
                'starts_on',
                'is_onboarding_required'
            ]);
        });
    }
};
