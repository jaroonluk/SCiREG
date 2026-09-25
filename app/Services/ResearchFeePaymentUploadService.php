<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use RuntimeException;

class ResearchFeePaymentUploadService
{
    private const EXCLUDED_DEPARTMENT_ID = 7;

    /** Titles longest-first so นางสาว / น.ส. win over นาง / น. */
    private const TITLE_PATTERN = '/^(?:นางสาว|น\.?\s*ส\.?|นาย|นาง|เด็กชาย|เด็กหญิง|ด\.?\s*ช\.?|ด\.?\s*ญ\.?|mr\.?|mrs\.?|miss|ms\.?)\s*/iu';

    private const STD_CODE_PATTERN = '/(\d{7,}-\d)/u';

    /**
     * Upload Excel to MinIO and return a preview of matched/unmatched rows.
     *
     * Matching uses student code + term/year + name from the Excel row
     * (H = name/code, G = amount, C = slip, D = term/year description).
     *
     * @return array{
     *   term:int,
     *   year:int,
     *   storage_path:string,
     *   storage_disk:string,
     *   original_name:string,
     *   matched:list<array<string,mixed>>,
     *   unmatched:list<array<string,mixed>>,
     *   ambiguous:list<array<string,mixed>>,
     *   total_rows:int
     * }
     */
    public function preview(UploadedFile $file, int $term, int $year): array
    {
        $excelRows = $this->parseExcel($file->getRealPath() ?: $file->getPathname(), $term, $year);
        $stored = $this->storeUploadedFile($file, $term, $year);

        $pairs = collect($excelRows)
            ->map(fn (array $row) => [(int) $row['term'], (int) $row['year']])
            ->unique(fn (array $pair) => $pair[0].'|'.$pair[1])
            ->values()
            ->all();

        $students = $this->studentsForTermYears($pairs);
        $byCode = $this->indexStudentsByCode($students);

        $fileDupCounts = [];
        foreach ($excelRows as $row) {
            $dupKey = $this->fileDuplicateKey($row);
            $fileDupCounts[$dupKey] = ($fileDupCounts[$dupKey] ?? 0) + 1;
        }

        $matched = [];
        $unmatched = [];
        $ambiguous = [];
        $conflicts = [];
        $unchanged = [];
        $usedKeys = [];

        foreach ($excelRows as $row) {
            if ($row['excel_name'] === '' && $row['excel_std_code'] === '') {
                $unmatched[] = [
                    ...$row,
                    'reason' => 'ไม่มีชื่อหรือรหัสนักศึกษาในไฟล์',
                ];

                continue;
            }

            // Duplicate = same term + year + student code + name within the uploaded file.
            $dupKey = $this->fileDuplicateKey($row);
            if (($fileDupCounts[$dupKey] ?? 0) > 1) {
                $ambiguous[] = [
                    ...$row,
                    'reason' => 'ซ้ำในไฟล์ (ภาค/ปี + รหัสนักศึกษา + ชื่อเดียวกัน)',
                    'duplicate_type' => 'file_identity',
                    'candidates' => [],
                ];

                continue;
            }

            $candidates = $this->findCandidatesForRow($row, $students, $byCode);
            $candidates = array_values(array_filter(
                $candidates,
                function (object $s) use ($usedKeys) {
                    $key = (string) $s->std_code.'|'.$s->term.'|'.$s->year;

                    return ! isset($usedKeys[$key]);
                }
            ));

            if ($candidates === []) {
                $unmatched[] = [
                    ...$row,
                    'reason' => $this->unmatchedReason($row),
                ];

                continue;
            }

            if (count($candidates) > 1) {
                $ambiguous[] = [
                    ...$row,
                    'reason' => 'พบรายการในระบบมากกว่า 1 รายการ',
                    'duplicate_type' => 'system_match',
                    'candidates' => array_map(fn (object $s) => [
                        'std_code' => $s->std_code,
                        'name' => $s->name,
                        'term' => $s->term,
                        'year' => $s->year,
                        'depart_name' => $s->depart_name,
                    ], $candidates),
                ];

                continue;
            }

            $student = $candidates[0];
            $nameMatches = $this->namesCompatible($row['excel_name'], (string) ($student->name ?? ''));
            if ($row['excel_name'] !== '' && ! $nameMatches) {
                $unmatched[] = [
                    ...$row,
                    'std_code' => $student->std_code,
                    'name' => $student->name,
                    'reason' => 'พบรหัสนักศึกษา แต่ชื่อไม่ตรงกับระบบ ('.$student->name.')',
                ];

                continue;
            }

            $usedKeys[(string) $student->std_code.'|'.$student->term.'|'.$student->year] = true;

            $base = [
                ...$row,
                'std_code' => $student->std_code,
                'name' => $student->name,
                'depart_id' => (int) $student->depart_id,
                'depart_name' => $student->depart_name ?: '—',
                'level' => $student->level,
                'couse' => $student->couse,
                'current_status' => (string) $student->status,
                'current_amount' => (float) ($student->amount ?? 0),
                'current_slip_no' => $student->slip_no,
            ];

            // Already paid/updated: compare with incoming Excel values.
            if ($this->isAlreadyUpdated($student)) {
                $differences = $this->paymentDifferences($student, $row);
                if ($differences !== []) {
                    $conflicts[] = [
                        ...$base,
                        'reason' => 'มีการบันทึกชำระเงินแล้ว แต่ข้อมูลไม่ตรงกับไฟล์ที่อัปโหลด',
                        'differences' => $differences,
                    ];

                    continue;
                }

                $unchanged[] = [
                    ...$base,
                    'reason' => 'มีการบันทึกชำระเงินแล้ว และข้อมูลตรงกับไฟล์ (ไม่ต้องอัปเดตซ้ำ)',
                ];

                continue;
            }

            $matched[] = $base;
        }

        $termGroups = $this->buildTermYearGroups($matched, $unmatched, $ambiguous, $conflicts, $unchanged);

        return [
            'term' => $term,
            'year' => $year,
            'storage_path' => $stored['path'],
            'storage_disk' => $stored['disk'],
            'original_name' => $file->getClientOriginalName(),
            'matched' => $matched,
            'unmatched' => $unmatched,
            'ambiguous' => $ambiguous,
            'conflicts' => $conflicts,
            'unchanged' => $unchanged,
            'has_duplicates' => $ambiguous !== [],
            'has_conflicts' => $conflicts !== [],
            'total_rows' => count($excelRows),
            'term_groups' => $termGroups,
            'term_group_count' => count($termGroups),
        ];
    }

