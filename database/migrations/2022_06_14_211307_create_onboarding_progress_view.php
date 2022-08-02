<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('
            CREATE VIEW onboarding_progress AS
                SELECT
                    t.total,
                    t.completed,
                    t.user_id,
                    TRUNC(t.completed/t.total*100, 1) AS percent
                FROM (
                    SELECT
                        SUM(total) AS total,
                        SUM(completed) AS completed,
                        user_id
                    FROM script_progress
                    GROUP BY user_id
                ) t;
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW onboarding_progress');
    }
};
