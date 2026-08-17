<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'discipline';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasColumn('formlate', 'SEAT_NO')) {
            DB::connection($this->connection)->statement("
                ALTER TABLE formlate
                ADD COLUMN SEAT_NO VARCHAR(20) NULL
                AFTER ROOM_NAME
            ");
        }
    }

    public function down(): void
    {
        if (Schema::connection($this->connection)->hasColumn('formlate', 'SEAT_NO')) {
            DB::connection($this->connection)->statement(
                'ALTER TABLE formlate DROP COLUMN SEAT_NO'
            );
        }
    }
};
