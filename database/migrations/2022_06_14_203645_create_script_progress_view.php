<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('
            CREATE VIEW script_progress AS
                SELECT
                    script_user.user_id,
                    script_user.script_id,
                    scripts.title,
                    COALESCE(c.completed, 0) AS completed,
                    t.total
                FROM script_user
                JOIN (
                    SELECT
                        script_id,
                        COUNT(1) AS total
                    FROM tasks
                    GROUP BY script_id
                ) t
                ON t.script_id = script_user.script_id
                LEFT JOIN (
                    SELECT
                        answers.user_id,
                        tasks.script_id,
                        COUNT(1) AS completed
                    FROM answers
                    JOIN tasks ON answers.task_id = tasks.id
                    GROUP BY answers.user_id, tasks.script_id
                ) c
                ON c.user_id = script_user.user_id AND c.script_id = script_user.script_id
                JOIN scripts ON scripts.id = script_user.script_id;
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW script_progress');
    }
};