    private function isAlreadyUpdated(object $student): bool
    {
        $status = (string) ($student->status ?? '');
        $slip = trim((string) ($student->slip_no ?? ''));

        return $status === '3' || $slip !== '';
    }

    /**
     * @return list<array{field:string,label:string,current:string,incoming:string}>
     */
    private function paymentDifferences(object $student, array $row): array
    {
        $differences = [];

        $currentAmount = round((float) ($student->amount ?? 0), 2);
        $incomingAmount = round((float) ($row['amount'] ?? 0), 2);
        if (abs($currentAmount - $incomingAmount) > 0.009) {
            $differences[] = [
                'field' => 'amount',
                'label' => 'จำนวนเงิน',
                'current' => number_format($currentAmount, 2),
                'incoming' => number_format($incomingAmount, 2),
            ];
        }

        $currentSlip = trim((string) ($student->slip_no ?? ''));
        $incomingSlip = trim((string) ($row['slip_no'] ?? ''));
        if ($currentSlip !== $incomingSlip) {
            $differences[] = [
                'field' => 'slip_no',
                'label' => 'เลขที่ใบเสร็จ',
                'current' => $currentSlip !== '' ? $currentSlip : '—',
                'incoming' => $incomingSlip !== '' ? $incomingSlip : '—',
            ];
        }

        return $differences;
    }

    /**
     * Duplicate identity within one Excel file:
     * same academic term + year + student code + normalized name.
     */
    private function fileDuplicateKey(array $row): string
    {
        $code = $this->normalizeStdCode((string) ($row['excel_std_code'] ?? ''));
        $name = $this->normalizeName((string) ($row['excel_name'] ?? ''));

        return ((int) ($row['term'] ?? 0)).'|'
            .((int) ($row['year'] ?? 0)).'|'
            .$code.'|'
            .$name;
    }

