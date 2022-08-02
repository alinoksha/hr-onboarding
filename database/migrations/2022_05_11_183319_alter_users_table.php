<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_hr_id_foreign');
            $table->dropForeign('users_manager_id_foreign');
            $table->dropForeign('users_lead_id_foreign');

            $table->foreign('hr_id')->references('id')->on('users');
            $table->foreign('manager_id')->references('id')->on('users');
            $table->foreign('lead_id')->references('id')->on('users');
        });
    }
};
