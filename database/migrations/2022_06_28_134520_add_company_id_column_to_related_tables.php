<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('SET NULL');
        });

        DB::table('users')->whereIn('role_id', [1, 2, 3])->update(['company_id' => 1]);

        Schema::table('scripts', function (Blueprint $table) {
            $table->foreignId('company_id')->default(1)->constrained('companies')->onDelete('SET NULL');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('SET NULL');
        });

        DB::table('media')->update(['company_id' => 1]);

    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });

        Schema::table('scripts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });
    }
};