    /**
     * @param  list<array<string,mixed>>  $matched
     * @param  list<array<string,mixed>>  $unmatched
     * @param  list<array<string,mixed>>  $ambiguous
     * @param  list<array<string,mixed>>  $conflicts
     * @param  list<array<string,mixed>>  $unchanged
     * @return list<array<string,mixed>>
     */
    private function buildTermYearGroups(
        array $matched,
        array $unmatched,
        array $ambiguous,
        array $conflicts = [],
        array $unchanged = []
    ): array {
        $buckets = [];

        $push = function (string $bucket, array $row) use (&$buckets): void {
            $term = (int) ($row['term'] ?? 0);
            $year = (int) ($row['year'] ?? 0);
            $key = $term.'|'.$year;
            if (! isset($buckets[$key])) {
                $buckets[$key] = [
                    'term' => $term,
                    'year' => $year,
                    'label' => $this->termYearLabel($term, $year),
                    'matched' => [],
                    'unmatched' => [],
                    'ambiguous' => [],
                    'conflicts' => [],
                    'unchanged' => [],
                ];
            }
            $buckets[$key][$bucket][] = $row;
        };

        foreach ($matched as $row) {
            $push('matched', $row);
        }
        foreach ($unmatched as $row) {
            $push('unmatched', $row);
        }
        foreach ($ambiguous as $row) {
            $push('ambiguous', $row);
        }
        foreach ($conflicts as $row) {
            $push('conflicts', $row);
        }
        foreach ($unchanged as $row) {
            $push('unchanged', $row);
        }

        uasort($buckets, function (array $a, array $b): int {
            return [$a['year'], $a['term']] <=> [$b['year'], $b['term']];
        });

        $groups = [];
        foreach ($buckets as $group) {
            $matchedAmount = array_sum(array_map(
                fn (array $row) => (float) ($row['amount'] ?? 0),
                $group['matched']
            ));

            $groups[] = [
                ...$group,
                'matched_count' => count($group['matched']),
                'unmatched_count' => count($group['unmatched']),
                'ambiguous_count' => count($group['ambiguous']),
                'conflict_count' => count($group['conflicts']),
                'unchanged_count' => count($group['unchanged']),
                'matched_amount' => round($matchedAmount, 2),
                'anchor' => 'term-'.$group['term'].'-'.$group['year'],
            ];
        }

        return $groups;
    }

    /**
     * Apply matched payment updates (each row carries its own term/year).
     *
     * @param  list<array{std_code:string,amount:float,slip_no:string,term?:int,year?:int}>  $matched
     * @return array{updated:int,skipped:int}
     */
    public function confirm(int $term, int $year, array $matched): array
    {
        $updated = 0;
        $skipped = 0;

        DB::connection('eoffice')->transaction(function () use ($matched, $term, $year, &$updated, &$skipped) {
            foreach ($matched as $row) {
                $stdCode = trim((string) ($row['std_code'] ?? ''));
                $rowTerm = (string) ($row['term'] ?? $term);
                $rowYear = (string) ($row['year'] ?? $year);

                if ($stdCode === '' || $rowTerm === '' || $rowYear === '') {
                    $skipped++;

                    continue;
                }

                $affected = DB::connection('eoffice')
                    ->table('fee_research')
                    ->where('std_code', $stdCode)
                    ->where('term', $rowTerm)
                    ->where('year', $rowYear)
                    ->where('depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
                    ->update([
                        'status' => '3',
                        'amount' => (float) ($row['amount'] ?? 0),
                        'slip_no' => trim((string) ($row['slip_no'] ?? '')) ?: null,
                    ]);

                if ($affected > 0) {
                    $updated += $affected;
                } else {
                    $skipped++;
                }
            }
        });

        return [
            'updated' => $updated,
            'skipped' => $skipped,
        ];
    }

    /**
     * @return array{disk:string,path:string}
     */
    private function storeUploadedFile(UploadedFile $file, int $term, int $year): array
    {
        $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) ?: 'payment';
        $ext = strtolower($file->getClientOriginalExtension() ?: 'xlsx');
        $directory = sprintf('research-fee/payments/%d/term%d', $year, $term);
        $filename = sprintf('%s_%s.%s', now()->format('Ymd_His'), $safeName, $ext);
        $objectKey = $directory.'/'.$filename;

        try {
            $path = app(MinioObjectStorage::class)->putFile($objectKey, $file);

            return [
                'disk' => 'minio',
                'path' => $path,
            ];
        } catch (\Throwable $e) {
            report($e);
        }

        $stored = Storage::disk('local')->putFileAs($directory, $file, $filename);
        if (! is_string($stored) || $stored === '') {
            throw new RuntimeException('บันทึกไฟล์อัปโหลดไม่สำเร็จ');
        }

        return [
            'disk' => 'local',
            'path' => $stored,
        ];
    }

