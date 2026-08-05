<?php

namespace App\Http\Controllers;

use App\Models\EofficeUser;
use App\Models\Privilege;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserPermissionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $filter = $request->query('filter', 'all');

        $users = EofficeUser::query()
            ->assignableForPermissions()
            ->with(['academicTitle', 'sciregPrivilege'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.$search.'%';
                $query->where(function ($inner) use ($like, $search) {
                    $inner->where('username', 'like', $like)
                        ->orWhere('fname', 'like', $like)
                        ->orWhere('lname', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhereRaw("CONCAT(IFNULL(fname,''), ' ', IFNULL(lname,'')) LIKE ?", [$like]);

                    if (ctype_digit($search)) {
                        $inner->orWhere('username', $search);
                    }
                });
            })
            ->when($filter === 'granted', function ($query) {
                $query->whereHas('sciregPrivilege');
            })
            ->when($filter === 'ungranted', function ($query) {
                $query->whereDoesntHave('sciregPrivilege');
            })
            ->when(in_array($filter, ['service', 'department', 'finance'], true), function ($query) use ($filter) {
                $level = match ($filter) {
                    'service' => Privilege::LEVEL_SERVICE,
                    'department' => Privilege::LEVEL_DEPARTMENT,
                    'finance' => Privilege::LEVEL_FINANCE,
                };
                $query->whereHas('sciregPrivilege', fn ($inner) => $inner->where('level', $level));
            })
            ->orderBy('fname')
            ->orderBy('lname')
            ->paginate(15)
            ->withQueryString();

        $grantedCount = Privilege::forScireg()
            ->whereHas('user', fn ($query) => $query->assignableForPermissions())
            ->count();

        return view('users.permissions', [
            'users' => $users,
            'search' => $search,
            'filter' => $filter,
            'grantedCount' => $grantedCount,
            'roleLabels' => Privilege::levelLabels(),
        ]);
    }

    public function grant(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => [
                'required',
                'string',
                'max:10',
                Rule::exists(EofficeUser::class, 'username')->where(function ($query) {
                    $query->where(function ($inner) {
                        $inner->whereNull('pd_level')
                            ->orWhere('pd_level', '')
                            ->orWhereNotIn('pd_level', [
                                EofficeUser::PD_LEVEL_RETIRED,
                                EofficeUser::PD_LEVEL_RESIGNED,
                            ]);
                    })
                        ->whereRaw('LOWER(username) NOT LIKE ?', ['%test%'])
                        ->whereRaw('LOWER(username) NOT LIKE ?', ['%webmaster%']);
                }),
            ],
            'level' => ['required', 'integer', Rule::in(Privilege::accessLevels())],
        ]);

        Privilege::query()->updateOrCreate(
            [
                'system_id' => Privilege::SYSTEM_SCIREG,
                'username' => $data['username'],
            ],
            [
                'level' => (int) $data['level'],
            ]
        );

        return back()->with(
            'success',
            'บันทึกสิทธิเป็น '.Privilege::levelLabel((int) $data['level']).' เรียบร้อยแล้ว'
        );
    }

    public function revoke(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:10'],
        ]);

        Privilege::forScireg()
            ->where('username', $data['username'])
            ->delete();

        return back()->with('success', 'ถอนสิทธิเข้าใช้งานระบบเรียบร้อยแล้ว');
    }
}
