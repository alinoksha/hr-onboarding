<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $this->addNullableField();
        $this->addDisplayNames();
        $this->removeNullableFromFiled();
    }

    private function addNullableField()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('display_name')->nullable()->unique();
        });
    }

    private function removeNullableFromFiled()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->string('display_name')->nullable(false)->change();
        });
    }

    private function addDisplayNames()
    {
        DB::table('roles')->where('id', Role::ADMIN)->update(['display_name' => 'Administrator']);
        DB::table('roles')->where('id', Role::MANAGER)->update(['display_name' => 'Manager']);
        DB::table('roles')->where('id', Role::EMPLOYEE)->update(['display_name' => 'Employee']);
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['display_name']);
        });
    }
};