    /**
     * @return list<array{
     *   row:int,
     *   excel_name:string,
     *   excel_std_code:string,
     *   amount:float,
     *   slip_no:string,
     *   term:int,
     *   year:int,
     *   depart_hint:string,
     *   description:string,
     *   term_year_label:string
     * }>
     */
    private function parseExcel(string $path, int $fallbackTerm, int $fallbackYear): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        $parsed = [];

        foreach ($rows as $index => $row) {
            $rawName = trim((string) ($row['H'] ?? ''));
            $amountRaw = $row['G'] ?? null;
            $slip = trim((string) ($row['C'] ?? ''));
            $departHint = trim((string) ($row['F'] ?? ''));
            $description = trim((string) ($row['D'] ?? ''));

            if ($rawName === '' && ($amountRaw === null || $amountRaw === '') && $slip === '') {
                continue;
            }

            if ($this->looksLikeHeader($rawName, $slip, $amountRaw)) {
                continue;
            }

            [$name, $stdCode] = $this->extractNameAndStdCode($rawName, $description);

            if ($name === '' || ! $this->looksLikePersonName($name)) {
                continue;
            }

            $termYears = $this->extractTermYears($description);
            if ($termYears === []) {
                $termYears = [['term' => $fallbackTerm, 'year' => $fallbackYear]];
            }

            $amount = $this->parseAmount($amountRaw);
            $perTermAmount = count($termYears) > 1
                ? round($amount / count($termYears), 2)
                : $amount;

            foreach ($termYears as $i => $pair) {
                // Absorb rounding remainder into the last split row.
                $rowAmount = $perTermAmount;
                if (count($termYears) > 1 && $i === count($termYears) - 1) {
                    $rowAmount = round($amount - ($perTermAmount * (count($termYears) - 1)), 2);
                }

                $parsed[] = [
                    'row' => (int) $index,
                    'excel_name' => preg_replace('/\s+/u', ' ', $name) ?? $name,
                    'excel_std_code' => $stdCode,
                    'amount' => $rowAmount,
                    'slip_no' => $slip,
                    'term' => (int) $pair['term'],
                    'year' => (int) $pair['year'],
                    'depart_hint' => $departHint,
                    'description' => $description,
                    'term_year_label' => $this->termYearLabel((int) $pair['term'], (int) $pair['year']),
                ];
            }
        }

