<?php

namespace App\Services;

use App\Models\DocumentSigner;
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
            ->whereNotIn('ue.username', ['114650', '121285'])
            ->whereRaw('LOWER(ue.username) NOT LIKE ?', ['%test%'])
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
     * @return array{username:?string, signing_role:string, active:?array{role_key:string,role_label:string,username:?string,full_name:string,position:string,signature_name:string}}
     */
    public function currentSettings(): array
    {
        $this->ensureRows();

        $active = $this->activeSigner();

        return [
            'username' => $active['username'] ?? null,
            'signing_role' => $active['role_key'] ?? DocumentSigner::ROLE_ACTING_FOR_DEAN,
            'active' => $active,
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
            ->whereNotNull('username')
            ->where('username', '!=', '')
            ->first();

        if (! $active) {
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

    public function save(string $username, string $signingRole, ?string $updatedBy = null): void
    {
        $this->ensureRows();

        $usernames = $this->selectableExecutives()->pluck('username')->all();

        if ($username === '' || ! in_array($username, $usernames, true)) {
            throw new \InvalidArgumentException('กรุณาเลือกผู้บริหารที่ต้องการลงนามเอกสาร');
        }

        if (! array_key_exists($signingRole, DocumentSigner::roleLabels())) {
            throw new \InvalidArgumentException('ประเภทการลงนามไม่ถูกต้อง');
        }

        foreach (array_keys(DocumentSigner::roleLabels()) as $roleKey) {
            $isSelected = $roleKey === $signingRole;

            DocumentSigner::query()->updateOrCreate(
                ['role_key' => $roleKey],
                [
                    'username' => $isSelected ? $username : null,
                    'is_active' => $isSelected,
                    'updated_by' => $updatedBy,
                ]
            );
        }
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
