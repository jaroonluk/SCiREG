<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $connection = 'eoffice';

        if (! Schema::connection($connection)->hasTable('scireg_document_signers')) {
            DB::connection($connection)->statement("
                CREATE TABLE scireg_document_signers (
                    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    role_key VARCHAR(50) NOT NULL,
                    username VARCHAR(100) NULL,
                    is_active TINYINT(1) NOT NULL DEFAULT 0,
                    updated_by VARCHAR(100) NULL,
                    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY uq_role (role_key)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
            ");
        }

        DB::connection($connection)->table('scireg_document_signers')->insertOrIgnore([
            [
                'role_key' => 'acting_for_dean',
                'username' => null,
                'is_active' => 1,
            ],
            [
                'role_key' => 'acting_dean',
                'username' => null,
                'is_active' => 0,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::connection('eoffice')->dropIfExists('scireg_document_signers');
    }
};
