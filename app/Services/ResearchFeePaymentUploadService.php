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

    /**
     * Upload Excel to MinIO and return a preview of matched/unmatched rows.
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
        $excelRows = $this->parseExcel($file->getRealPath() ?: $file->getPathname());
        $stored = $this->storeUploadedFile($file, $term, $year);
        $students = $this->studentsForTermYear($term, $year);
        $indexed = $this->indexStudentsByNormalizedName($students);

        $matched = [];
        $unmatched = [];
        $ambiguous = [];
        $usedStdCodes = [];

        foreach ($excelRows as $row) {
            $key = $this->normalizeName($row['excel_name']);
            if ($key === '') {
                $unmatched[] = [
                    ...$row,
                    'reason' => 'ไม่มีชื่อในไฟล์',
                ];

                continue;
            }

            $candidates = $this->findCandidates($row['excel_name'], $students, $indexed);
            $candidates = array_values(array_filter(
                $candidates,
                fn (object $s) => ! isset($usedStdCodes[(string) $s->std_code])
            ));

            if ($candidates === []) {
                $unmatched[] = [
                    ...$row,
                    'reason' => 'ไม่พบชื่อในระบบตามปี/ภาคที่เลือก',
                ];

                continue;
            }

            if (count($candidates) > 1) {
                $ambiguous[] = [
                    ...$row,
                    'reason' => 'พบชื่อซ้ำในระบบมากกว่า 1 รายการ',
                    'candidates' => array_map(fn (object $s) => [
                        'std_code' => $s->std_code,
                        'name' => $s->name,
                        'depart_name' => $s->depart_name,
                    ], $candidates),
                ];

                continue;
            }

            $student = $candidates[0];
            $usedStdCodes[(string) $student->std_code] = true;
            $matched[] = [
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
        }

        return [
            'term' => $term,
            'year' => $year,
            'storage_path' => $stored['path'],
            'storage_disk' => $stored['disk'],
            'original_name' => $file->getClientOriginalName(),
            'matched' => $matched,
            'unmatched' => $unmatched,
            'ambiguous' => $ambiguous,
            'total_rows' => count($excelRows),
        ];
    }

    /**
     * Apply matched payment updates for term/year.
     *
     * @param  list<array{std_code:string,amount:float,slip_no:string}>  $matched
     * @return array{updated:int,skipped:int}
     */
    public function confirm(int $term, int $year, array $matched): array
    {
        $updated = 0;
        $skipped = 0;

        DB::connection('eoffice')->transaction(function () use ($term, $year, $matched, &$updated, &$skipped) {
            foreach ($matched as $row) {
                $stdCode = trim((string) ($row['std_code'] ?? ''));
                if ($stdCode === '') {
                    $skipped++;

                    continue;
                }

                $affected = DB::connection('eoffice')
                    ->table('fee_research')
                    ->where('std_code', $stdCode)
                    ->where('term', (string) $term)
                    ->where('year', (string) $year)
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
     * @return list<array{row:int,excel_name:string,amount:float,slip_no:string,depart_hint:string,description:string}>
     */
    private function parseExcel(string $path): array
    {
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);
        $parsed = [];

        foreach ($rows as $index => $row) {
            $name = trim((string) ($row['H'] ?? ''));
            $amountRaw = $row['G'] ?? null;
            $slip = trim((string) ($row['C'] ?? ''));
            $departHint = trim((string) ($row['F'] ?? ''));
            $description = trim((string) ($row['D'] ?? ''));

            if ($name === '' && ($amountRaw === null || $amountRaw === '') && $slip === '') {
                continue;
            }

            // Skip likely header rows
            if ($this->looksLikeHeader($name, $slip, $amountRaw)) {
                continue;
            }

            if (! $this->looksLikePersonName($name) && $description !== '') {
                $fromDescription = $this->extractPersonName($description);
                if ($fromDescription !== null) {
                    $name = $fromDescription;
                }
            }

            if ($name === '' || ! $this->looksLikePersonName($name)) {
                continue;
            }

            $amount = $this->parseAmount($amountRaw);
            $parsed[] = [
                'row' => (int) $index,
                'excel_name' => preg_replace('/\s+/u', ' ', $name) ?? $name,
                'amount' => $amount,
                'slip_no' => $slip,
                'depart_hint' => $departHint,
                'description' => $description,
            ];
        }

        return $parsed;
    }

    private function looksLikeHeader(string $name, string $slip, mixed $amountRaw): bool
    {
        $haystack = mb_strtolower($name.' '.$slip.' '.(string) $amountRaw);

        return str_contains($haystack, 'ชื่อ')
            || str_contains($haystack, 'ใบเสร็จ')
            || str_contains($haystack, 'amount')
            || str_contains($haystack, 'จำนวนเงิน');
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

        if (preg_match('/((?:นางสาว|น\.?\s*ส\.?|นาย|นาง)\s*\S+(?:\s+\S+){1,3})/u', $text, $m)) {
            return trim(preg_replace('/\s+/u', ' ', $m[1]) ?? $m[1]);
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
     * @return Collection<int, object>
     */
    private function studentsForTermYear(int $term, int $year): Collection
    {
        return DB::connection('eoffice')
            ->table('fee_research as fr')
            ->leftJoin('depart_fee_research as d', 'fr.depart_id', '=', 'd.depart_id')
            ->where('fr.term', (string) $term)
            ->where('fr.year', (string) $year)
            ->where('fr.depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
            ->select([
                'fr.std_code',
                'fr.name',
                'fr.level',
                'fr.couse',
                'fr.depart_id',
                'fr.status',
                'fr.amount',
                'fr.slip_no',
                'd.depart_name',
            ])
            ->get();
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
                $unique[(string) $student->std_code] = $student;
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
                $found[(string) $student->std_code] = $student;
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
                $found[(string) $student->std_code] = $student;

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
                    $found[(string) $student->std_code] = $student;
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
