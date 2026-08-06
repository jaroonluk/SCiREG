@extends('layouts.app')

@section('title', 'เข้าดูข้อมูล log')

@section('styles')
    main.app-main { max-width: min(100%, 1280px); width: 100%; }

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
    .page-head p { color: var(--ink-muted); line-height: 1.5; max-width: 46rem; }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: .85rem;
        margin-bottom: 1rem;
        animation: rise .5s .04s ease both;
    }
    .stat-card {
        position: relative;
        background: linear-gradient(160deg, #fffef8, #fff6d8);
        border: 1px solid rgba(201,146,26,.28);
        border-radius: 1.1rem;
        padding: 1rem 1.1rem;
        display: flex; align-items: center; gap: .85rem;
        overflow: hidden;
    }
    .stat-card::after {
        content: '';
        position: absolute; right: -12px; top: -18px;
        width: 70px; height: 70px; border-radius: 50%;
        background: rgba(230,180,34,.12);
    }
    .stat-icon {
        width: 2.6rem; height: 2.6rem; border-radius: .85rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: rgba(230,180,34,.18);
        color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
        z-index: 1;
    }
    .stat-icon svg { width: 1.2rem; height: 1.2rem; }
    .stat-card .label {
        font-size: .82rem; color: var(--ink-muted); font-weight: 600;
        display: block;
    }
    .stat-card .value {
        font: 700 1.35rem 'Outfit','Sarabun',sans-serif;
        color: var(--champaca-deep);
        line-height: 1.2;
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

    .filters {
        display: grid;
        grid-template-columns: 1.4fr 1fr 1fr 1fr auto;
        gap: .75rem;
        align-items: end;
    }
    .field { display: grid; gap: .35rem; }
    .field label {
        font-size: .86rem; color: var(--ink-muted); font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .field label svg { width: .95rem; height: .95rem; color: var(--champaca-deep); }
    .field input, .field select {
        width: 100%; border: 1px solid rgba(201,146,26,.28);
        background: #fffef9; border-radius: .8rem;
        padding: .7rem .9rem; font: inherit; color: var(--ink);
    }
    .field input:focus, .field select:focus {
        outline: none; border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.18);
    }

    .btn {
        border: 1px solid var(--line); background: #fffef9; color: var(--ink);
        border-radius: .85rem; padding: .72rem 1.05rem;
        font: 600 .92rem inherit; cursor: pointer;
        display: inline-flex; align-items: center; gap: .4rem;
        text-decoration: none; white-space: nowrap;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn-primary {
        background: linear-gradient(145deg, #f0c94a, var(--champaca-deep));
        border-color: transparent; color: #fffdf5;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.65);
    }
    .btn-primary:hover { filter: brightness(1.03); }
    .btn-ghost:hover { border-color: var(--champaca); color: var(--champaca-deep); }

    .table-wrap { overflow-x: auto; margin-top: .35rem; }
    table.audit-table {
        width: 100%; border-collapse: separate; border-spacing: 0;
        min-width: 920px;
    }
    table.audit-table th {
        text-align: left; font-size: .78rem; font-weight: 700;
        color: var(--champaca-deep); letter-spacing: .02em;
        padding: .65rem .7rem; border-bottom: 2px solid rgba(201,146,26,.28);
        background: rgba(230,180,34,.08);
        white-space: nowrap;
    }
    table.audit-table td {
        padding: .75rem .7rem; border-bottom: 1px solid rgba(201,146,26,.14);
        vertical-align: top; font-size: .9rem;
    }
    table.audit-table tbody tr {
        transition: background .15s ease;
    }
    table.audit-table tbody tr:hover {
        background: rgba(230,180,34,.07);
    }

    .time-cell { white-space: nowrap; color: var(--ink-muted); font-size: .86rem; }
    .user-cell strong { display: block; font-weight: 700; }
    .user-cell span { font-size: .8rem; color: var(--ink-muted); }

    .module-chip {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .28rem .65rem; border-radius: 999px;
        font-size: .78rem; font-weight: 700;
        background: rgba(230,180,34,.14);
        color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
        white-space: nowrap;
    }
    .module-chip svg { width: .85rem; height: .85rem; }

    .action-text { font-weight: 600; color: var(--ink); }
    .desc-text { color: var(--ink-muted); font-size: .84rem; margin-top: .15rem; }
    .meta-line {
        display: flex; flex-wrap: wrap; gap: .35rem .7rem;
        margin-top: .35rem; font-size: .78rem; color: var(--ink-muted);
    }
    .meta-line span {
        display: inline-flex; align-items: center; gap: .25rem;
    }
    .meta-line svg { width: .8rem; height: .8rem; color: var(--champaca-deep); }

    .empty {
        text-align: center; padding: 2.5rem 1rem;
        color: var(--ink-muted);
    }
    .empty svg {
        width: 2.8rem; height: 2.8rem; margin: 0 auto .7rem;
        color: var(--champaca);
        display: block;
    }

    .pager {
        display: flex; justify-content: space-between; align-items: center;
        gap: .75rem; flex-wrap: wrap; margin-top: 1rem;
        font-size: .88rem; color: var(--ink-muted);
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

    details.payload {
        margin-top: .4rem;
    }
    details.payload summary {
        cursor: pointer; font-size: .78rem; font-weight: 700;
        color: var(--champaca-deep); list-style: none;
        display: inline-flex; align-items: center; gap: .25rem;
    }
    details.payload summary::-webkit-details-marker { display: none; }
    details.payload pre {
        margin: .45rem 0 0;
        padding: .65rem .75rem;
        background: #fffef9;
        border: 1px solid rgba(201,146,26,.22);
        border-radius: .65rem;
        font-size: .75rem;
        max-height: 160px;
        overflow: auto;
        white-space: pre-wrap;
        word-break: break-word;
    }

    @media (max-width: 960px) {
        .filters { grid-template-columns: 1fr 1fr; }
        .stat-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .filters { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
    @php
        $moduleIcons = [
            'auth' => '<path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.5 12.2l1.7 1.7 3.5-3.5"/>',
            'late_exam' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
            'research_fee' => '<rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 6V4h8v2"/>',
            'users' => '<circle cx="9" cy="8" r="3"/><path d="M3.5 19c.7-3 2.8-4.8 5.5-4.8S14 16 14.7 19"/><path d="M16 8h5M18.5 5.5v5"/>',
            'system' => '<path d="M12 3v3M12 18v3M3 12h3M18 12h3M5.6 5.6l2.1 2.1M16.3 16.3l2.1 2.1M18.4 5.6l-2.1 2.1M7.7 16.3l-2.1 2.1"/><circle cx="12" cy="12" r="3"/>',
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
                    <path d="M8 4h8a2 2 0 0 1 2 2v14l-3-1.5L12 20l-3-1.5L6 20V6a2 2 0 0 1 2-2z"/>
                    <path d="M9 9h6M9 12h6M9 15h4"/>
                </svg>
            </div>
            <div>
                <h1>เข้าดูข้อมูล log</h1>
                <p>ติดตามประวัติการเข้าใช้งานและกิจกรรมสำคัญในระบบ SCiREG เพื่อตรวจสอบความถูกต้องและความปลอดภัย</p>
            </div>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
                </svg>
            </div>
            <div>
                <span class="label">วันนี้</span>
                <span class="value">{{ number_format($stats['today']) }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M8 4h8a2 2 0 0 1 2 2v14l-3-1.5L12 20l-3-1.5L6 20V6a2 2 0 0 1 2-2z"/>
                    <path d="M9 9h6M9 12h4"/>
                </svg>
            </div>
            <div>
                <span class="label">ทั้งหมด</span>
                <span class="value">{{ number_format($stats['total']) }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="9" cy="8" r="3"/>
                    <path d="M3.5 19c.7-3 2.8-4.8 5.5-4.8S14 16 14.7 19"/>
                    <circle cx="17" cy="9" r="2.2"/>
                    <path d="M15.2 19c.4-1.8 1.6-3 3.3-3"/>
                </svg>
            </div>
            <div>
                <span class="label">ผู้ใช้งานที่มี log</span>
                <span class="value">{{ number_format($stats['users']) }}</span>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/>
                </svg>
            </div>
            <div>
                <h2>ค้นหาและกรอง</h2>
                <span class="sub">กรองตามผู้ใช้ โมดูล หรือช่วงวันที่</span>
            </div>
        </div>

        <form method="get" action="{{ route('audit-logs.index') }}" class="filters">
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/></svg>
                    ค้นหา
                </label>
                <input type="search" name="q" value="{{ $filters['q'] }}" placeholder="ชื่อผู้ใช้, กิจกรรม, คำอธิบาย…">
            </div>
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M7 12h10M10 18h4"/></svg>
                    โมดูล
                </label>
                <select name="module">
                    <option value="">ทั้งหมด</option>
                    @foreach ($modules as $key => $label)
                        <option value="{{ $key }}" @selected($filters['module'] === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                    ตั้งแต่วันที่
                </label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] }}">
            </div>
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                    ถึงวันที่
                </label>
                <input type="date" name="date_to" value="{{ $filters['date_to'] }}">
            </div>
            <div style="display:flex;gap:.45rem;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/></svg>
                    ค้นหา
                </button>
                <a class="btn btn-ghost" href="{{ route('audit-logs.index') }}">ล้าง</a>
            </div>
        </form>
    </div>

    <div class="panel" style="animation-delay:.12s">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M8 4h8a2 2 0 0 1 2 2v14l-3-1.5L12 20l-3-1.5L6 20V6a2 2 0 0 1 2-2z"/>
                    <path d="M9 9h6M9 12h6M9 15h4"/>
                </svg>
            </div>
            <div>
                <h2>รายการกิจกรรม</h2>
                <span class="sub">เรียงจากล่าสุดไปเก่าสุด · แสดงหน้าละ 30 รายการ</span>
            </div>
        </div>

        @if ($logs->isEmpty())
            <div class="empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                    <path d="M8 4h8a2 2 0 0 1 2 2v14l-3-1.5L12 20l-3-1.5L6 20V6a2 2 0 0 1 2-2z"/>
                    <path d="M9 10h6M9 14h4"/>
                </svg>
                <p>ไม่พบข้อมูล log ตามเงื่อนไขที่เลือก</p>
            </div>
        @else
            <div class="table-wrap">
                <table class="audit-table">
                    <thead>
                        <tr>
                            <th>เวลา</th>
                            <th>ผู้ใช้</th>
                            <th>โมดูล</th>
                            <th>กิจกรรม</th>
                            <th>รายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td class="time-cell">
                                    {{ optional($log->created_at)->timezone(config('app.timezone'))->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="user-cell">
                                    <strong>{{ $log->user_name ?: '—' }}</strong>
                                    <span>{{ $log->username ?: '—' }}</span>
                                </td>
                                <td>
                                    <span class="module-chip">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            {!! $moduleIcons[$log->module] ?? $moduleIcons['system'] !!}
                                        </svg>
                                        {{ $log->moduleLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-text">{{ $log->action }}</div>
                                    @if ($log->route_name)
                                        <div class="desc-text">{{ $log->route_name }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $log->description ?: '—' }}</div>
                                    <div class="meta-line">
                                        @if ($log->method)
                                            <span>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 12h16M13 5l7 7-7 7"/></svg>
                                                {{ $log->method }}
                                            </span>
                                        @endif
                                        @if ($log->ip_address)
                                            <span>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.8 2.8 2.8 15.2 0 18M12 3c-2.8 2.8-2.8 15.2 0 18"/></svg>
                                                {{ $log->ip_address }}
                                            </span>
                                        @endif
                                        @if ($log->status_code)
                                            <span>
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/></svg>
                                                HTTP {{ $log->status_code }}
                                            </span>
                                        @endif
                                    </div>
                                    @if (! empty($log->request_data))
                                        <details class="payload">
                                            <summary>
                                                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8v4H8zM6 8h12v12H6z"/><path d="M9 13h6M9 16h4"/></svg>
                                                ดูข้อมูลคำขอ
                                            </summary>
                                            <pre>{{ json_encode($log->request_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pager">
                <div>
                    แสดง {{ $logs->firstItem() }}–{{ $logs->lastItem() }}
                    จากทั้งหมด {{ number_format($logs->total()) }} รายการ
                </div>
                <div class="links">
                    @if ($logs->onFirstPage())
                        <span class="page">‹</span>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}">‹</a>
                    @endif

                    @foreach ($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
                        @if ($page == $logs->currentPage())
                            <span class="page current">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}">›</a>
                    @else
                        <span class="page">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
