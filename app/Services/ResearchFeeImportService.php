<?php

namespace App\Services;

use App\Models\FeeResearch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResearchFeeImportService
{
    public const FACULTY_SCIENCE = 2;

    public const STUDYING_STATUS = 10;

    private const EXCLUDED_DEPARTMENT_ID = 7;

    /**
     * @return Collection<int, object>
     */
    public function fetchFromReg(int $term, int $year): Collection
    {
        return DB::connection('reg')
            ->table('studentmaster as sm')
            ->join('levelid as l', 'sm.LEVELID', '=', 'l.LEVELID')
            ->join('studentstatus as ss', function ($join) use ($term, $year) {
                $join->on('sm.STUDENTID', '=', 'ss.STUDENTID')
                    ->where('ss.ACADYEAR', '=', $year)
                    ->where('ss.SEMESTER', '=', $term)
                    ->where('ss.STUDENTSTATUS', '=', self::STUDYING_STATUS);
            })
            ->leftJoin('prefix as p', 'sm.PREFIXID', '=', 'p.PREFIXID')
            ->leftJoin('program as pr', 'sm.PROGRAMID', '=', 'pr.PROGRAMID')
            ->leftJoin('department as d', function ($join) {
                $join->on('sm.FACULTYID', '=', 'd.FACULTYID')
                    ->on('sm.DEPARTMENTID', '=', 'd.DEPARTMENTID');
            })
            ->where('sm.FACULTYID', self::FACULTY_SCIENCE)
            ->whereIn('l.LEVELGROUPID', [5, 7])
            ->orderBy('l.LEVELGROUPID')
            ->orderBy('d.DEPARTMENTNAME')
            ->orderBy('sm.STUDENTCODE')
            ->select([
                'sm.STUDENTCODE',
                'sm.STUDENTNAME',
                'sm.STUDENTSURNAME',
                'p.PREFIXABB',
                'l.LEVELABB',
                'l.LEVELGROUPID',
                'pr.PROGRAMNAME',
                'd.DEPARTMENTNAME',
            ])
            ->get();
    }

    /**
     * @return array{term:int,year:int,reg_total:int,existing:int,to_insert:int,inserted:int,skipped:int,preview:array<int,array<string,mixed>>}
     */
    public function preview(int $term, int $year): array
    {
        $rows = $this->fetchFromReg($term, $year);
        $mapped = $rows
            ->map(fn ($row) => $this->mapRow($row, $term, $year))
            ->reject(fn (array $row) => $row['depart_id'] === self::EXCLUDED_DEPARTMENT_ID)
            ->values();

        $existingCodes = FeeResearch::query()
            ->where('term', (string) $term)
            ->where('year', (string) $year)
            ->pluck('std_code')
            ->all();

        $existingLookup = array_fill_keys($existingCodes, true);
        $toInsert = $mapped->reject(fn (array $row) => isset($existingLookup[$row['std_code']]));

        return [
            'term' => $term,
            'year' => $year,
            'reg_total' => $mapped->count(),
            'existing' => count($existingCodes),
            'to_insert' => $toInsert->count(),
            'inserted' => 0,
            'skipped' => $mapped->count() - $toInsert->count(),
            'preview' => $toInsert->take(20)->values()->all(),
        ];
    }

    /**
     * @return array{term:int,year:int,reg_total:int,existing:int,to_insert:int,inserted:int,skipped:int,preview:array<int,array<string,mixed>>}
     */
    public function import(int $term, int $year): array
    {
        $rows = $this->fetchFromReg($term, $year);
        $mapped = $rows
            ->map(fn ($row) => $this->mapRow($row, $term, $year))
            ->reject(fn (array $row) => $row['depart_id'] === self::EXCLUDED_DEPARTMENT_ID)
            ->values();

        $existingCodes = FeeResearch::query()
            ->where('term', (string) $term)
            ->where('year', (string) $year)
            ->pluck('std_code')
            ->all();

        $existingLookup = array_fill_keys($existingCodes, true);
        $toInsert = $mapped
            ->reject(fn (array $row) => isset($existingLookup[$row['std_code']]))
            ->values();

        $inserted = 0;

        DB::connection('eoffice')->transaction(function () use ($toInsert, &$inserted) {
            foreach ($toInsert->chunk(200) as $chunk) {
                DB::connection('eoffice')
                    ->table('fee_research')
                    ->insert($chunk->values()->all());
                $inserted += $chunk->count();
            }
        });

        return [
            'term' => $term,
            'year' => $year,
            'reg_total' => $mapped->count(),
            'existing' => count($existingCodes),
            'to_insert' => $toInsert->count(),
            'inserted' => $inserted,
            'skipped' => $mapped->count() - $toInsert->count(),
            'preview' => $toInsert->take(20)->values()->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapRow(object $row, int $term, int $year): array
    {
        $levelGroup = (int) $row->LEVELGROUPID;
        $levelAbb = trim((string) ($row->LEVELABB ?? ''));
        $level = match (true) {
            str_contains($levelAbb, 'เอก') => 'เอก',
            str_contains($levelAbb, 'โท') => 'โท',
            $levelGroup === 7 => 'เอก',
            default => 'โท',
        };

        $prefix = trim((string) ($row->PREFIXABB ?? ''));
        $firstName = trim((string) ($row->STUDENTNAME ?? ''));
        $lastName = trim((string) ($row->STUDENTSURNAME ?? ''));
        $name = preg_replace('/\s+/u', ' ', trim($prefix.$firstName.' '.$lastName)) ?? '';

        return [
            'std_code' => (string) $row->STUDENTCODE,
            'name' => $name,
            'level' => $level,
            'couse' => (string) ($row->PROGRAMNAME ?? ''),
            'depart_id' => $this->mapDepartId(
                (string) ($row->DEPARTMENTNAME ?? ''),
                (string) ($row->PROGRAMNAME ?? '')
            ),
            'status' => '1',
            'amount' => 0,
            'term' => (string) $term,
            'year' => (string) $year,
            'slip_no' => null,
        ];
    }

    private function mapDepartId(string $departmentName, string $programName): int
    {
        $haystack = $departmentName.' '.$programName;

        $rules = [
            10 => ['นิติวิทยาศาสตร์'],
            11 => ['วิทยาศาสตร์ชีวภาพ'],
            12 => ['วัสดุศาสตร์'],
            1 => ['คณิตศาสตร์'],
            2 => ['เคมี'],
            3 => ['จุลชีววิทยา'],
            4 => ['ชีวเคมี'],
            7 => ['วิทยาการคอมพิวเตอร์', 'คอมพิวเตอร์'],
            8 => ['วิทยาศาสตร์สิ่งแวดล้อม', 'สิ่งแวดล้อม'],
            9 => ['สถิติ'],
            6 => ['ฟิสิกส์'],
            5 => ['ชีววิทยา'],
        ];

        foreach ($rules as $departId => $keywords) {
            foreach ($keywords as $keyword) {
                if ($keyword !== '' && str_contains($haystack, $keyword)) {
                    return $departId;
                }
            }
        }

        return 0;
    }
}
