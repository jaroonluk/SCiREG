<?php

namespace App\Http\Controllers;

use App\Models\AuditLogScireg;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'module' => ['nullable', 'string', 'max:50'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $query = AuditLogScireg::query()->orderByDesc('id');

        if (! empty($data['q'])) {
            $q = trim($data['q']);
            $query->where(function ($inner) use ($q) {
                $inner->where('username', 'like', '%'.$q.'%')
                    ->orWhere('user_name', 'like', '%'.$q.'%')
                    ->orWhere('action', 'like', '%'.$q.'%')
                    ->orWhere('description', 'like', '%'.$q.'%')
                    ->orWhere('route_name', 'like', '%'.$q.'%');
            });
        }

        if (! empty($data['module'])) {
            $query->where('module', $data['module']);
        }

        if (! empty($data['date_from'])) {
            $query->whereDate('created_at', '>=', $data['date_from']);
        }

        if (! empty($data['date_to'])) {
            $query->whereDate('created_at', '<=', $data['date_to']);
        }

        $logs = $query->paginate(30)->withQueryString();

        $stats = [
            'today' => AuditLogScireg::query()->whereDate('created_at', today())->count(),
            'total' => AuditLogScireg::query()->count(),
            'users' => (int) AuditLogScireg::query()
                ->whereNotNull('username')
                ->where('username', '!=', '')
                ->selectRaw('COUNT(DISTINCT username) as aggregate')
                ->value('aggregate'),
        ];

        return view('audit-logs.index', [
            'logs' => $logs,
            'modules' => AuditLogScireg::moduleLabels(),
            'filters' => [
                'q' => $data['q'] ?? '',
                'module' => $data['module'] ?? '',
                'date_from' => $data['date_from'] ?? '',
                'date_to' => $data['date_to'] ?? '',
            ],
            'stats' => $stats,
        ]);
    }
}
