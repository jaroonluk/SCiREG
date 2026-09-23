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

    private const TITLE_PATTERN = '/^(นางสาว|นาง|นาย|เด็กชาย|เด็กหญิง|mr\.?|mrs\.?|miss|ms\.?)\s*/iu';

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

        foreach ($excelRows as $row) {
            $key = $this->normalizeName($row['excel_name']);
            if ($key === '') {
                $unmatched[] = [
                    ...$row,
                    'reason' => 'ไม่มีชื่อในไฟล์',
                ];

                continue;
            }

            $candidates = $indexed[$key] ?? [];
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

        return (bool) preg_match('/^(นางสาว|นาง|นาย|เด็กชาย|เด็กหญิง)\s*\S+/u', $name);
    }

    private function extractPersonName(string $text): ?string
    {
        if (preg_match('/\(((?:นางสาว|นาง|นาย)\s*[^)]+)\)/u', $text, $m)) {
            return trim(preg_replace('/\s+/u', ' ', $m[1]) ?? $m[1]);
        }

        if (preg_match('/((?:นางสาว|นาง|นาย)\s+\S+(?:\s+\S+){1,3})/u', $text, $m)) {
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
            $key = $this->normalizeName((string) ($student->name ?? ''));
            if ($key === '') {
                continue;
            }

            $index[$key][] = $student;
        }

        return $index;
    }

    public function normalizeName(string $name): string
    {
        $name = trim(preg_replace('/\s+/u', ' ', $name) ?? '');
        $name = preg_replace(self::TITLE_PATTERN, '', $name) ?? $name;
        $name = preg_replace('/\s+/u', '', $name) ?? $name;

        return mb_strtolower($name);
    }
}
