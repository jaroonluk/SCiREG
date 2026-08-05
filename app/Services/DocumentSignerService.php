<?php

namespace App\Services;

use App\Models\DocumentSigner;
use App\Models\ExecutiveUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DocumentSignerService
{
    /**
     * @return Collection<int, object>
     */
    public function selectableExecutives(): Collection
    {
        return DB::connection('eoffice')
            ->table('tbluser_ex as ue')
            ->join('tbluser as u', 'u.username', '=', 'ue.username')
            ->leftJoin('tbltitle as t', 't.title_id', '=', 'u.title')
            ->where(function ($query) {
                $query->whereNull('u.pd_level')
                    ->orWhere('u.pd_level', '')
                    ->orWhereNotIn('u.pd_level', ['4', '5']);
            })
            ->orderBy('ue.position')
            ->orderBy('u.fname')
            ->orderBy('u.lname')
            ->select([
                'ue.id',
                'ue.username',
                'ue.position',
                'u.fname',
                'u.lname',
                'u.title',
                't.title_name',
                't.title_name_s',
            ])
            ->get()
            ->map(function (object $row) {
                $title = trim((string) ($row->title_name ?: $row->title_name_s ?: ''));
                $personName = trim(($row->fname ?? '').' '.($row->lname ?? ''));
                $row->display_name = trim($title.$personName);
                $row->display_name = preg_replace('/\s+/u', ' ', $row->display_name) ?? $row->display_name;
                $row->option_label = trim($row->display_name.' — '.($row->position ?: 'ไม่ระบุตำแหน่ง'));

                return $row;
            });
    }

    /**
     * @return array{acting_for_dean:?object, acting_dean:?object, active_role:string, active:?array{role_key:string,role_label:string,username:?string,full_name:string,position:string,signature_name:string}}
     */
    public function currentSettings(): array
    {
        $this->ensureRows();

        $rows = DocumentSigner::query()->get()->keyBy('role_key');
        $executives = $this->selectableExecutives()->keyBy('username');

        $build = function (?DocumentSigner $row) use ($executives): ?object {
            if (! $row) {
                return null;
            }

            $executive = $row->username ? $executives->get($row->username) : null;

            return (object) [
                'role_key' => $row->role_key,
                'role_label' => $row->role_label,
                'username' => $row->username,
                'is_active' => (bool) $row->is_active,
                'executive' => $executive,
                'full_name' => $executive?->display_name,
                'position' => $executive?->position,
            ];
        };

        $actingForDean = $build($rows->get(DocumentSigner::ROLE_ACTING_FOR_DEAN));
        $actingDean = $build($rows->get(DocumentSigner::ROLE_ACTING_DEAN));

        $activeRole = $rows->firstWhere('is_active', true)?->role_key
            ?? DocumentSigner::ROLE_ACTING_FOR_DEAN;

        return [
            'acting_for_dean' => $actingForDean,
            'acting_dean' => $actingDean,
            'active_role' => $activeRole,
            'active' => $this->activeSigner(),
        ];
    }

    /**
     * @return array{role_key:string,role_label:string,username:?string,full_name:string,position:string,signature_name:string}|null
     */
    public function activeSigner(): ?array
    {
        $this->ensureRows();

        $active = DocumentSigner::query()
            ->where('is_active', true)
            ->first();

        if (! $active || ! $active->username) {
            $active = DocumentSigner::query()
                ->whereNotNull('username')
                ->where('username', '!=', '')
                ->orderByDesc('is_active')
                ->first();
        }

        if (! $active || ! $active->username) {
            return null;
        }

        $executive = $this->selectableExecutives()
            ->firstWhere('username', $active->username);

        if (! $executive) {
            return null;
        }

        return [
            'role_key' => $active->role_key,
            'role_label' => DocumentSigner::roleLabels()[$active->role_key] ?? $active->role_key,
            'username' => $active->username,
            'full_name' => $executive->display_name,
            'position' => (string) ($executive->position ?: ''),
            'signature_name' => '('.$executive->display_name.')',
        ];
    }

    public function save(string $actingForDeanUsername, string $actingDeanUsername, string $activeRole, ?string $updatedBy = null): void
    {
        $this->ensureRows();

        $usernames = $this->selectableExecutives()->pluck('username')->all();

        if ($actingForDeanUsername !== '' && ! in_array($actingForDeanUsername, $usernames, true)) {
            throw new \InvalidArgumentException('ไม่พบผู้บริหารที่เลือกสำหรับปฏิบัติการแทนคณบดี');
        }

        if ($actingDeanUsername !== '' && ! in_array($actingDeanUsername, $usernames, true)) {
            throw new \InvalidArgumentException('ไม่พบผู้บริหารที่เลือกสำหรับรักษาการแทนคณบดี');
        }

        if (! array_key_exists($activeRole, DocumentSigner::roleLabels())) {
            throw new \InvalidArgumentException('บทบาทผู้ลงนามไม่ถูกต้อง');
        }

        DocumentSigner::query()->updateOrCreate(
            ['role_key' => DocumentSigner::ROLE_ACTING_FOR_DEAN],
            [
                'username' => $actingForDeanUsername !== '' ? $actingForDeanUsername : null,
                'is_active' => $activeRole === DocumentSigner::ROLE_ACTING_FOR_DEAN,
                'updated_by' => $updatedBy,
            ]
        );

        DocumentSigner::query()->updateOrCreate(
            ['role_key' => DocumentSigner::ROLE_ACTING_DEAN],
            [
                'username' => $actingDeanUsername !== '' ? $actingDeanUsername : null,
                'is_active' => $activeRole === DocumentSigner::ROLE_ACTING_DEAN,
                'updated_by' => $updatedBy,
            ]
        );
    }

    private function ensureRows(): void
    {
        foreach (array_keys(DocumentSigner::roleLabels()) as $roleKey) {
            DocumentSigner::query()->firstOrCreate(
                ['role_key' => $roleKey],
                [
                    'username' => null,
                    'is_active' => $roleKey === DocumentSigner::ROLE_ACTING_FOR_DEAN,
                ]
            );
        }
    }
}
