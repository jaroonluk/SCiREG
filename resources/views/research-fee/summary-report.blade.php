@extends('layouts.app')

@section('title', 'รายงานค่าธรรมเนียมวิจัย')

@section('styles')
    main.app-main {
        max-width: min(100%, 1480px);
        width: 100%;
        padding: 1.5rem 1.25rem 2.5rem;
    }

    .page-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }
    .back {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        color: var(--ink-muted);
        text-decoration: none;
        font-size: .92rem;
    }
    .back:hover { color: var(--champaca-deep); }
    .page-title {
        display: flex;
        align-items: center;
        gap: .85rem;
        margin-top: .55rem;
    }
    .page-title-icon {
        width: 3rem;
        height: 3rem;
        border-radius: .9rem;
        display: grid;
        place-items: center;
        background: linear-gradient(145deg, rgba(230,180,34,.32), rgba(201,146,26,.18));
        color: var(--champaca-deep);
        flex-shrink: 0;
    }
    .page-title-icon svg { width: 1.45rem; height: 1.45rem; }
    .page-title h1 {
        font: 700 clamp(1.35rem, 3vw, 1.8rem) 'Outfit', 'Sarabun', sans-serif;
        line-height: 1.25;
    }
    .page-title p {
        margin-top: .25rem;
        color: var(--ink-muted);
        font-size: .95rem;
        line-height: 1.45;
    }

    .panel {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 1.15rem;
        box-shadow: 0 16px 36px -28px rgba(120,80,10,.4);
    }
    .filter-panel { padding: 1.15rem 1.2rem; margin-bottom: 1rem; }
    .filters {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr)) auto;
        gap: .85rem;
        align-items: end;
    }
    .field { display: grid; gap: .3rem; min-width: 0; }
    .field label {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        color: var(--ink-muted);
        font-size: .82rem;
        font-weight: 600;
    }
    .field label svg { width: .95rem; height: .95rem; }
    select {
        width: 100%;
        border: 1px solid var(--line);
        background: #fff;
        border-radius: .7rem;
        padding: .62rem .75rem;
        font: inherit;
        color: var(--ink);
    }
    select:focus {
        outline: none;
        border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.15);
    }
    .filter-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }
    .btn {
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink);
        padding: .62rem 1rem;
        border-radius: 999px;
        font: 600 .9rem inherit;
        text-decoration: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
        white-space: nowrap;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn-primary {
        background: linear-gradient(145deg, var(--champaca), var(--champaca-deep));
        border-color: transparent;
        color: #fff;
    }
    .btn-excel {
        background: linear-gradient(145deg, #2f9e5d, #1f7a46);
        border-color: transparent;
        color: #fff;
    }
    .btn:hover { border-color: var(--champaca); transform: translateY(-1px); }

    .summary {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: .65rem;
        margin-bottom: 1rem;
    }
    .stat {
        background: rgba(255,252,245,.92);
        border: 1px solid var(--line);
        border-radius: .95rem;
        padding: .85rem 1rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        min-width: 0;
    }
    .stat-icon {
        width: 2.35rem;
        height: 2.35rem;
        border-radius: .7rem;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        background: rgba(230,180,34,.16);
        color: var(--champaca-deep);
    }
    .stat-icon svg { width: 1.15rem; height: 1.15rem; }
    .stat small { display: block; color: var(--ink-muted); font-size: .8rem; }
    .stat strong {
        font: 700 1.25rem 'Outfit', 'Sarabun', sans-serif;
        color: var(--champaca-deep);
        word-break: break-word;
    }
    .stat.paid .stat-icon { background: rgba(34,197,94,.14); color: #166534; }
    .stat.paid strong { color: #166534; }
    .stat.exempt .stat-icon { background: rgba(100,116,139,.14); color: #475569; }
    .stat.exempt strong { color: #475569; }
    .stat.pending .stat-icon { background: rgba(234,88,12,.14); color: #9a3412; }
    .stat.pending strong { color: #9a3412; }

    .report-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        flex-wrap: wrap;
        padding: 1rem 1.15rem;
        border-bottom: 1px solid rgba(201,146,26,.14);
    }
    .report-meta h2 {
        font: 700 1.05rem 'Outfit', 'Sarabun', sans-serif;
        display: flex;
        align-items: center;
        gap: .45rem;
    }
    .report-meta h2 svg { width: 1.15rem; height: 1.15rem; color: var(--champaca-deep); }
    .report-meta p { color: var(--ink-muted); font-size: .9rem; }

    .table-wrap { overflow: hidden; }
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    th, td {
        padding: .78rem .7rem;
        border-bottom: 1px solid rgba(201,146,26,.12);
        font-size: .92rem;
        vertical-align: middle;
    }
    thead th {
        background: rgba(230,180,34,.12);
        color: var(--ink-muted);
        font-size: .8rem;
        font-weight: 700;
        text-align: center;
    }
    thead th.group {
        border-bottom: 1px solid rgba(201,146,26,.22);
    }
    .col-depart { width: 22%; text-align: left; }
    .col-code { width: 16%; text-align: center; }
    .col-num { width: 12%; text-align: center; }
    .col-money { width: 14%; text-align: right; }
    td.depart {
        font-weight: 700;
        background: rgba(255,252,245,.75);
        border-right: 1px solid rgba(201,146,26,.1);
    }
    td.code {
        text-align: center;
        font-weight: 600;
        color: var(--ink-muted);
    }
    td.num { text-align: center; font-variant-numeric: tabular-nums; }
    td.money {
        text-align: right;
        font-variant-numeric: tabular-nums;
        font-weight: 600;
        color: #166534;
    }
    tbody tr:hover td:not(.depart) { background: rgba(230,180,34,.06); }
    tfoot td {
        background: rgba(230,180,34,.14);
        font-weight: 700;
        border-bottom: none;
    }
    .empty {
        padding: 2.5rem 1rem;
        text-align: center;
        color: var(--ink-muted);
    }
    .empty svg {
        width: 2.5rem;
        height: 2.5rem;
        margin: 0 auto .75rem;
        color: var(--champaca-deep);
        opacity: .7;
        display: block;
    }

    @media (max-width: 1100px) {
        .filters { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .filter-actions { grid-column: 1 / -1; }
        .summary { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 720px) {
        .filters, .summary { grid-template-columns: 1fr 1fr; }
        table { table-layout: auto; }
        .col-depart, .col-code, .col-num, .col-money { width: auto; }
    }
@endsection

@section('content')
    <div class="page-head">
        <div>
            <a class="back" href="{{ route('home') }}">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 5l-7 7 7 7"/></svg>
                กลับเมนูหลัก
            </a>
            <div class="page-title">
                <div class="page-title-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 19V5M4 19h16"/>
                        <path d="M8 16V10M12 16V7M16 16v-4"/>
                    </svg>
                </div>
                <div>
                    <h1>รายงานค่าธรรมเนียมวิจัย</h1>
                    <p>สรุปจำนวนนักศึกษาและการรับชำระ แยกตามสาขาวิชาและรหัสประจำตัว</p>
                </div>
            </div>
        </div>
    </div>

    <form class="panel filter-panel" method="GET" action="{{ route('research-fee.summary') }}">
        <div class="filters">
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
                    ปีการศึกษา
                </label>
                <select name="year">
                    @foreach($years as $year)
                        <option value="{{ $year }}" @selected($filters['year'] === $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l2.5 1.5"/></svg>
                    ภาคการศึกษา
                </label>
                <select name="term">
                    <option value="1" @selected($filters['term'] === 1)>1</option>
                    <option value="2" @selected($filters['term'] === 2)>2</option>
                </select>
            </div>
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20V9l8-5 8 5v11"/><path d="M9 20v-6h6v6"/></svg>
                    สาขาวิชา
                </label>
                @if($departmentLocked ?? false)
                    <input type="hidden" name="depart_id" value="{{ $filters['depart_id'] }}">
                    <select disabled>
                        @foreach($departments as $department)
                            <option value="{{ $department->depart_id }}" @selected($filters['depart_id'] === (int) $department->depart_id)>
                                {{ $department->depart_name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <select name="depart_id">
                        <option value="0">ทั้งหมด</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->depart_id }}" @selected($filters['depart_id'] === (int) $department->depart_id)>
                                {{ $department->depart_name }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="filter-actions">
                <button class="btn btn-primary" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="6.5"/><path d="M16.5 16.5L21 21"/></svg>
                    แสดงรายงาน
                </button>
                <a class="btn btn-excel" href="{{ route('research-fee.summary.export', $filters) }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/>
                        <path d="M14 3v5h5M8.5 17l2.2-6h.6l2.2 6M9.2 15h3.6"/>
                    </svg>
                    พิมพ์รายงาน Excel
                </a>
            </div>
        </div>
    </form>

    @php $totals = $report['totals']; @endphp

    <div class="summary">
        <div class="stat">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c.7-3 2.8-4.8 5.5-4.8S14 16 14.7 19"/><path d="M16 8h5M18.5 5.5v5"/></svg>
            </div>
            <div><small>นักศึกษาทั้งหมด</small><strong>{{ number_format($totals['total']) }}</strong></div>
        </div>
        <div class="stat paid">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M8.5 12.2l2.3 2.3 4.7-4.8"/></svg>
            </div>
            <div><small>ที่ชำระแล้ว</small><strong>{{ number_format($totals['paid']) }}</strong></div>
        </div>
        <div class="stat exempt">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/></svg>
            </div>
            <div><small>ที่ขอยกเว้น</small><strong>{{ number_format($totals['exempt']) }}</strong></div>
        </div>
        <div class="stat pending">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 7.5v5l3 1.8"/></svg>
            </div>
            <div><small>ที่คงค้าง</small><strong>{{ number_format($totals['pending']) }}</strong></div>
        </div>
        <div class="stat paid">
            <div class="stat-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16v10H4z"/><path d="M4 10h16M8 14h2"/></svg>
            </div>
            <div><small>ยอดรับชำระแล้ว</small><strong>{{ number_format($totals['amount'], 2) }}</strong></div>
        </div>
    </div>

    <div class="panel table-wrap">
        <div class="report-meta">
            <div>
                <h2>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8v3H8zM6 7h12v13H6z"/><path d="M9 11h6M9 14h6M9 17h4"/></svg>
                    รายงานการชำระเงินค่าธรรมเนียมวิจัย
                </h2>
                <p>สำหรับนักศึกษาระดับบัณฑิตศึกษา คณะวิทยาศาสตร์ · ปีการศึกษา {{ $filters['year'] }} ภาคเรียนที่ {{ $filters['term'] }}</p>
            </div>
        </div>

        @if(count($report['sections']) === 0)
            <div class="empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="11" cy="11" r="6.5"/><path d="M16.5 16.5L21 21"/><path d="M8.5 11h5"/></svg>
                ไม่พบข้อมูลตามเงื่อนไขที่เลือก
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th class="col-depart group" rowspan="2">สาขาวิชา</th>
                        <th class="col-code group" rowspan="2">นักศึกษาแยกตาม<br>รหัสประจำตัว</th>
                        <th class="group" colspan="4">จำนวนนักศึกษา</th>
                        <th class="col-money group" rowspan="2">จำนวนเงินที่<br>รับชำระแล้ว</th>
                    </tr>
                    <tr>
                        <th class="col-num">ทั้งหมด</th>
                        <th class="col-num">ที่ชำระ</th>
                        <th class="col-num">ที่ขอยกเว้น</th>
                        <th class="col-num">ที่คงค้าง</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['sections'] as $section)
                        @foreach($section['rows'] as $index => $row)
                            <tr>
                                @if($index === 0)
                                    <td class="depart" rowspan="{{ $section['row_count'] }}">{{ $section['depart_name'] }}</td>
                                @endif
                                <td class="code">รหัส {{ $row['code'] }}</td>
                                <td class="num">{{ number_format($row['total']) }}</td>
                                <td class="num">{{ number_format($row['paid']) }}</td>
                                <td class="num">{{ number_format($row['exempt']) }}</td>
                                <td class="num">{{ number_format($row['pending']) }}</td>
                                <td class="money">{{ number_format($row['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:center">รวม</td>
                        <td class="num">{{ number_format($totals['total']) }}</td>
                        <td class="num">{{ number_format($totals['paid']) }}</td>
                        <td class="num">{{ number_format($totals['exempt']) }}</td>
                        <td class="num">{{ number_format($totals['pending']) }}</td>
                        <td class="money">{{ number_format($totals['amount'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>
@endsection
