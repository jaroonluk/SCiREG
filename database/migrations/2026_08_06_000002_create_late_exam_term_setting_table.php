<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'discipline';

    public function up(): void
    {
        $db = DB::connection($this->connection);

        if (! Schema::connection($this->connection)->hasTable('late_exam_term_setting')) {
            $db->statement("
                CREATE TABLE late_exam_term_setting (
                    id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
                    TERM TINYINT NOT NULL,
                    ACADYEAR INT NOT NULL,
                    updated_by VARCHAR(100) NULL,
                    updated_at DATETIME NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
            ");
        }

        $month = (int) date('n');
        $term = ($month >= 6 && $month <= 10) ? 1 : 2;
        $year = (int) date('Y') + 543;

        $db->table('late_exam_term_setting')->updateOrInsert(
            ['id' => 1],
            [
                'TERM' => $term,
                'ACADYEAR' => $year,
                'updated_by' => 'system',
                'updated_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('late_exam_term_setting');
    }
};
