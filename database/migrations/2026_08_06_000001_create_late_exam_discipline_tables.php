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

        if (! Schema::connection($this->connection)->hasTable('reason')) {
            $db->statement("
                CREATE TABLE reason (
                    ReasonID INT NOT NULL PRIMARY KEY,
                    ReasonName VARCHAR(255) NOT NULL,
                    ReasonDesc VARCHAR(500) NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
            ");
        }

        $db->table('reason')->upsert([
            [
                'ReasonID' => 1,
                'ReasonName' => 'รถติด/การเดินทาง',
                'ReasonDesc' => 'สภาพการจราจรหรือการเดินทางล่าช้า',
            ],
            [
                'ReasonID' => 2,
                'ReasonName' => 'สุขภาพไม่เอื้ออำนวย',
                'ReasonDesc' => 'มีอาการเจ็บป่วยหรือสุขภาพไม่พร้อม',
            ],
            [
                'ReasonID' => 3,
                'ReasonName' => 'นาฬิกาผิดพลาด/เข้าใจเวลาสอบผิด',
                'ReasonDesc' => 'คลาดเคลื่อนเรื่องเวลาสอบ',
            ],
            [
                'ReasonID' => 4,
                'ReasonName' => 'อื่น ๆ',
                'ReasonDesc' => 'โปรดระบุรายละเอียดเพิ่มเติม',
            ],
            [
                'ReasonID' => 5,
                'ReasonName' => 'เหตุสุดวิสัยอื่น',
                'ReasonDesc' => 'เหตุสุดวิสัยที่ไม่อยู่ในรายการหลัก',
            ],
            [
                'ReasonID' => 6,
                'ReasonName' => 'เอกสาร/อุปกรณ์ไม่พร้อม',
                'ReasonDesc' => 'ลืมหรือเตรียมเอกสารอุปกรณ์สอบไม่ครบ',
            ],
            [
                'ReasonID' => 7,
                'ReasonName' => 'ระบบขนส่งสาธารณะล่าช้า',
                'ReasonDesc' => 'รถเมล์/รถไฟ/ขนส่งสาธารณะล่าช้า',
            ],
            [
                'ReasonID' => 8,
                'ReasonName' => 'อื่น ๆ (ข้อมูลเดิม)',
                'ReasonDesc' => 'สาเหตุอื่นจากข้อมูลย้อนหลัง',
            ],
        ], ['ReasonID'], ['ReasonName', 'ReasonDesc']);

        if (Schema::connection($this->connection)->hasTable('formlate')) {
            $nullCount = (int) $db->table('formlate')->whereNull('formID')->count();
            if ($nullCount > 0) {
                $maxId = (int) ($db->table('formlate')->max('formID') ?? 0);
                $nullRows = $db->table('formlate')->whereNull('formID')->get();
                foreach ($nullRows as $row) {
                    $maxId++;
                    $db->table('formlate')
                        ->whereNull('formID')
                        ->where('STUDENTID', $row->STUDENTID)
                        ->where('LATETIME', $row->LATETIME)
                        ->where('DESCI', $row->DESCI)
                        ->limit(1)
                        ->update(['formID' => $maxId]);
                }
            }

            $indexes = collect($db->select('SHOW INDEX FROM formlate'))
                ->pluck('Key_name')
                ->unique()
                ->all();

            if (! in_array('PRIMARY', $indexes, true)) {
                $db->statement('ALTER TABLE formlate MODIFY formID INT NOT NULL');
                $db->statement('ALTER TABLE formlate ADD PRIMARY KEY (formID)');
            }

            $create = $db->selectOne('SHOW CREATE TABLE formlate');
            $createSql = $create->{'Create Table'} ?? '';
            if (! str_contains(strtoupper($createSql), 'AUTO_INCREMENT')) {
                $db->statement('ALTER TABLE formlate MODIFY formID INT NOT NULL AUTO_INCREMENT');
            }

            $columns = [
                'STUDENTCODE' => 'VARCHAR(20) NULL',
                'STUDENT_NAME' => 'VARCHAR(255) NULL',
                'PROGRAM_NAME' => 'VARCHAR(255) NULL',
                'DEPARTMENT_NAME' => 'VARCHAR(255) NULL',
                'COURSE_CODE' => 'VARCHAR(20) NULL',
                'COURSE_NAME' => 'VARCHAR(255) NULL',
                'ROOM_NAME' => 'VARCHAR(100) NULL',
                'REASON_NAME' => 'VARCHAR(255) NULL',
                'SEMESTER' => 'TINYINT NULL',
                'EXAM_TYPE' => 'VARCHAR(10) NULL',
                'ACADYEAR' => 'INT NULL',
                'CREATED_BY' => 'VARCHAR(100) NULL',
            ];

            foreach ($columns as $name => $definition) {
                if (! Schema::connection($this->connection)->hasColumn('formlate', $name)) {
                    $db->statement("ALTER TABLE formlate ADD COLUMN {$name} {$definition}");
                }
            }

            $latetime = collect($db->select('SHOW COLUMNS FROM formlate LIKE \'LATETIME\''))->first();
            if ($latetime && str_contains(strtolower((string) $latetime->Type), 'date')
                && ! str_contains(strtolower((string) $latetime->Type), 'datetime')
                && ! str_contains(strtolower((string) $latetime->Type), 'timestamp')) {
                $db->statement('ALTER TABLE formlate MODIFY LATETIME DATETIME NULL');
            }
        }

        if (! Schema::connection($this->connection)->hasTable('late_reg_student')) {
            $db->statement("
                CREATE TABLE late_reg_student (
                    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    STUDENTID DECIMAL(12,0) NULL,
                    STUDENTCODE VARCHAR(20) NOT NULL,
                    PREFIXABB VARCHAR(32) NULL,
                    STUDENTNAME VARCHAR(100) NULL,
                    STUDENTSURNAME VARCHAR(100) NULL,
                    PROGRAMNAME VARCHAR(255) NULL,
                    DEPARTMENTNAME VARCHAR(255) NULL,
                    CITIZENID VARCHAR(13) NULL,
                    TERM TINYINT NOT NULL,
                    YEAR INT NOT NULL,
                    imported_at DATETIME NULL,
                    UNIQUE KEY uq_late_reg_code_term_year (STUDENTCODE, TERM, YEAR),
                    KEY idx_late_reg_citizen (CITIZENID),
                    KEY idx_late_reg_term_year (TERM, YEAR)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
            ");
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('late_reg_student');
        // Keep reason and formlate alterations — dropping would destroy historical data.
    }
};
