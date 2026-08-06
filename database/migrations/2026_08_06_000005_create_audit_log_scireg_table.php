<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'discipline';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasTable('audit_log_scireg')) {
            return;
        }

        DB::connection($this->connection)->statement("
            CREATE TABLE audit_log_scireg (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) NULL,
                user_name VARCHAR(255) NULL,
                module VARCHAR(50) NOT NULL,
                action VARCHAR(100) NOT NULL,
                description VARCHAR(500) NULL,
                route_name VARCHAR(150) NULL,
                method VARCHAR(10) NULL,
                url VARCHAR(500) NULL,
                ip_address VARCHAR(45) NULL,
                user_agent VARCHAR(500) NULL,
                request_data JSON NULL,
                status_code SMALLINT UNSIGNED NULL,
                created_at DATETIME NOT NULL,
                KEY idx_audit_created (created_at),
                KEY idx_audit_username (username),
                KEY idx_audit_module (module),
                KEY idx_audit_action (action)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('audit_log_scireg');
    }
};
