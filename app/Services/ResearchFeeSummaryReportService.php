<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ResearchFeeSummaryReportService
{
    private const EXCLUDED_DEPARTMENT_ID = 7;

    /**
     * @return array{
     *     term:int,
     *     year:int,
     *     depart_id:int,
     *     departments:Collection<int,object>,
     *     sections:list<array{
     *         depart_id:int,
     *         depart_name:string,
     *         rows:list<array{code:string,total:int,paid:int,exempt:int,pending:int,amount:float}>,
     *         row_count:int
     *     }>,
     *     totals:array{total:int,paid:int,exempt:int,pending:int,amount:float}
     * }
     */
    public function build(int $term, int $year, int $departId = 0): array
    {
        $departments = $this->departments($departId);

        $stats = DB::connection('eoffice')
            ->table('fee_research as fr')
            ->where('fr.term', (string) $term)
            ->where('fr.year', (string) $year)
            ->where('fr.depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
            ->when($departId > 0, fn ($query) => $query->where('fr.depart_id', $departId))
            ->selectRaw("fr.depart_id")
            ->selectRaw('LEFT(fr.std_code, 2) as code_prefix')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN fr.status = '3' THEN 1 ELSE 0 END) as paid")
            ->selectRaw("SUM(CASE WHEN fr.status = '2' THEN 1 ELSE 0 END) as exempt")
            ->selectRaw("SUM(CASE WHEN fr.status = '1' THEN 1 ELSE 0 END) as pending")
            ->selectRaw("SUM(CASE WHEN fr.status = '3' THEN fr.amount ELSE 0 END) as amount")
            ->groupBy('fr.depart_id', DB::raw('LEFT(fr.std_code, 2)'))
            ->orderBy('fr.depart_id')
            ->orderBy('code_prefix')
            ->get()
            ->groupBy('depart_id');

        $sections = [];
        $totals = [
            'total' => 0,
            'paid' => 0,
            'exempt' => 0,
            'pending' => 0,
            'amount' => 0.0,
        ];

        foreach ($departments as $department) {
            $departmentStats = $stats->get($department->depart_id, collect());

            if ($departmentStats->isEmpty()) {
                continue;
            }

            $rows = [];

            foreach ($departmentStats as $row) {
                $item = [
                    'code' => (string) $row->code_prefix,
                    'total' => (int) $row->total,
                    'paid' => (int) $row->paid,
                    'exempt' => (int) $row->exempt,
                    'pending' => (int) $row->pending,
                    'amount' => (float) $row->amount,
                ];

                $rows[] = $item;
                $totals['total'] += $item['total'];
                $totals['paid'] += $item['paid'];
                $totals['exempt'] += $item['exempt'];
                $totals['pending'] += $item['pending'];
                $totals['amount'] += $item['amount'];
            }

            $sections[] = [
                'depart_id' => (int) $department->depart_id,
                'depart_name' => (string) $department->depart_name,
                'rows' => $rows,
                'row_count' => count($rows),
            ];
        }

        return [
            'term' => $term,
            'year' => $year,
            'depart_id' => $departId,
            'departments' => $departments,
            'sections' => $sections,
            'totals' => $totals,
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function departments(int $departId = 0): Collection
    {
        return DB::connection('eoffice')
            ->table('depart_fee_research')
            ->where('depart_id', '!=', self::EXCLUDED_DEPARTMENT_ID)
            ->when($departId > 0, fn ($query) => $query->where('depart_id', $departId))
            ->orderBy('depart_id')
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function allDepartments(): Collection
    {
        return $this->departments(0);
    }
}
