<?php

namespace App\Services;

use App\Models\FormLate;
use App\Models\LateReason;
use App\Models\LateRegStudent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LateExamService
{
    public const FACULTY_SCIENCE = 2;

    public const STUDYING_STATUS = 10;

    /**
     * @return Collection<int, object>
     */
    public function fetchFromReg(int $term, int $year): Collection
    {
        return DB::connection('reg')
            ->table('studentmaster as sm')
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
            // vstudentbio: faculty bio snapshot (ไม่มี CITIZENID — คอลัมน์นั้นมีเฉพาะ studentbio)
            ->leftJoin('vstudentbio as bio', 'sm.STUDENTID', '=', 'bio.STUDENTID')
            ->where('sm.FACULTYID', self::FACULTY_SCIENCE)
            ->orderBy('d.DEPARTMENTNAME')
            ->orderBy('sm.STUDENTCODE')
            ->select([
                'sm.STUDENTID',
                'sm.STUDENTCODE',
                'sm.STUDENTNAME',
                'sm.STUDENTSURNAME',
                'p.PREFIXABB',
                'pr.PROGRAMNAME',
                'd.DEPARTMENTNAME',
                DB::raw('NULL as CITIZENID'),
            ])
            ->get();
    }

    /**
     * @return array{term:int,year:int,reg_total:int,existing:int,imported:int,preview:array<int,array<string,mixed>>}
     */
    public function previewImport(int $term, int $year): array
    {
        $rows = $this->fetchFromReg($term, $year);
        $existing = LateRegStudent::query()
            ->where('TERM', $term)
            ->where('YEAR', $year)
            ->count();

        $preview = $rows->take(20)->map(fn ($row) => $this->mapImportRow($row, $term, $year))->all();

        return [
            'term' => $term,
            'year' => $year,
            'reg_total' => $rows->count(),
            'existing' => $existing,
            'imported' => 0,
            'preview' => $preview,
        ];
    }

    /**
     * Replace cache for term/year with REG studying students (Faculty Science).
     *
     * @return array{term:int,year:int,reg_total:int,existing:int,imported:int,preview:array<int,array<string,mixed>>}
     */
    public function importFromReg(int $term, int $year): array
    {
        $rows = $this->fetchFromReg($term, $year);
        $mapped = $rows->map(fn ($row) => $this->mapImportRow($row, $term, $year))->values();
        $existing = LateRegStudent::query()
            ->where('TERM', $term)
            ->where('YEAR', $year)
            ->count();

        $imported = 0;
        $now = now();

        DB::connection('discipline')->transaction(function () use ($term, $year, $mapped, $now, &$imported) {
            LateRegStudent::query()
                ->where('TERM', $term)
                ->where('YEAR', $year)
                ->delete();

            foreach ($mapped->chunk(200) as $chunk) {
                $payload = $chunk->map(function (array $row) use ($now) {
                    $row['imported_at'] = $now;

                    return $row;
                })->values()->all();

                DB::connection('discipline')->table('late_reg_student')->insert($payload);
                $imported += count($payload);
            }
        });

        return [
            'term' => $term,
            'year' => $year,
            'reg_total' => $mapped->count(),
            'existing' => $existing,
            'imported' => $imported,
            'preview' => $mapped->take(20)->values()->all(),
        ];
    }

    /**
     * Suggest students by partial or full student code (cache first, then REG).
     *
     * @return list<array<string, mixed>>
     */
    public function searchStudents(string $query, int $limit = 15): array
    {
        $raw = trim($query);
        $needle = str_replace(['-', ' '], '', $raw);
        if ($needle === '' || mb_strlen($needle) < 2) {
            return [];
        }

        $limit = max(1, min(30, $limit));
        /** @var array<string, array<string, mixed>> */
        $byCode = [];

        $remember = function (array $row, bool $prefer = false) use (&$byCode): void {
            $code = (string) ($row['STUDENTCODE'] ?? '');
            $key = str_replace(['-', ' '], '', $code);
            if ($key === '') {
                return;
            }
            if (! isset($byCode[$key]) || $prefer) {
                $byCode[$key] = $row;
            }
        };

        $fromReg = DB::connection('reg')
            ->table('studentmaster as sm')
            ->leftJoin('prefix as p', 'sm.PREFIXID', '=', 'p.PREFIXID')
            ->leftJoin('program as pr', 'sm.PROGRAMID', '=', 'pr.PROGRAMID')
            ->leftJoin('department as d', function ($join) {
                $join->on('sm.FACULTYID', '=', 'd.FACULTYID')
                    ->on('sm.DEPARTMENTID', '=', 'd.DEPARTMENTID');
            })
            ->where('sm.FACULTYID', self::FACULTY_SCIENCE)
            ->where(function ($q) use ($raw, $needle) {
                $q->where('sm.STUDENTCODE', 'like', $raw.'%')
                    ->orWhere('sm.STUDENTCODE', 'like', '%'.$raw.'%')
                    ->orWhereRaw("REPLACE(REPLACE(sm.STUDENTCODE, '-', ''), ' ', '') LIKE ?", [$needle.'%'])
                    ->orWhereRaw("REPLACE(REPLACE(sm.STUDENTCODE, '-', ''), ' ', '') LIKE ?", ['%'.$needle.'%']);
            })
            ->orderByRaw('CASE WHEN sm.STUDENTCODE LIKE ? THEN 0 ELSE 1 END', [$raw.'%'])
            ->orderBy('sm.STUDENTCODE')
            ->limit($limit * 2)
            ->select([
                'sm.STUDENTID',
                'sm.STUDENTCODE',
                'sm.STUDENTNAME',
                'sm.STUDENTSURNAME',
                'p.PREFIXABB',
                'pr.PROGRAMNAME',
                'd.DEPARTMENTNAME',
            ])
            ->get();

        foreach ($fromReg as $row) {
            $remember($this->formatLookupRow($row, 'reg'), true);
        }

        if (count($byCode) < $limit) {
            $cached = LateRegStudent::query()
                ->where(function ($q) use ($raw, $needle) {
                    $q->where('STUDENTCODE', 'like', $raw.'%')
                        ->orWhere('STUDENTCODE', 'like', '%'.$raw.'%')
                        ->orWhereRaw("REPLACE(REPLACE(STUDENTCODE, '-', ''), ' ', '') LIKE ?", [$needle.'%'])
                        ->orWhereRaw("REPLACE(REPLACE(STUDENTCODE, '-', ''), ' ', '') LIKE ?", ['%'.$needle.'%']);
                })
                ->orderByDesc('imported_at')
                ->orderByDesc('YEAR')
                ->orderBy('STUDENTCODE')
                ->limit($limit * 3)
                ->get();

            foreach ($cached as $row) {
                $remember([
                    'source' => 'cache',
                    'STUDENTID' => $row->STUDENTID,
                    'STUDENTCODE' => $row->STUDENTCODE,
                    'PREFIXABB' => $row->PREFIXABB,
                    'STUDENTNAME' => $row->STUDENTNAME,
                    'STUDENTSURNAME' => $row->STUDENTSURNAME,
                    'PROGRAMNAME' => $row->PROGRAMNAME,
                    'DEPARTMENTNAME' => $row->DEPARTMENTNAME,
                    'CITIZENID' => $row->CITIZENID,
                    'full_name' => $row->fullName(),
                    'TERM' => $row->TERM,
                    'YEAR' => $row->YEAR,
                ]);
            }
        }

        return array_values(array_slice($byCode, 0, $limit));
    }

    /**
     * Suggest courses from eoffice.pdcourse by partial/full subject code.
     *
     * @return list<array{code:string,name:string,credit:?string}>
     */
    public function searchCourses(string $query, int $limit = 15): array
    {
        $raw = trim($query);
        if ($raw === '' || mb_strlen($raw) < 2) {
            return [];
        }

        $limit = max(1, min(30, $limit));

        // pdcourse has many duplicate subjcode rows — pick one row per code.
        $rows = DB::connection('eoffice')
            ->table('pdcourse')
            ->select([
                'subjcode',
                DB::raw('MAX(subjname) as subjname'),
                DB::raw('MAX(courseint) as courseint'),
            ])
            ->where(function ($q) use ($raw) {
                $q->where('subjcode', 'like', $raw.'%')
                    ->orWhere('subjcode', 'like', '%'.$raw.'%');
            })
            ->groupBy('subjcode')
            ->orderByRaw('CASE WHEN subjcode LIKE ? THEN 0 ELSE 1 END', [$raw.'%'])
            ->orderBy('subjcode')
            ->limit($limit)
            ->get();

        return $rows->map(fn ($row) => [
            'code' => trim((string) $row->subjcode),
            'name' => trim((string) ($row->subjname ?? '')),
            'credit' => $row->courseint ? trim((string) $row->courseint) : null,
        ])->values()->all();
    }

    /**
     * Suggest departments from eoffice.tbldepartment (names starting with สาขาวิชา).
     *
     * @return list<array{id:int,name:string}>
     */
    public function searchDepartments(string $query, int $limit = 20): array
    {
        $raw = trim($query);
        $limit = max(1, min(50, $limit));

        $rows = DB::connection('eoffice')
            ->table('tbldepartment')
            ->whereRaw(
                "department_name LIKE CONVERT(? USING utf8) COLLATE utf8_general_ci",
                ['สาขาวิชา%']
            )
            ->orderBy('department_name')
            ->limit(100)
            ->get(['department_id', 'department_name']);

        $excludeContains = config('late_exam.departments.exclude_contains', []);
        $rename = config('late_exam.departments.rename', []);
        $extra = config('late_exam.departments.extra', []);

        $departments = [];
        $seenNames = [];

        foreach ($rows as $row) {
            $name = trim((string) $row->department_name);

            foreach ($excludeContains as $needle) {
                if ($needle !== '' && mb_strpos($name, $needle) !== false) {
                    continue 2;
                }
            }

            if (isset($rename[$name])) {
                $name = $rename[$name];
            }

            if ($name === '' || isset($seenNames[$name])) {
                continue;
            }

            $seenNames[$name] = true;
            $departments[] = [
                'id' => (int) $row->department_id,
                'name' => $name,
            ];
        }

        foreach ($extra as $name) {
            $name = trim((string) $name);
            if ($name === '' || isset($seenNames[$name])) {
                continue;
            }

            $seenNames[$name] = true;
            $departments[] = [
                'id' => 0,
                'name' => $name,
            ];
        }

        if ($raw !== '') {
            $needle = mb_strtolower($raw);
            $departments = array_values(array_filter(
                $departments,
                fn (array $department): bool => mb_strpos(mb_strtolower($department['name']), $needle) !== false
            ));
        }

        usort($departments, fn (array $a, array $b): int => strcmp($a['name'], $b['name']));

        return array_slice($departments, 0, $limit);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function lookupStudent(string $codeOrCitizen): ?array
    {
        $raw = trim($codeOrCitizen);
        $needle = str_replace(['-', ' '], '', $raw);
        if ($raw === '') {
            return null;
        }

        $exact = $this->lookupFromReg($needle, $raw);
        if ($exact) {
            return $exact;
        }

        $matches = $this->searchStudents($codeOrCitizen, 5);
        if ($matches === []) {
            return null;
        }

        foreach ($matches as $row) {
            $code = str_replace(['-', ' '], '', (string) $row['STUDENTCODE']);
            if ($code === $needle || (string) $row['STUDENTCODE'] === $raw) {
                return $row;
            }
        }

        return $matches[0];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function recordLate(array $data): FormLate
    {
        $reasonId = (int) ($data['ReasonID'] ?? 0);
        $reason = LateReason::query()->find($reasonId);
        $reasonName = $reason?->ReasonName ?? ($data['REASON_NAME'] ?? null);

        $form = new FormLate;
        $form->fill([
            'ReasonID' => $reasonId ?: null,
            'REASON_NAME' => $reasonName,
            'STUDENTID' => $data['STUDENTID'] ?? 0,
            'STUDENTCODE' => $data['STUDENTCODE'] ?? null,
            'STUDENT_NAME' => $data['STUDENT_NAME'] ?? null,
            'PROGRAM_NAME' => $data['PROGRAM_NAME'] ?? null,
            'DEPARTMENT_NAME' => $data['DEPARTMENT_NAME'] ?? null,
            'CLASSID' => $data['CLASSID'] ?? 0,
            'COURSEID' => $data['COURSEID'] ?? 0,
            'COURSE_CODE' => $data['COURSE_CODE'] ?? null,
            'COURSE_NAME' => $data['COURSE_NAME'] ?? null,
            'ROOMID' => $data['ROOMID'] ?? 0,
            'ROOM_NAME' => $data['ROOM_NAME'] ?? null,
            'SEAT_NO' => filled($data['SEAT_NO'] ?? null) ? trim((string) $data['SEAT_NO']) : null,
            'SEMESTER' => $data['SEMESTER'] ?? null,
            'EXAM_TYPE' => $data['EXAM_TYPE'] ?? null,
            'ACADYEAR' => $data['ACADYEAR'] ?? null,
            'CREATED_BY' => $data['CREATED_BY'] ?? null,
            'DESCI' => $data['DESCI'] ?? null,
            'LATETIME' => $data['LATETIME'] ?? now(),
        ]);
        $form->save();

        return $form->fresh();
    }

    /**
     * Fill denormalized student fields + approximate year/term for legacy formlate rows.
     *
     * @return array{updated_students:int,updated_meta:int}
     */
    public function backfillHistoricalRecords(): array
    {
        $updatedStudents = 0;
        $updatedMeta = 0;

        $reasons = LateReason::query()->pluck('ReasonName', 'ReasonID');

        FormLate::query()
            ->where(function ($q) {
                $q->whereNull('STUDENTCODE')
                    ->orWhere('STUDENTCODE', '')
                    ->orWhereNull('STUDENT_NAME')
                    ->orWhere('STUDENT_NAME', '');
            })
            ->whereNotNull('STUDENTID')
            ->where('STUDENTID', '>', 0)
            ->orderBy('formID')
            ->chunkById(100, function (Collection $rows) use (&$updatedStudents, $reasons) {
                $ids = $rows->pluck('STUDENTID')->unique()->filter()->values()->all();
                if ($ids === []) {
                    return;
                }

                $students = DB::connection('reg')
                    ->table('studentmaster as sm')
                    ->leftJoin('prefix as p', 'sm.PREFIXID', '=', 'p.PREFIXID')
                    ->leftJoin('program as pr', 'sm.PROGRAMID', '=', 'pr.PROGRAMID')
                    ->leftJoin('department as d', function ($join) {
                        $join->on('sm.FACULTYID', '=', 'd.FACULTYID')
                            ->on('sm.DEPARTMENTID', '=', 'd.DEPARTMENTID');
                    })
                    ->whereIn('sm.STUDENTID', $ids)
                    ->select([
                        'sm.STUDENTID',
                        'sm.STUDENTCODE',
                        'sm.STUDENTNAME',
                        'sm.STUDENTSURNAME',
                        'p.PREFIXABB',
                        'pr.PROGRAMNAME',
                        'd.DEPARTMENTNAME',
                    ])
                    ->get()
                    ->keyBy('STUDENTID');

                foreach ($rows as $row) {
                    $sm = $students->get($row->STUDENTID);
                    if (! $sm) {
                        continue;
                    }

                    $prefix = trim((string) ($sm->PREFIXABB ?? ''));
                    $fullName = preg_replace(
                        '/\s+/u',
                        ' ',
                        trim($prefix.$sm->STUDENTNAME.' '.$sm->STUDENTSURNAME)
                    ) ?? '';

                    $payload = [
                        'STUDENTCODE' => (string) $sm->STUDENTCODE,
                        'STUDENT_NAME' => $fullName,
                        'PROGRAM_NAME' => $sm->PROGRAMNAME,
                        'DEPARTMENT_NAME' => $sm->DEPARTMENTNAME,
                    ];

                    if (blank($row->REASON_NAME) && $row->ReasonID) {
                        $payload['REASON_NAME'] = $reasons[$row->ReasonID] ?? null;
                    }

                    $row->fill($payload)->save();
                    $updatedStudents++;
                }
            }, 'formID');

        // Approximate academic year / term from LATETIME when missing (legacy rows).
        $metaRows = FormLate::query()
            ->whereNotNull('LATETIME')
            ->where(function ($q) {
                $q->whereNull('ACADYEAR')->orWhereNull('SEMESTER');
            })
            ->get(['formID', 'LATETIME', 'ACADYEAR', 'SEMESTER', 'REASON_NAME', 'ReasonID']);

        foreach ($metaRows as $row) {
            $payload = [];
            if ($row->ACADYEAR === null && $row->LATETIME) {
                $payload['ACADYEAR'] = (int) $row->LATETIME->format('Y') + 543;
            }
            if ($row->SEMESTER === null && $row->LATETIME) {
                $month = (int) $row->LATETIME->format('n');
                $payload['SEMESTER'] = ($month >= 6 && $month <= 10) ? 1 : 2;
            }
            if (blank($row->REASON_NAME) && $row->ReasonID) {
                $payload['REASON_NAME'] = $reasons[$row->ReasonID] ?? null;
            }
            if ($payload !== []) {
                $row->fill($payload)->save();
                $updatedMeta++;
            }
        }

        return [
            'updated_students' => $updatedStudents,
            'updated_meta' => $updatedMeta,
        ];
    }

    /**
     * @return Collection<int, FormLate>
     */
    public function listPrintable(?int $year, ?int $term, ?string $examType = null): Collection
    {
        $query = FormLate::query()->orderByDesc('LATETIME')->orderByDesc('formID');

        if ($year) {
            $query->where(function ($q) use ($year) {
                $q->where('ACADYEAR', $year)
                    ->orWhere(function ($q2) use ($year) {
                        $q2->whereNull('ACADYEAR')
                            ->whereRaw('(YEAR(LATETIME) + 543) = ?', [$year]);
                    });
            });
        }
        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('SEMESTER', $term)
                    ->orWhere(function ($q2) use ($term) {
                        $q2->whereNull('SEMESTER')
                            ->whereRaw(
                                'CASE WHEN MONTH(LATETIME) BETWEEN 6 AND 10 THEN 1 ELSE 2 END = ?',
                                [$term]
                            );
                    });
            });
        }
        if ($examType) {
            $examType = strtoupper($examType);
            $query->where(function ($q) use ($examType) {
                $q->where('EXAM_TYPE', $examType)
                    ->orWhereNull('EXAM_TYPE')
                    ->orWhere('EXAM_TYPE', '');
            });
        }

        return $query->limit(500)->get();
    }

    public function deleteRecord(int $id): FormLate
    {
        $form = FormLate::query()->find($id);
        abort_if($form === null, 404);

        $form->delete();

        return $form;
    }

    /**
     * @param  array<int, int>  $terms
     * @param  array<int, string>  $examTypes
     * @return array{
     *   total:int,
     *   by_reason:array<int,array{reason_id:int|null,reason_name:string,count:int}>,
     *   by_month:array<int,array{month:string,label:string,count:int}>,
     *   by_department:array<int,array{department:string,count:int}>,
     *   rows:Collection<int, FormLate>
     * }
     */
    public function summary(int $year, array $terms, array $examTypes): array
    {
        $query = FormLate::query()->where(function ($q) use ($year) {
            $q->where('ACADYEAR', $year)
                ->orWhere(function ($q2) use ($year) {
                    $q2->whereNull('ACADYEAR')
                        ->whereRaw('(YEAR(LATETIME) + 543) = ?', [$year]);
                });
        });

        if ($terms !== []) {
            $query->where(function ($q) use ($terms) {
                $q->whereIn('SEMESTER', $terms)
                    ->orWhere(function ($q2) use ($terms) {
                        $q2->whereNull('SEMESTER')
                            ->whereRaw(
                                'CASE WHEN MONTH(LATETIME) BETWEEN 6 AND 10 THEN 1 ELSE 2 END IN ('.implode(',', array_map('intval', $terms)).')'
                            );
                    });
            });
        }
        if ($examTypes !== []) {
            // Legacy rows often lack EXAM_TYPE — include them when filtering by mid/final.
            $query->where(function ($q) use ($examTypes) {
                $q->whereIn('EXAM_TYPE', $examTypes)
                    ->orWhereNull('EXAM_TYPE')
                    ->orWhere('EXAM_TYPE', '');
            });
        }

        $rows = $query->orderByDesc('LATETIME')->orderByDesc('formID')->get();

        $byReason = $rows
            ->groupBy(fn (FormLate $r) => (string) ($r->ReasonID ?? '0'))
            ->map(function (Collection $group, $key) {
                /** @var FormLate $first */
                $first = $group->first();

                return [
                    'reason_id' => $key === '0' ? null : (int) $key,
                    'reason_name' => $first->REASON_NAME
                        ?: (LateReason::query()->find((int) $key)?->ReasonName)
                        ?: 'ไม่ระบุ',
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->all();

        $thaiMonths = [
            1 => 'ม.ค.', 2 => 'ก.พ.', 3 => 'มี.ค.', 4 => 'เม.ย.',
            5 => 'พ.ค.', 6 => 'มิ.ย.', 7 => 'ก.ค.', 8 => 'ส.ค.',
            9 => 'ก.ย.', 10 => 'ต.ค.', 11 => 'พ.ย.', 12 => 'ธ.ค.',
        ];

        $byMonth = $rows
            ->filter(fn (FormLate $r) => $r->LATETIME !== null)
            ->groupBy(fn (FormLate $r) => $r->LATETIME->format('Y-m'))
            ->sortKeys()
            ->map(function (Collection $group, string $ym) use ($thaiMonths) {
                [$y, $m] = array_map('intval', explode('-', $ym));
                $be = $y + 543;

                return [
                    'month' => $ym,
                    'label' => ($thaiMonths[$m] ?? $m).' '.$be,
                    'count' => $group->count(),
                ];
            })
            ->values()
            ->all();

        $byDepartment = $rows
            ->groupBy(fn (FormLate $r) => trim((string) ($r->DEPARTMENT_NAME ?: 'ไม่ระบุ')))
            ->map(fn (Collection $group, string $name) => [
                'department' => $name,
                'count' => $group->count(),
            ])
            ->sortByDesc('count')
            ->values()
            ->all();

        return [
            'total' => $rows->count(),
            'by_reason' => $byReason,
            'by_month' => $byMonth,
            'by_department' => $byDepartment,
            'rows' => $rows,
        ];
    }

    /**
     * Active reasons for the record form (อื่น ๆ is last via sort_order).
     *
     * @return Collection<int, LateReason>
     */
    public function reasons(): Collection
    {
        return LateReason::query()
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('ReasonID')
            ->get();
    }

    public static function dateThai(?\DateTimeInterface $date = null): string
    {
        $date = $date ?? now();
        $months = [
            '', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม',
        ];
        $d = (int) $date->format('j');
        $m = (int) $date->format('n');
        $y = (int) $date->format('Y') + 543;

        return "วันที่  {$d}  เดือน  {$months[$m]}  พ.ศ. {$y}";
    }

    /**
     * @return array<string, mixed>
     */
    private function mapImportRow(object $row, int $term, int $year): array
    {
        return [
            'STUDENTID' => $row->STUDENTID,
            'STUDENTCODE' => (string) $row->STUDENTCODE,
            'PREFIXABB' => $row->PREFIXABB,
            'STUDENTNAME' => $row->STUDENTNAME,
            'STUDENTSURNAME' => $row->STUDENTSURNAME,
            'PROGRAMNAME' => $row->PROGRAMNAME,
            'DEPARTMENTNAME' => $row->DEPARTMENTNAME,
            // vstudentbio has no CITIZENID — keep column null for cache schema compatibility
            'CITIZENID' => null,
            'TERM' => $term,
            'YEAR' => $year,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function lookupFromReg(string $needle, string $raw): ?array
    {
        $row = DB::connection('reg')
            ->table('studentmaster as sm')
            ->leftJoin('prefix as p', 'sm.PREFIXID', '=', 'p.PREFIXID')
            ->leftJoin('program as pr', 'sm.PROGRAMID', '=', 'pr.PROGRAMID')
            ->leftJoin('department as d', function ($join) {
                $join->on('sm.FACULTYID', '=', 'd.FACULTYID')
                    ->on('sm.DEPARTMENTID', '=', 'd.DEPARTMENTID');
            })
            ->where('sm.FACULTYID', self::FACULTY_SCIENCE)
            ->where(function ($q) use ($needle, $raw) {
                $q->where('sm.STUDENTCODE', $raw)
                    ->orWhereRaw("REPLACE(REPLACE(sm.STUDENTCODE, '-', ''), ' ', '') = ?", [$needle]);
            })
            ->select([
                'sm.STUDENTID',
                'sm.STUDENTCODE',
                'sm.STUDENTNAME',
                'sm.STUDENTSURNAME',
                'p.PREFIXABB',
                'pr.PROGRAMNAME',
                'd.DEPARTMENTNAME',
                DB::raw('NULL as CITIZENID'),
            ])
            ->first();

        return $row ? $this->formatLookupRow($row, 'reg') : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatLookupRow(object $row, string $source): array
    {
        $prefix = trim((string) ($row->PREFIXABB ?? ''));
        $first = trim((string) ($row->STUDENTNAME ?? ''));
        $last = trim((string) ($row->STUDENTSURNAME ?? ''));
        $fullName = preg_replace('/\s+/u', ' ', trim($prefix.$first.' '.$last)) ?? '';

        return [
            'source' => $source,
            'STUDENTID' => $row->STUDENTID,
            'STUDENTCODE' => $row->STUDENTCODE,
            'PREFIXABB' => $row->PREFIXABB,
            'STUDENTNAME' => $row->STUDENTNAME,
            'STUDENTSURNAME' => $row->STUDENTSURNAME,
            'PROGRAMNAME' => $row->PROGRAMNAME ?? null,
            'DEPARTMENTNAME' => $row->DEPARTMENTNAME ?? null,
            'CITIZENID' => $row->CITIZENID ?? null,
            'full_name' => $fullName,
            'TERM' => null,
            'YEAR' => null,
        ];
    }
}
