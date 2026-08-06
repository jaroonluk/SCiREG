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

        if (! Schema::connection($this->connection)->hasColumn('reason', 'is_active')) {
            $db->statement('ALTER TABLE reason ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1 AFTER ReasonDesc');
        }
        if (! Schema::connection($this->connection)->hasColumn('reason', 'sort_order')) {
            $db->statement('ALTER TABLE reason ADD COLUMN sort_order INT NOT NULL DEFAULT 100 AFTER is_active');
        }

        $rows = [
            [
                'ReasonID' => 9,
                'ReasonName' => 'แต่งกายผิดระเบียบ กลับไปแต่งตัวให้เรียบร้อย',
                'ReasonDesc' => 'แต่งกายไม่ถูกต้องตามระเบียบการสอบ',
                'is_active' => 1,
                'sort_order' => 10,
            ],
            [
                'ReasonID' => 1,
                'ReasonName' => 'ปัญหาการจราจร เช่น ฝนตก รถติด',
                'ReasonDesc' => 'รวมกรณีฝนตก รถติด และการจราจรล่าช้า',
                'is_active' => 1,
                'sort_order' => 20,
            ],
            [
                'ReasonID' => 7,
                'ReasonName' => 'อุปสรรคในการเดินทาง เช่น รถเสีย ยางแบน น้ำมันหมด',
                'ReasonDesc' => 'รวมรถเสีย ยางแบน น้ำมันหมด หรืออุปสรรคเดินทางอื่น',
                'is_active' => 1,
                'sort_order' => 30,
            ],
            [
                'ReasonID' => 10,
                'ReasonName' => 'ตื่นสาย',
                'ReasonDesc' => 'ตื่นสายทำให้มาสอบไม่ทันเวลา',
                'is_active' => 1,
                'sort_order' => 40,
            ],
            [
                'ReasonID' => 2,
                'ReasonName' => 'ปัญหาด้านสุขภาพ เช่น ท้องเสีย ปวดหัว',
                'ReasonDesc' => 'รวมอาการเจ็บป่วยหรือสุขภาพไม่พร้อม',
                'is_active' => 1,
                'sort_order' => 50,
            ],
            [
                'ReasonID' => 6,
                'ReasonName' => 'ลืมอุปกรณ์ที่ใช้ในการสอบ',
                'ReasonDesc' => 'ลืมพกเอกสารหรืออุปกรณ์ที่ใช้ในการสอบ',
                'is_active' => 1,
                'sort_order' => 60,
            ],
            [
                'ReasonID' => 3,
                'ReasonName' => 'จำเวลาสอบผิด',
                'ReasonDesc' => 'คลาดเคลื่อนเรื่องวันหรือเวลาสอบ',
                'is_active' => 1,
                'sort_order' => 70,
            ],
            [
                'ReasonID' => 4,
                'ReasonName' => 'อื่น ๆ',
                'ReasonDesc' => 'โปรดระบุรายละเอียดเพิ่มเติม',
                'is_active' => 1,
                'sort_order' => 100,
            ],
            [
                'ReasonID' => 5,
                'ReasonName' => 'เหตุสุดวิสัยอื่น',
                'ReasonDesc' => 'ข้อมูลเดิม — ไม่แสดงในฟอร์มบันทึกใหม่',
                'is_active' => 0,
                'sort_order' => 900,
            ],
            [
                'ReasonID' => 8,
                'ReasonName' => 'อื่น ๆ (ข้อมูลเดิม)',
                'ReasonDesc' => 'ข้อมูลเดิม — ไม่แสดงในฟอร์มบันทึกใหม่',
                'is_active' => 0,
                'sort_order' => 910,
            ],
        ];

        foreach ($rows as $row) {
            $db->table('reason')->updateOrInsert(
                ['ReasonID' => $row['ReasonID']],
                $row
            );
        }
    }

    public function down(): void
    {
        // Keep columns; only reverse active flags for historical safety if needed.
    }
};