        return $parsed;
    }

    /**
     * @return array{0:string,1:string} [name, std_code]
     */
    private function extractNameAndStdCode(string $columnH, string $description): array
    {
        $stdCode = '';
        $name = $columnH;

        if (preg_match(self::STD_CODE_PATTERN, $columnH, $m)) {
            $stdCode = $m[1];
            $name = trim(preg_replace(self::STD_CODE_PATTERN, '', $columnH) ?? $columnH);
        }

        if (! $this->looksLikePersonName($name) && $description !== '') {
            $fromDescription = $this->extractPersonName($description);
            if ($fromDescription !== null) {
                $name = $fromDescription;
            }

            if ($stdCode === '' && preg_match(self::STD_CODE_PATTERN, $description, $m)) {
                $stdCode = $m[1];
            }
        }

        $name = trim(preg_replace('/\s+/u', ' ', $name) ?? $name);

        return [$name, $stdCode];
    }

    /**
     * @return list<array{term:int,year:int}>
     */
    private function extractTermYears(string $text): array
    {
        if ($text === '') {
            return [];
        }

        $found = [];

        // e.g. ภาคเรียนที่ 2/2568 or 1/2569
        if (preg_match_all('/(?:ภาคเรียนที่\s*)?([12])\s*\/\s*(25\d{2})/u', $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $key = $m[1].'|'.$m[2];
                $found[$key] = ['term' => (int) $m[1], 'year' => (int) $m[2]];
            }
        }

        // e.g. ภาคต้น ปี 2568 / ภาคปลาย ปีการศึกษา 2567 / ภาคการศึกษาปลาย ปีการศึกษา 2568
        if (preg_match_all('/ภาค(?:การศึกษา)?\s*(ต้น|ปลาย).*?ปี(?:การศึกษา)?\s*(25\d{2})/u', $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $term = $m[1] === 'ต้น' ? 1 : 2;
                $key = $term.'|'.$m[2];
                $found[$key] = ['term' => $term, 'year' => (int) $m[2]];
            }
        }

        // e.g. ประจำภาคต้น ปีการศึกษา 2568
        if (preg_match_all('/ประจำ\s*ภาค(?:การศึกษา)?\s*(ต้น|ปลาย).*?ปี(?:การศึกษา)?\s*(25\d{2})/u', $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $term = $m[1] === 'ต้น' ? 1 : 2;
                $key = $term.'|'.$m[2];
                $found[$key] = ['term' => $term, 'year' => (int) $m[2]];
            }
        }

        return array_values($found);
    }

    private function termYearLabel(int $term, int $year): string
    {
        return ($term === 1 ? 'ต้น' : 'ปลาย').'/'.$year;
    }

    private function looksLikeHeader(string $name, string $slip, mixed $amountRaw): bool
    {
        $haystack = mb_strtolower($name.' '.$slip.' '.(string) $amountRaw);

        return str_contains($haystack, 'ชื่อ')
            || str_contains($haystack, 'ใบเสร็จ')
            || str_contains($haystack, 'amount')
            || str_contains($haystack, 'จำนวนเงิน')
            || str_contains($haystack, 'ออกใบเสร็จในนาม');
    }

    private function looksLikePersonName(string $name): bool
    {
        $name = trim($name);
        if ($name === '') {
            return false;
        }

        if (preg_match('/(สถาบัน|มหาวิทยาลัย|บริษัท|จำกัด|สสวท|องค์กร|กองทุน)/u', $name)) {
            return false;
        }

        return (bool) preg_match('/^(?:นางสาว|น\.?\s*ส\.?|นาย|นาง|เด็กชาย|เด็กหญิง)\s*\S+/u', $name);
    }

    private function extractPersonName(string $text): ?string
    {
        if (preg_match('/\(((?:นางสาว|น\.?\s*ส\.?|นาย|นาง)\s*[^)]+)\)/u', $text, $m)) {
            return trim(preg_replace('/\s+/u', ' ', $m[1]) ?? $m[1]);
        }

        // Prefer "ราย นาย..." / "พสวท. นาย..." style endings over noisy middle matches.
        if (preg_match('/(?:ราย|พสวท\.?)\s*((?:นางสาว|น\.?\s*ส\.?|นาย|นาง)\s*\S+(?:\s+\S+){0,3})/u', $text, $m)) {
            $name = trim(preg_replace('/\s+/u', ' ', $m[1]) ?? $m[1]);
            $name = preg_replace('/\s+ระดับ.*$/u', '', $name) ?? $name;

            return trim($name);
        }

        if (preg_match('/((?:นางสาว|น\.?\s*ส\.?|นาย|นาง)\s*\S+(?:\s+\S+){1,3})/u', $text, $m)) {
            $name = trim(preg_replace('/\s+/u', ' ', $m[1]) ?? $m[1]);
            $name = preg_replace('/\s+ระดับ.*$/u', '', $name) ?? $name;

            return trim($name);
        }

        return null;
    }

    private function parseAmount(mixed $value): float
    {
        if (is_numeric($value)) {
            return round((float) $value, 2);
        }

        $raw = preg_replace('/[^0-9.\-]/', '', (string) $value) ?? '';

        return $raw === '' ? 0.0 : round((float) $raw, 2);
    }

    /**
     * @param  list<array{0:int,1:int}>  $pairs
     * @return Collection<int, object>
     */
    private function studentsForTermYears(array $pairs): Collection
    {
        if ($pairs === []) {
            return collect();
        }

        $query = DB::connection('eoffice')
            ->table('fee_research as fr')
            ->leftJoin('depart_fee_research as d', 'fr.depart_id', '=', 'd.depart_id')
            ->where('fr.depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
            ->where(function ($q) use ($pairs) {
                foreach ($pairs as [$term, $year]) {
                    $q->orWhere(function ($inner) use ($term, $year) {
                        $inner->where('fr.term', (string) $term)
                            ->where('fr.year', (string) $year);
                    });
                }
            })
            ->select([
                'fr.std_code',
                'fr.name',
                'fr.level',
                'fr.couse',
                'fr.depart_id',
                'fr.status',
                'fr.amount',
                'fr.slip_no',
                'fr.term',
                'fr.year',
                'd.depart_name',
            ]);

        return $query->get();
    }

    /**
     * @param  Collection<int, object>  $students
     * @return array<string, list<object>>
     */
    private function indexStudentsByCode(Collection $students): array
    {
        $index = [];

        foreach ($students as $student) {
            $code = $this->normalizeStdCode((string) ($student->std_code ?? ''));
            if ($code === '') {
                continue;
            }
            $key = $code.'|'.$student->term.'|'.$student->year;
            $index[$key][] = $student;
            $index[$code][] = $student;
        }

        return $index;
    }

    /**
     * @param  Collection<int, object>  $students
     * @param  array<string, list<object>>  $byCode
     * @return list<object>
     */
    private function findCandidatesForRow(array $row, Collection $students, array $byCode): array
    {
        $term = (string) $row['term'];
        $year = (string) $row['year'];
        $stdCode = $this->normalizeStdCode((string) ($row['excel_std_code'] ?? ''));

        if ($stdCode !== '') {
            $exactKey = $stdCode.'|'.$term.'|'.$year;
            if (! empty($byCode[$exactKey])) {
                return $this->uniqueStudents($byCode[$exactKey]);
            }

            // Code found but wrong term/year — still return empty so unmatched reason is clear.
            return [];
        }

        // No student code: match by name within term/year.
        $scoped = $students->filter(
            fn (object $s) => (string) $s->term === $term && (string) $s->year === $year
        );

        return $this->findCandidates($row['excel_name'], $scoped, $this->indexStudentsByNormalizedName($scoped));
    }

    /**
     * @param  list<object>  $students
     * @return list<object>
     */
    private function uniqueStudents(array $students): array
    {
        $unique = [];
        foreach ($students as $student) {
            $key = (string) $student->std_code.'|'.$student->term.'|'.$student->year;
            $unique[$key] = $student;
        }

        return array_values($unique);
    }

    private function unmatchedReason(array $row): string
    {
        if (($row['excel_std_code'] ?? '') !== '') {
            return 'ไม่พบรหัส '.$row['excel_std_code'].' ในระบบสำหรับภาค '.$row['term_year_label'];
        }

        if (($row['excel_name'] ?? '') !== '') {
            return 'ไม่พบชื่อในระบบตามภาค '.$row['term_year_label'];
        }

        return 'ไม่พบในระบบ';
    }

    private function namesCompatible(string $excelName, string $dbName): bool
    {
        if ($excelName === '' || $dbName === '') {
            return true;
        }

        $excelKey = $this->normalizeName($excelName);
        $dbKey = $this->normalizeName($dbName);

        if ($excelKey === '' || $dbKey === '') {
            return true;
        }

        if ($excelKey === $dbKey || str_contains($dbKey, $excelKey) || str_contains($excelKey, $dbKey)) {
            return true;
        }

        $tokens = $this->nameTokens($excelName);
        if (count($tokens) < 2) {
            return false;
        }

        foreach ($tokens as $token) {
            if (! str_contains($dbKey, $token)) {
                return false;
            }
        }

        return true;
    }

    private function normalizeStdCode(string $code): string
    {
        $code = trim($code);
        $code = preg_replace('/\s+/u', '', $code) ?? $code;

        return $code;
    }

    /**
     * @param  Collection<int, object>  $students
     * @return array<string, list<object>>
     */
    private function indexStudentsByNormalizedName(Collection $students): array
    {
        $index = [];

        foreach ($students as $student) {
            foreach ($this->matchKeys((string) ($student->name ?? '')) as $key) {
                $index[$key][] = $student;
            }
        }

        foreach ($index as $key => $list) {
            $unique = [];
            foreach ($list as $student) {
                $unique[(string) $student->std_code.'|'.$student->term.'|'.$student->year] = $student;
            }
            $index[$key] = array_values($unique);
        }

        return $index;
    }

    /**
     * @param  Collection<int, object>  $students
     * @param  array<string, list<object>>  $indexed
     * @return list<object>
     */
    private function findCandidates(string $excelName, Collection $students, array $indexed): array
    {
        $found = [];

        foreach ($this->matchKeys($excelName) as $key) {
            foreach ($indexed[$key] ?? [] as $student) {
                $found[(string) $student->std_code.'|'.$student->term.'|'.$student->year] = $student;
            }
        }

        if ($found !== []) {
            return array_values($found);
        }

        $excelKey = $this->normalizeName($excelName);
        $tokens = $this->nameTokens($excelName);
        if ($excelKey === '' && $tokens === []) {
            return [];
        }

        foreach ($students as $student) {
            $dbKey = $this->normalizeName((string) ($student->name ?? ''));
            if ($dbKey === '') {
                continue;
            }

            if ($excelKey !== '' && ($excelKey === $dbKey || str_contains($dbKey, $excelKey) || str_contains($excelKey, $dbKey))) {
                $found[(string) $student->std_code.'|'.$student->term.'|'.$student->year] = $student;

                continue;
            }

            if (count($tokens) >= 2) {
                $allPresent = true;
                foreach ($tokens as $token) {
                    if (! str_contains($dbKey, $token)) {
                        $allPresent = false;
                        break;
                    }
                }
                if ($allPresent) {
                    $found[(string) $student->std_code.'|'.$student->term.'|'.$student->year] = $student;
                }
            }
        }

        return array_values($found);
    }

    /**
     * @return list<string>
     */
    private function matchKeys(string $name): array
    {
        $keys = [];
        $normalized = $this->normalizeName($name);
        if ($normalized !== '') {
            $keys[] = $normalized;
        }

        $tokens = $this->nameTokens($name);
        if (count($tokens) >= 2) {
            $keys[] = implode('', $tokens);
            $keys[] = $tokens[0].'|'.$tokens[count($tokens) - 1];
        }

        return array_values(array_unique($keys));
    }

    /**
     * @return list<string>
     */
    private function nameTokens(string $name): array
    {
        $name = $this->cleanName($name);
        $name = preg_replace(self::TITLE_PATTERN, '', $name) ?? $name;
        $parts = preg_split('/\s+/u', trim($name)) ?: [];

        $tokens = [];
        foreach ($parts as $part) {
            $token = $this->normalizeName($part);
            if ($token !== '' && mb_strlen($token) >= 2) {
                $tokens[] = $token;
            }
        }

        return array_values(array_unique($tokens));
    }

    public function normalizeName(string $name): string
    {
        $name = $this->cleanName($name);
        // Strip titles repeatedly (e.g. "น.ส. นางสาว...")
        for ($i = 0; $i < 3; $i++) {
            $next = preg_replace(self::TITLE_PATTERN, '', $name) ?? $name;
            if ($next === $name) {
                break;
            }
            $name = trim($next);
        }

        // Keep Thai combining marks (\p{M}) so สระ/วรรณยุกต์ are not stripped.
        $name = preg_replace('/[^\p{L}\p{N}\p{M}]+/u', '', $name) ?? $name;

        return mb_strtolower($name);
    }

    private function cleanName(string $name): string
    {
        $name = str_replace("\u{00A0}", ' ', $name);
        $name = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $name) ?? $name;
        $name = trim(preg_replace('/\s+/u', ' ', $name) ?? '');

        return $name;
    }
}
