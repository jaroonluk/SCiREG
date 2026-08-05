@extends('layouts.app')

@section('title', 'กำหนดสิทธิผู้ใช้งานระบบ')

@section('styles')
        .page-head {
            margin-bottom: 1.35rem;
            animation: rise 0.45s ease both;
        }
        .page-head a.back {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none;
            color: var(--ink-muted);
            font-size: 0.92rem;
            margin-bottom: 0.7rem;
        }
        .page-head a.back:hover { color: var(--champaca-deep); }
        .page-head h1 {
            font-family: 'Outfit', 'Sarabun', sans-serif;
            font-size: clamp(1.35rem, 3vw, 1.7rem);
        }

        .toolbar {
            display: grid;
            gap: 0.85rem;
            margin-bottom: 1.1rem;
            animation: rise 0.45s 0.06s ease both;
        }
        .search-form {
            display: flex;
            gap: 0.55rem;
            flex-wrap: wrap;
            position: relative;
        }
        .search-form input[type="search"] {
            flex: 1;
            min-width: 220px;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 999px;
            padding: 0.7rem 1.1rem;
            font: inherit;
            color: var(--ink);
            outline: none;
        }
        .search-form input[type="search"]:focus {
            border-color: var(--champaca);
            box-shadow: 0 0 0 3px rgba(230, 180, 34, 0.18);
        }
        .search-form.is-loading input[type="search"] {
            opacity: 0.72;
        }
        .btn {
            appearance: none;
            border: 1px solid transparent;
            border-radius: 999px;
            padding: 0.7rem 1.15rem;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
        }
        .btn-primary {
            background: linear-gradient(145deg, var(--champaca), var(--champaca-deep));
            color: #fffdf5;
        }
        .btn-primary:hover { filter: brightness(1.03); }
        .btn-ghost {
            background: #fff;
            border-color: var(--line);
            color: var(--ink);
        }
        .btn-ghost:hover { border-color: var(--champaca); }
        .btn-danger {
            background: #fff;
            border-color: rgba(154, 52, 18, 0.25);
            color: #9a3412;
        }
        .btn-danger:hover { background: #fff1e8; }
        .btn-sm {
            padding: 0.4rem 0.85rem;
            font-size: 0.88rem;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.45rem;
        }
        .filters a {
            text-decoration: none;
            padding: 0.4rem 0.9rem;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: rgba(255, 252, 245, 0.8);
            color: var(--ink-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }
        .filters a.active {
            background: rgba(230, 180, 34, 0.2);
            border-color: rgba(201, 146, 26, 0.45);
            color: var(--champaca-deep);
            font-weight: 600;
        }

        .stats {
            color: var(--ink-muted);
            font-size: 0.9rem;
        }
        .stats strong { color: var(--champaca-deep); }

        .alert-success {
            margin-bottom: 1rem;
            padding: 0.85rem 1rem;
            border-radius: 0.85rem;
            background: #eefbf1;
            border: 1px solid rgba(22, 101, 52, 0.18);
            color: #166534;
            font-size: 0.95rem;
            animation: rise 0.35s ease both;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 1.15rem;
            overflow: hidden;
            box-shadow: 0 16px 36px -28px rgba(120, 80, 10, 0.4);
            animation: rise 0.45s 0.1s ease both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.85rem 1rem;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid rgba(201, 146, 26, 0.12);
        }
        th {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ink-muted);
            background: rgba(230, 180, 34, 0.1);
            letter-spacing: 0.01em;
        }
        tr:last-child td { border-bottom: 0; }
        tbody tr:hover td { background: rgba(230, 180, 34, 0.06); }

        .person {
            display: flex;
            flex-direction: column;
            gap: 0.12rem;
        }
        .person-name { font-weight: 600; color: var(--ink); }
        .person-meta { font-size: 0.86rem; color: var(--ink-muted); }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .badge-on {
            background: rgba(22, 101, 52, 0.1);
            color: #166534;
        }
        .badge-off {
            background: rgba(107, 93, 69, 0.1);
            color: var(--ink-muted);
        }

        .empty {
            padding: 2.5rem 1.5rem;
            text-align: center;
            color: var(--ink-muted);
        }

        .pager {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            padding: 0.85rem 1rem;
            border-top: 1px solid rgba(201, 146, 26, 0.12);
            font-size: 0.9rem;
            color: var(--ink-muted);
        }
        .pager .links {
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
        }
        .pager a, .pager span.current {
            text-decoration: none;
            min-width: 2rem;
            height: 2rem;
            display: inline-grid;
            place-items: center;
            border-radius: 0.5rem;
            border: 1px solid var(--line);
            background: #fff;
            color: var(--ink);
            padding: 0 0.45rem;
        }
        .pager span.current {
            background: rgba(230, 180, 34, 0.22);
            border-color: rgba(201, 146, 26, 0.45);
            color: var(--champaca-deep);
            font-weight: 700;
        }
        .pager a:hover { border-color: var(--champaca); }

        @keyframes rise {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 720px) {
            .col-email, .col-id { display: none; }
            th, td { padding: 0.75rem 0.7rem; }
        }
@endsection

@section('content')
    <div class="page-head">
        <a class="back" href="{{ route('home') }}">← กลับเมนูหลัก</a>
        <h1>กำหนดสิทธิผู้ใช้งานระบบ</h1>
    </div>

    @if (session('success'))
        <div class="alert-success" role="status">{{ session('success') }}</div>
    @endif

    <div class="toolbar">
        <form id="permission-search-form" class="search-form" method="GET" action="{{ route('users.permissions') }}">
            <input
                id="permission-search-input"
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="พิมพ์เพื่อค้นหาชื่อ นามสกุล อีเมล หรือรหัสบุคลากร"
                autocomplete="off"
                autofocus
            >
            @if ($filter !== 'all')
                <input type="hidden" name="filter" value="{{ $filter }}">
            @endif
            @if ($search !== '')
                <a class="btn btn-ghost" href="{{ route('users.permissions', array_filter(['filter' => $filter !== 'all' ? $filter : null])) }}">ล้างคำค้น</a>
            @endif
        </form>

        <div class="filters" role="tablist" aria-label="ตัวกรองสิทธิ">
            <a href="{{ route('users.permissions', array_filter(['q' => $search ?: null])) }}" class="{{ $filter === 'all' ? 'active' : '' }}">ทั้งหมด</a>
            <a href="{{ route('users.permissions', array_filter(['q' => $search ?: null, 'filter' => 'granted'])) }}" class="{{ $filter === 'granted' ? 'active' : '' }}">มีสิทธิแล้ว</a>
            <a href="{{ route('users.permissions', array_filter(['q' => $search ?: null, 'filter' => 'ungranted'])) }}" class="{{ $filter === 'ungranted' ? 'active' : '' }}">ยังไม่มีสิทธิ</a>
        </div>

        <div class="stats">
            ผู้มีสิทธิเข้าใช้งานขณะนี้ <strong>{{ number_format($grantedCount) }}</strong> คน
        </div>
    </div>

    <div class="panel">
        @if ($users->isEmpty())
            <div class="empty">ไม่พบบุคลากรตามเงื่อนไขที่ค้นหา</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th class="col-id">รหัส</th>
                        <th>บุคลากร</th>
                        <th class="col-email">อีเมล</th>
                        <th>สถานะสิทธิ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $person)
                        @php $hasAccess = $person->sciregPrivilege !== null; @endphp
                        <tr>
                            <td class="col-id">{{ $person->username }}</td>
                            <td>
                                <div class="person">
                                    <span class="person-name">{{ $person->full_name }}</span>
                                    <span class="person-meta">{{ $person->username }}</span>
                                </div>
                            </td>
                            <td class="col-email">{{ $person->email ?: '—' }}</td>
                            <td>
                                @if ($hasAccess)
                                    <span class="badge badge-on">อนุญาตเข้าใช้งาน</span>
                                @else
                                    <span class="badge badge-off">ยังไม่มีสิทธิ</span>
                                @endif
                            </td>
                            <td>
                                @if ($hasAccess)
                                    <form method="POST" action="{{ route('users.permissions.revoke') }}" onsubmit="return confirm('ถอนสิทธิของ {{ $person->full_name }} หรือไม่?')">
                                        @csrf
                                        <input type="hidden" name="username" value="{{ $person->username }}">
                                        <button type="submit" class="btn btn-danger btn-sm">ถอนสิทธิ</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('users.permissions.grant') }}">
                                        @csrf
                                        <input type="hidden" name="username" value="{{ $person->username }}">
                                        <button type="submit" class="btn btn-primary btn-sm">ให้สิทธิ</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pager">
                <div>
                    แสดง {{ $users->firstItem() }}–{{ $users->lastItem() }}
                    จาก {{ number_format($users->total()) }} รายการ
                </div>
                <div class="links">
                    @if ($users->onFirstPage())
                        <span class="current" style="opacity:.45">‹</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}">‹</a>
                    @endif

                    @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}">›</a>
                    @else
                        <span class="current" style="opacity:.45">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('permission-search-form');
    const input = document.getElementById('permission-search-input');
    if (!form || !input) return;

    const initialValue = input.value;
    let timer = null;
    let lastSubmitted = initialValue;

    const submitSearch = () => {
        const value = input.value.trim();
        if (value === lastSubmitted.trim()) return;
        lastSubmitted = value;
        form.classList.add('is-loading');
        sessionStorage.setItem('permissionsSearchCaret', String(input.selectionStart ?? value.length));
        form.requestSubmit ? form.requestSubmit() : form.submit();
    };

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(submitSearch, 350);
    });

    input.addEventListener('search', () => {
        clearTimeout(timer);
        submitSearch();
    });

    const caret = sessionStorage.getItem('permissionsSearchCaret');
    if (caret !== null) {
        const pos = Math.min(Number(caret), input.value.length);
        input.setSelectionRange(pos, pos);
        sessionStorage.removeItem('permissionsSearchCaret');
    }
})();
</script>
@endpush
