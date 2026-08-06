@extends('layouts.app')

@section('title', 'กำหนดสิทธิผู้ใช้งานระบบ')

@section('styles')
    main.app-main { max-width: min(100%, 1200px); width: 100%; }

    @keyframes rise {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes softPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(230,180,34,.28); }
        50% { box-shadow: 0 0 0 8px rgba(230,180,34,0); }
    }

    .page-head {
        margin-bottom: 1.35rem;
        animation: rise .45s ease both;
        display: grid; gap: .45rem;
    }
    .back {
        display: inline-flex; align-items: center; gap: .35rem;
        color: var(--ink-muted); text-decoration: none; font-size: .92rem; width: fit-content;
    }
    .back:hover { color: var(--champaca-deep); }
    .back svg { width: 1rem; height: 1rem; }
    .title-row { display: flex; align-items: center; gap: .85rem; flex-wrap: wrap; }
    .title-icon {
        width: 3rem; height: 3rem; border-radius: 1rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: linear-gradient(145deg, #f3d06a, var(--champaca-deep));
        color: #fffdf5;
        box-shadow: 0 12px 24px -14px rgba(150,100,10,.55);
        animation: softPulse 2.8s ease-in-out infinite;
    }
    .title-icon svg { width: 1.45rem; height: 1.45rem; }
    .page-head h1 {
        font: 700 clamp(1.4rem,3vw,1.85rem) 'Outfit','Sarabun',sans-serif;
        line-height: 1.2;
    }
    .page-head p { color: var(--ink-muted); line-height: 1.5; max-width: 42rem; }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: .85rem;
        margin-bottom: 1rem;
        animation: rise .5s .04s ease both;
    }
    .stat-card {
        position: relative;
        background: linear-gradient(160deg, #fffef8, #fff6d8);
        border: 1px solid rgba(201,146,26,.28);
        border-radius: 1.1rem;
        padding: 1rem 1.05rem;
        display: flex; align-items: center; gap: .8rem;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform .15s ease, border-color .15s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        border-color: rgba(201,146,26,.5);
    }
    .stat-card.is-active {
        border-color: rgba(201,146,26,.55);
        box-shadow: 0 10px 24px -18px rgba(150,100,10,.55);
        background: linear-gradient(160deg, #fff9e6, #ffe9a8);
    }
    .stat-card::after {
        content: '';
        position: absolute; right: -12px; top: -18px;
        width: 70px; height: 70px; border-radius: 50%;
        background: rgba(230,180,34,.12);
    }
    .stat-icon {
        width: 2.55rem; height: 2.55rem; border-radius: .85rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: rgba(230,180,34,.18);
        color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
        z-index: 1;
    }
    .stat-icon.tone-service { background: rgba(201,146,26,.18); color: #8a6510; }
    .stat-icon.tone-dept { background: rgba(37,99,235,.12); color: #1d4ed8; border-color: rgba(37,99,235,.22); }
    .stat-icon.tone-finance { background: rgba(15,118,110,.12); color: #0f766e; border-color: rgba(15,118,110,.22); }
    .stat-icon svg { width: 1.15rem; height: 1.15rem; }
    .stat-card .label {
        font-size: .8rem; color: var(--ink-muted); font-weight: 600;
        display: block;
    }
    .stat-card .value {
        font: 700 1.3rem 'Outfit','Sarabun',sans-serif;
        color: var(--champaca-deep);
        line-height: 1.15;
    }

    .panel {
        position: relative;
        background: linear-gradient(180deg, rgba(255,253,247,.98), rgba(255,250,236,.94));
        border: 1px solid var(--line);
        border-radius: 1.2rem;
        box-shadow: 0 18px 40px -30px rgba(120,80,10,.5);
        padding: 1.25rem 1.3rem 1.35rem;
        margin-bottom: 1rem;
        overflow: hidden;
        animation: rise .5s .08s ease both;
    }
    .panel::before {
        content: '';
        position: absolute; inset: 0 auto 0 0; width: 4px;
        background: linear-gradient(180deg, var(--champaca), var(--champaca-deep));
        border-radius: 1.2rem 0 0 1.2rem;
    }
    .panel-head {
        display: flex; align-items: center; gap: .7rem;
        margin-bottom: 1rem;
    }
    .step-badge {
        width: 2.15rem; height: 2.15rem; border-radius: .7rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: rgba(230,180,34,.16);
        color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
    }
    .step-badge svg { width: 1.1rem; height: 1.1rem; }
    .panel-head h2 {
        font: 700 1.05rem 'Outfit','Sarabun',sans-serif;
    }
    .panel-head .sub {
        display: block; margin-top: .12rem;
        font-size: .82rem; font-weight: 500; color: var(--ink-muted);
    }

    .alert-success {
        margin-bottom: 1rem;
        padding: .85rem 1rem;
        border-radius: .9rem;
        background: #eefbf1;
        border: 1px solid rgba(22,101,52,.18);
        color: #166534;
        font-size: .95rem;
        display: flex; align-items: flex-start; gap: .55rem;
        animation: rise .35s ease both;
    }
    .alert-success svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .1rem; }

    .search-form {
        display: flex;
        gap: .55rem;
        flex-wrap: wrap;
        margin-bottom: .9rem;
    }
    .search-wrap {
        flex: 1;
        min-width: 220px;
        position: relative;
    }
    .search-wrap svg {
        position: absolute; left: .95rem; top: 50%; transform: translateY(-50%);
        width: 1.05rem; height: 1.05rem; color: var(--champaca-deep); pointer-events: none;
    }
    .search-form input[type="search"] {
        width: 100%;
        border: 1px solid rgba(201,146,26,.28);
        background: #fffef9;
        border-radius: .85rem;
        padding: .72rem 1rem .72rem 2.55rem;
        font: inherit;
        color: var(--ink);
        outline: none;
    }
    .search-form input[type="search"]:focus {
        border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.18);
    }
    .search-form.is-loading input[type="search"] { opacity: .72; }

    .btn {
        appearance: none;
        border: 1px solid transparent;
        border-radius: .85rem;
        padding: .7rem 1.1rem;
        font: inherit;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .35rem;
        white-space: nowrap;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn-primary {
        background: linear-gradient(145deg, #f0c94a, var(--champaca-deep));
        color: #fffdf5;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.65);
    }
    .btn-primary:hover { filter: brightness(1.03); }
    .btn-ghost {
        background: #fffef9;
        border-color: rgba(201,146,26,.28);
        color: var(--ink);
    }
    .btn-ghost:hover { border-color: var(--champaca); color: var(--champaca-deep); }
    .btn-danger {
        background: #fff;
        border-color: rgba(154,52,18,.25);
        color: #9a3412;
    }
    .btn-danger:hover { background: #fff1e8; }
    .btn-sm {
        padding: .42rem .85rem;
        font-size: .86rem;
        border-radius: .7rem;
    }

    .filters {
        display: flex;
        flex-wrap: wrap;
        gap: .45rem;
        margin-bottom: .15rem;
    }
    .filters a {
        text-decoration: none;
        padding: .42rem .9rem;
        border-radius: 999px;
        border: 1px solid rgba(201,146,26,.22);
        background: #fffef9;
        color: var(--ink-muted);
        font-size: .88rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        transition: background .15s ease, border-color .15s ease, color .15s ease;
    }
    .filters a svg { width: .95rem; height: .95rem; }
    .filters a:hover {
        border-color: rgba(201,146,26,.45);
        color: var(--champaca-deep);
    }
    .filters a.active {
        background: rgba(230,180,34,.2);
        border-color: rgba(201,146,26,.5);
        color: var(--champaca-deep);
    }

    .role-help {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .75rem;
        margin-top: 1rem;
    }
    .role-card {
        border: 1px solid rgba(201,146,26,.22);
        background: #fffef9;
        border-radius: 1rem;
        padding: .9rem 1rem;
        display: grid;
        gap: .45rem;
    }
    .role-card-head {
        display: flex; align-items: center; gap: .55rem;
    }
    .role-card-icon {
        width: 2rem; height: 2rem; border-radius: .65rem;
        display: grid; place-items: center; flex-shrink: 0;
        border: 1px solid transparent;
    }
    .role-card-icon svg { width: 1rem; height: 1rem; }
    .role-card-icon.service {
        background: rgba(201,146,26,.16); color: #8a6510; border-color: rgba(201,146,26,.28);
    }
    .role-card-icon.department {
        background: rgba(37,99,235,.12); color: #1d4ed8; border-color: rgba(37,99,235,.22);
    }
    .role-card-icon.finance {
        background: rgba(15,118,110,.12); color: #0f766e; border-color: rgba(15,118,110,.22);
    }
    .role-card strong {
        font-size: .92rem;
        font-family: 'Outfit','Sarabun',sans-serif;
    }
    .role-card p {
        margin: 0;
        font-size: .84rem;
        color: var(--ink-muted);
        line-height: 1.45;
    }

    .table-panel { padding: 0; }
    .table-panel .panel-head {
        padding: 1.15rem 1.3rem 0;
        margin-bottom: .85rem;
    }
    .table-wrap { overflow-x: auto; }
    table.perm-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 780px;
    }
    table.perm-table th {
        text-align: left;
        font-size: .78rem;
        font-weight: 700;
        color: var(--champaca-deep);
        letter-spacing: .02em;
        padding: .7rem 1.1rem;
        border-bottom: 2px solid rgba(201,146,26,.28);
        background: rgba(230,180,34,.08);
        white-space: nowrap;
    }
    table.perm-table td {
        padding: .9rem 1.1rem;
        border-bottom: 1px solid rgba(201,146,26,.14);
        vertical-align: middle;
        font-size: .92rem;
    }
    table.perm-table tbody tr { transition: background .15s ease; }
    table.perm-table tbody tr:hover { background: rgba(230,180,34,.07); }
    table.perm-table tbody tr:last-child td { border-bottom: 0; }

    .person {
        display: flex; align-items: center; gap: .7rem;
    }
    .avatar {
        width: 2.35rem; height: 2.35rem; border-radius: .75rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: linear-gradient(145deg, #f3d06a, var(--champaca-deep));
        color: #fffdf5;
        font: 700 .85rem 'Outfit','Sarabun',sans-serif;
        box-shadow: 0 8px 16px -12px rgba(150,100,10,.55);
    }
    .person-name { font-weight: 700; color: var(--ink); display: block; }
    .person-meta { font-size: .8rem; color: var(--ink-muted); }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .32rem .7rem;
        border-radius: 999px;
        font-size: .8rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .badge svg { width: .85rem; height: .85rem; }
    .badge-on { background: rgba(22,101,52,.1); color: #166534; }
    .badge-off { background: rgba(107,93,69,.1); color: var(--ink-muted); }
    .badge-service { background: rgba(201,146,26,.16); color: #8a6510; }
    .badge-department { background: rgba(37,99,235,.12); color: #1d4ed8; }
    .badge-finance { background: rgba(15,118,110,.12); color: #0f766e; }

    .role-form {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        align-items: center;
    }
    .role-form select {
        border: 1px solid rgba(201,146,26,.28);
        background: #fffef9;
        border-radius: .7rem;
        padding: .42rem .7rem;
        font: inherit;
        font-size: .86rem;
        color: var(--ink);
        max-width: 13rem;
    }
    .role-form select:focus {
        outline: none;
        border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.15);
    }

    .empty {
        padding: 2.8rem 1.5rem;
        text-align: center;
        color: var(--ink-muted);
    }
    .empty svg {
        width: 2.8rem; height: 2.8rem; margin: 0 auto .7rem;
        color: var(--champaca);
        display: block;
    }

    .pager {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
        padding: .9rem 1.15rem 1.15rem;
        border-top: 1px solid rgba(201,146,26,.14);
        font-size: .88rem;
        color: var(--ink-muted);
    }
    .pager .links { display: flex; gap: .35rem; flex-wrap: wrap; }
    .pager a, .pager span.page {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2rem; height: 2rem; padding: 0 .55rem;
        border-radius: .55rem; text-decoration: none;
        border: 1px solid rgba(201,146,26,.25);
        background: #fffef9; color: var(--ink);
        font-weight: 600; font-size: .84rem;
    }
    .pager a:hover { border-color: var(--champaca); color: var(--champaca-deep); }
    .pager span.page.current {
        background: linear-gradient(145deg, #f0c94a, var(--champaca-deep));
        border-color: transparent; color: #fffdf5;
    }
    .pager span.page.disabled { opacity: .4; }

    @media (max-width: 960px) {
        .stat-grid { grid-template-columns: 1fr 1fr; }
        .role-help { grid-template-columns: 1fr; }
    }
    @media (max-width: 720px) {
        .col-email { display: none; }
        .stat-grid { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
    @php
        $qParams = fn (?string $f = null) => array_filter([
            'q' => $search !== '' ? $search : null,
            'filter' => ($f !== null && $f !== 'all') ? $f : null,
        ]);
        $badgeIcons = [
            1 => '<path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/>',
            2 => '<path d="M3 20V9l9-5 9 5v11"/><path d="M9 20v-7h6v7"/>',
            3 => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 6V4h8v2"/>',
        ];
    @endphp

    <div class="page-head">
        <a class="back" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 18l-6-6 6-6"/></svg>
            กลับหน้าหลัก
        </a>
        <div class="title-row">
            <div class="title-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/>
                    <path d="M9.2 12.1l1.8 1.8 3.8-3.8"/>
                </svg>
            </div>
            <div>
                <h1>กำหนดสิทธิผู้ใช้งานระบบ</h1>
                <p>มอบสิทธิเข้าใช้ SCiREG ตามบทบาทงานบริการ สาขาวิชา และการเงิน พร้อมตรวจสอบผู้มีสิทธิได้อย่างรวดเร็ว</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="stat-grid">
        <a class="stat-card {{ $filter === 'granted' ? 'is-active' : '' }}" href="{{ route('users.permissions', $qParams('granted')) }}">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="9" cy="8" r="3"/>
                    <path d="M3.5 19c.7-3 2.8-4.8 5.5-4.8S14 16 14.7 19"/>
                    <circle cx="17" cy="9" r="2.2"/>
                    <path d="M15.2 19c.4-1.8 1.6-3 3.3-3"/>
                </svg>
            </div>
            <div>
                <span class="label">มีสิทธิทั้งหมด</span>
                <span class="value">{{ number_format($grantedCount) }}</span>
            </div>
        </a>
        <a class="stat-card {{ $filter === 'service' ? 'is-active' : '' }}" href="{{ route('users.permissions', $qParams('service')) }}">
            <div class="stat-icon tone-service" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/>
                    <path d="M9.2 12.1l1.8 1.8 3.8-3.8"/>
                </svg>
            </div>
            <div>
                <span class="label">งานบริการ</span>
                <span class="value">{{ number_format($levelCounts[1] ?? 0) }}</span>
            </div>
        </a>
        <a class="stat-card {{ $filter === 'department' ? 'is-active' : '' }}" href="{{ route('users.permissions', $qParams('department')) }}">
            <div class="stat-icon tone-dept" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M3 20V9l9-5 9 5v11"/><path d="M9 20v-7h6v7"/>
                </svg>
            </div>
            <div>
                <span class="label">สาขาวิชา</span>
                <span class="value">{{ number_format($levelCounts[2] ?? 0) }}</span>
            </div>
        </a>
        <a class="stat-card {{ $filter === 'finance' ? 'is-active' : '' }}" href="{{ route('users.permissions', $qParams('finance')) }}">
            <div class="stat-icon tone-finance" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 6V4h8v2"/>
                </svg>
            </div>
            <div>
                <span class="label">การเงิน</span>
                <span class="value">{{ number_format($levelCounts[3] ?? 0) }}</span>
            </div>
        </a>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/>
                </svg>
            </div>
            <div>
                <h2>ค้นหาและกรองสิทธิ</h2>
                <span class="sub">ค้นหาชื่อ อีเมล หรือรหัสบุคลากร แล้วเลือกดูตามระดับสิทธิ</span>
            </div>
        </div>

        <form id="permission-search-form" class="search-form" method="GET" action="{{ route('users.permissions') }}">
            <div class="search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/>
                </svg>
                <input
                    id="permission-search-input"
                    type="search"
                    name="q"
                    value="{{ $search }}"
                    placeholder="พิมพ์เพื่อค้นหาชื่อ นามสกุล อีเมล หรือรหัสบุคลากร"
                    autocomplete="off"
                    autofocus
                >
            </div>
            @if ($filter !== 'all')
                <input type="hidden" name="filter" value="{{ $filter }}">
            @endif
            @if ($search !== '')
                <a class="btn btn-ghost" href="{{ route('users.permissions', $qParams($filter)) }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 6l12 12M18 6L6 18"/></svg>
                    ล้างคำค้น
                </a>
            @endif
        </form>

        <div class="filters" role="tablist" aria-label="ตัวกรองสิทธิ">
            <a href="{{ route('users.permissions', $qParams('all')) }}" class="{{ $filter === 'all' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                ทั้งหมด
            </a>
            <a href="{{ route('users.permissions', $qParams('granted')) }}" class="{{ $filter === 'granted' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/></svg>
                มีสิทธิแล้ว
            </a>
            <a href="{{ route('users.permissions', $qParams('service')) }}" class="{{ $filter === 'service' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                งานบริการ
            </a>
            <a href="{{ route('users.permissions', $qParams('department')) }}" class="{{ $filter === 'department' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 20V9l9-5 9 5v11"/><path d="M9 20v-7h6v7"/></svg>
                สาขาวิชา
            </a>
            <a href="{{ route('users.permissions', $qParams('finance')) }}" class="{{ $filter === 'finance' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 6V4h8v2"/></svg>
                การเงิน
            </a>
            <a href="{{ route('users.permissions', $qParams('ungranted')) }}" class="{{ $filter === 'ungranted' ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M8 12h8"/></svg>
                ยังไม่มีสิทธิ
            </a>
        </div>

        <div class="role-help">
            <div class="role-card">
                <div class="role-card-head">
                    <div class="role-card-icon service" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/>
                            <path d="M9.2 12.1l1.8 1.8 3.8-3.8"/>
                        </svg>
                    </div>
                    <strong>เจ้าหน้าที่งานบริการ</strong>
                </div>
                <p>ใช้ได้ทุกเมนูในระบบ รวมนำเข้าข้อมูล บันทึก และกำหนดสิทธิ</p>
            </div>
            <div class="role-card">
                <div class="role-card-head">
                    <div class="role-card-icon department" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M3 20V9l9-5 9 5v11"/><path d="M9 20v-7h6v7"/>
                        </svg>
                    </div>
                    <strong>เจ้าหน้าที่สาขาวิชา</strong>
                </div>
                <p>ดูรายงานค่าธรรมเนียมวิจัยเฉพาะสาขาที่สังกัด</p>
            </div>
            <div class="role-card">
                <div class="role-card-head">
                    <div class="role-card-icon finance" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 6V4h8v2"/>
                        </svg>
                    </div>
                    <strong>เจ้าหน้าที่การเงิน</strong>
                </div>
                <p>จัดการข้อมูลชำระเงินค่าธรรมเนียมวิจัยเท่านั้น</p>
            </div>
        </div>
    </div>

    <div class="panel table-panel" style="animation-delay:.12s">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="9" cy="8" r="3"/>
                    <path d="M3.5 19c.7-3 2.8-4.8 5.5-4.8S14 16 14.7 19"/>
                    <path d="M16 8h5M18.5 5.5v5"/>
                </svg>
            </div>
            <div>
                <h2>รายชื่อบุคลากร</h2>
                <span class="sub">เลือกสิทธิแล้วกดบันทึก หรือถอนสิทธิเมื่อไม่ต้องการให้เข้าใช้งาน</span>
            </div>
        </div>

        @if ($users->isEmpty())
            <div class="empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/>
                </svg>
                <p>ไม่พบบุคลากรตามเงื่อนไขที่ค้นหา</p>
            </div>
        @else
            <div class="table-wrap">
                <table class="perm-table">
                    <thead>
                        <tr>
                            <th>บุคลากร</th>
                            <th class="col-email">อีเมล</th>
                            <th>ระดับสิทธิ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $person)
                            @php
                                $privilege = $person->sciregPrivilege;
                                $hasAccess = $privilege !== null;
                                $level = $privilege?->level;
                                $badgeClass = match ($level) {
                                    1 => 'badge-service',
                                    2 => 'badge-department',
                                    3 => 'badge-finance',
                                    default => 'badge-off',
                                };
                                $initial = mb_substr(trim((string) $person->fname), 0, 1) ?: '?';
                            @endphp
                            <tr>
                                <td>
                                    <div class="person">
                                        <div class="avatar" aria-hidden="true">{{ $initial }}</div>
                                        <div>
                                            <span class="person-name">{{ $person->full_name }}</span>
                                            <span class="person-meta">รหัส {{ $person->username }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-email">{{ $person->email ?: '—' }}</td>
                                <td>
                                    @if ($hasAccess)
                                        <span class="badge {{ $badgeClass }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                {!! $badgeIcons[$level] ?? '' !!}
                                            </svg>
                                            {{ $roleLabels[$level] ?? 'มีสิทธิ' }}
                                        </span>
                                    @else
                                        <span class="badge badge-off">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <circle cx="12" cy="12" r="9"/><path d="M8 12h8"/>
                                            </svg>
                                            ยังไม่มีสิทธิ
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="role-form">
                                        <form method="POST" action="{{ route('users.permissions.grant') }}" class="role-form">
                                            @csrf
                                            <input type="hidden" name="username" value="{{ $person->username }}">
                                            <select name="level" aria-label="เลือกระดับสิทธิของ {{ $person->full_name }}">
                                                @foreach ($roleLabels as $value => $label)
                                                    <option value="{{ $value }}" @selected($level === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                    <path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/>
                                                    <path d="M9.2 12.1l1.8 1.8 3.8-3.8"/>
                                                </svg>
                                                {{ $hasAccess ? 'บันทึกสิทธิ' : 'ให้สิทธิ' }}
                                            </button>
                                        </form>
                                        @if ($hasAccess)
                                            <form method="POST" action="{{ route('users.permissions.revoke') }}" onsubmit="return confirm('ถอนสิทธิของ {{ $person->full_name }} หรือไม่?')">
                                                @csrf
                                                <input type="hidden" name="username" value="{{ $person->username }}">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                        <circle cx="12" cy="12" r="9"/><path d="M8 12h8"/>
                                                    </svg>
                                                    ถอนสิทธิ
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pager">
                <div>
                    แสดง {{ $users->firstItem() }}–{{ $users->lastItem() }}
                    จาก {{ number_format($users->total()) }} รายการ
                </div>
                <div class="links">
                    @if ($users->onFirstPage())
                        <span class="page disabled">‹</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}">‹</a>
                    @endif

                    @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span class="page current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}">›</a>
                    @else
                        <span class="page disabled">›</span>
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
