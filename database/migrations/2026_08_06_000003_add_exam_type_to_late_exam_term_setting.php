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

        if (! Schema::connection($this->connection)->hasColumn('late_exam_term_setting', 'EXAM_TYPE')) {
            $db->statement("
                ALTER TABLE late_exam_term_setting
                ADD COLUMN EXAM_TYPE VARCHAR(1) NOT NULL DEFAULT 'F'
                AFTER ACADYEAR
            ");
        }

        $db->table('late_exam_term_setting')
            ->where('id', 1)
            ->where(function ($q) {
                $q->whereNull('EXAM_TYPE')->orWhere('EXAM_TYPE', '');
            })
            ->update(['EXAM_TYPE' => 'F']);
    }

    public function down(): void
    {
        if (Schema::connection($this->connection)->hasColumn('late_exam_term_setting', 'EXAM_TYPE')) {
            DB::connection($this->connection)->statement(
                'ALTER TABLE late_exam_term_setting DROP COLUMN EXAM_TYPE'
            );
        }
    }
};
