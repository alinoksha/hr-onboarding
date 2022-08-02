<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('TRUNCATE TABLE settings RESTART IDENTITY;');

        Schema::table('settings', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained('companies')->onDelete('CASCADE');
        });

        DB::table('settings')->insert([
            [
                'name' => 'Company Logo',
                'type' => 'photo',
                'data' => null,
                'sorting_order' => 1,
                'setting_group_id' => 1,
                'company_id' => 1
            ],
            [
                'name' => 'Welcome message',
                'type' => 'textarea',
                'data' => 'Welcome aboard!\<br\>Can`t wait for you to start the process.',
                'sorting_order' => 2,
                'setting_group_id' => 1,
                'company_id' => 1
            ],
            [
                'name' => 'Bot Profile Image',
                'type' => 'photo',
                'data' => null,
                'sorting_order' => 1,
                'setting_group_id' => 2,
                'company_id' => 1
            ],
            [
                'name' => 'Bot Name',
                'type' => 'text',
                'data' => 'Anna',
                'sorting_order' => 2,
                'setting_group_id' => 2,
                'company_id' => 1
            ]
        ]);
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('company_id');
        });
    }
};
