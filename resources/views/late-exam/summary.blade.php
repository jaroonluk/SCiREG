@extends('layouts.app')

@section('title', 'รายงานสรุปการเข้าสอบช้า')

@section('styles')
    main.app-main { max-width: min(100%, 1400px); width: 100%; }

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

    .panel {
        position: relative;
        background: linear-gradient(180deg, rgba(255,253,247,.98), rgba(255,250,236,.94));
        border: 1px solid var(--line);
        border-radius: 1.2rem;
        box-shadow: 0 18px 40px -30px rgba(120,80,10,.5);
        padding: 1.25rem 1.3rem 1.35rem;
        margin-bottom: 1rem;
        overflow: hidden;
        animation: rise .5s ease both;
    }
    .panel::before {
        content: '';
        position: absolute; inset: 0 auto 0 0; width: 4px;
        background: linear-gradient(180deg, var(--champaca), var(--champaca-deep));
        border-radius: 1.2rem 0 0 1.2rem;
    }

    .panel-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: .85rem; flex-wrap: wrap; margin-bottom: 1rem;
    }
    .panel-head-left { display: flex; align-items: center; gap: .7rem; }
    .step-badge {
        width: 2.15rem; height: 2.15rem; border-radius: .7rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: rgba(230,180,34,.16);
        color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
    }
    .step-badge svg { width: 1.1rem; height: 1.1rem; }
    .panel-head h2, .panel-head h3 {
        font: 700 1.05rem 'Outfit','Sarabun',sans-serif;
    }
    .panel-head .sub {
        display: block; margin-top: .12rem;
        font-size: .82rem; font-weight: 500; color: var(--ink-muted);
    }

    .filters {
        display: grid;
        grid-template-columns: 160px 1fr 1fr auto;
        gap: .85rem; align-items: end;
    }
    .field { display: grid; gap: .35rem; }
    .field label {
        font-size: .86rem; color: var(--ink-muted); font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .field label svg { width: .95rem; height: .95rem; color: var(--champaca-deep); }
    .field select {
        width: 100%; border: 1px solid rgba(201,146,26,.28);
        background: #fffef9; border-radius: .8rem;
        padding: .7rem .9rem; font: inherit; color: var(--ink);
    }
    .field select:focus {
        outline: none; border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.18);
    }
    .checks { display: flex; flex-wrap: wrap; gap: .55rem .9rem; padding: .35rem 0; }
    .checks label {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .92rem; font-weight: 500; color: var(--ink);
        padding: .4rem .7rem; border-radius: 999px;
        background: #fffef9; border: 1px solid rgba(201,146,26,.22);
        cursor: pointer;
    }
    .checks label:has(input:checked) {
        background: rgba(230,180,34,.16);
        border-color: rgba(201,146,26,.45);
        color: var(--champaca-deep);
        font-weight: 700;
    }
    .checks input { accent-color: var(--champaca-deep); }

    .btn {
        border: 1px solid var(--line); background: #fffef9; color: var(--ink);
        padding: .7rem 1.15rem; border-radius: 999px; font: 600 .9rem inherit;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: .4rem;
        transition: transform .12s ease, border-color .12s ease;
        white-space: nowrap;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn:hover { border-color: var(--champaca); transform: translateY(-1px); }
    .btn-primary {
        background: linear-gradient(145deg, #f0c54a, var(--champaca-deep));
        border-color: transparent; color: #fffdf5;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.7);
    }
    .btn-excel {
        background: linear-gradient(145deg, #43a047, #2e7d32);
        border-color: transparent; color: #fff;
        box-shadow: 0 10px 22px -14px rgba(30,100,40,.55);
    }
    .btn-excel:hover { filter: brightness(1.05); border-color: transparent; }

    .stats {
        display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: .75rem; margin-bottom: 1rem;
    }
    .stat {
        background: linear-gradient(180deg, #fffef9, rgba(255,250,236,.95));
        border: 1px solid var(--line);
        border-radius: 1rem; padding: 1rem 1.05rem;
        animation: rise .45s ease both;
        display: flex; gap: .75rem; align-items: center;
    }
    .stat-icon {
        width: 2.4rem; height: 2.4rem; border-radius: .75rem; flex-shrink: 0;
        display: grid; place-items: center;
        background: rgba(230,180,34,.16);
        color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.25);
    }
    .stat-icon svg { width: 1.15rem; height: 1.15rem; }
    .stat .label { font-size: .82rem; color: var(--ink-muted); font-weight: 600; }
    .stat .value {
        font: 700 1.45rem 'Outfit','Sarabun',sans-serif;
        color: var(--champaca-deep); margin-top: .15rem;
    }

    .charts {
        display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;
    }
    .chart-box {
        background: linear-gradient(180deg, #fffef9, rgba(255,250,236,.95));
        border: 1px solid var(--line);
        border-radius: 1.1rem; padding: 1rem 1.1rem;
        min-height: 280px;
        animation: rise .5s ease both;
    }
    .chart-box h3 {
        font: 700 .98rem 'Outfit','Sarabun',sans-serif;
        margin-bottom: .75rem;
        display: flex; align-items: center; gap: .45rem;
        color: var(--ink);
    }
    .chart-box h3 svg { width: 1rem; height: 1rem; color: var(--champaca-deep); }

    .table-wrap { overflow-x: auto; border-radius: .85rem; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; }
    th, td {
        padding: .75rem .8rem; text-align: left; font-size: .9rem; vertical-align: top;
    }
    th {
        color: var(--champaca-deep); font-size: .78rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .03em;
        background: rgba(230,180,34,.12);
        border-bottom: 1px solid rgba(201,146,26,.25);
    }
    th:first-child { border-radius: .7rem 0 0 0; }
    th:last-child { border-radius: 0 .7rem 0 0; }
    tbody tr { background: #fffef9; transition: background .12s ease; }
    tbody tr:hover { background: rgba(230,180,34,.08); }
    td { border-bottom: 1px solid rgba(201,146,26,.14); }
    .muted { color: var(--ink-muted); font-size: .86rem; }
    .cell-main { font-weight: 600; }
    .exam-badge {
        display: inline-flex; align-items: center;
        padding: .25rem .6rem; border-radius: 999px;
        font-size: .8rem; font-weight: 700;
    }
    .exam-badge.mid {
        background: rgba(59,130,246,.1); color: #1d4ed8;
        border: 1px solid rgba(59,130,246,.25);
    }
    .exam-badge.final {
        background: rgba(230,180,34,.16); color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.35);
    }
    .count-chip {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .35rem .75rem; border-radius: 999px;
        background: rgba(230,180,34,.16);
        color: var(--champaca-deep);
        font-size: .86rem; font-weight: 700;
        border: 1px solid rgba(201,146,26,.25);
    }
    .alert-info {
        background: rgba(255,249,239,.95);
        border: 1px solid rgba(201,146,26,.28);
        color: var(--ink);
        padding: 1rem 1.1rem; border-radius: 1rem;
        display: flex; align-items: center; gap: .6rem;
        animation: rise .35s ease both;
    }
    .alert-info svg { width: 1.15rem; height: 1.15rem; color: var(--champaca-deep); flex-shrink: 0; }

    @media (max-width: 960px) {
        .filters { grid-template-columns: 1fr 1fr; }
        .stats, .charts { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
    <div class="page-head">
        <a class="back" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M15 6 9 12l6 6"/></svg>
            กลับเมนูหลัก
        </a>
        <div class="title-row">
            <div class="title-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19V5M4 19h16"/><path d="M8 16V11M12 16V8M16 16v-3"/>
                </svg>
            </div>
            <div>
                <h1>รายงานสรุปการเข้าสอบช้า</h1>
                <p>สรุปจำนวนตามสาเหตุ เดือน และสาขาวิชา พร้อมรายการทั้งหมดสำหรับผู้บริหาร</p>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
                </div>
                <div>
                    <h2>เงื่อนไขรายงาน</h2>
                    <span class="sub">เลือกปี ภาค และช่วงสอบ แล้วแสดงรายงาน</span>
                </div>
            </div>
        </div>
        <form class="filters" method="GET" action="{{ route('late-exam.summary') }}">
            <input type="hidden" name="run" value="1">
            <div class="field">
                <label for="year">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                    ปีการศึกษา
                </label>
                <select id="year" name="year">
                    @foreach($years as $y)
                        <option value="{{ $y }}" @selected((int)$year === (int)$y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
                    ภาคการศึกษา
                </label>
                <div class="checks">
                    <label><input type="checkbox" name="terms[]" value="1" @checked(in_array(1, $terms, true))> ภาคต้น</label>
                    <label><input type="checkbox" name="terms[]" value="2" @checked(in_array(2, $terms, true))> ภาคปลาย</label>
                </div>
            </div>
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="13" r="7"/><path d="M12 10v3.5l2.2 1.4M9 3.5h6"/></svg>
                    ช่วงสอบ
                </label>
                <div class="checks">
                    <label><input type="checkbox" name="exam_types[]" value="M" @checked(in_array('M', $examTypes, true))> กลางภาค</label>
                    <label><input type="checkbox" name="exam_types[]" value="F" @checked(in_array('F', $examTypes, true))> ปลายภาค</label>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 19V5M4 19h16"/><path d="M8 16V11M12 16V8M16 16v-3"/></svg>
                แสดงรายงาน
            </button>
        </form>
    </div>

    @if(!$ran)
        <div class="alert-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16.2h.01"/></svg>
            เลือกเงื่อนไขแล้วกด “แสดงรายงาน”
        </div>
    @elseif($summary['total'] === 0)
        <div class="alert-info">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M9 12h6"/></svg>
            ไม่พบข้อมูลตามเงื่อนไขที่เลือก
        </div>
    @else
        <div class="stats">
            <div class="stat">
                <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M8 6h8M8 10h8M8 14h5M6 4h12v16H6z"/></svg></div>
                <div>
                    <div class="label">รวมทั้งหมด</div>
                    <div class="value">{{ number_format($summary['total']) }}</div>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16.2h.01"/></svg></div>
                <div>
                    <div class="label">จำนวนสาเหตุ</div>
                    <div class="value">{{ number_format(count($summary['by_reason'])) }}</div>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg></div>
                <div>
                    <div class="label">เดือนที่มีข้อมูล</div>
                    <div class="value">{{ number_format(count($summary['by_month'])) }}</div>
                </div>
            </div>
            <div class="stat">
                <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 20V6l8-3 8 3v14"/><path d="M9 20v-6h6v6"/></svg></div>
                <div>
                    <div class="label">สาขาวิชา</div>
                    <div class="value">{{ number_format(count($summary['by_department'])) }}</div>
                </div>
            </div>
        </div>

        <div class="charts">
            <div class="chart-box">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 19V5M4 19h16"/><path d="M8 16V10M12 16V7M16 16v-4"/></svg>
                    จำนวนตามสาเหตุ
                </h3>
                <canvas id="chartReason" height="220"></canvas>
            </div>
            <div class="chart-box">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 16c3-6 5-8 8-8s5 2 8 8"/><path d="M4 19h16"/></svg>
                    จำนวนตามเดือน
                </h3>
                <canvas id="chartMonth" height="220"></canvas>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <div class="step-badge" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 20V6l8-3 8 3v14"/><path d="M9 20v-6h6v6"/></svg>
                    </div>
                    <div>
                        <h3>สรุปตามสาขาวิชา</h3>
                        <span class="sub">จำนวนรายการแยกตามสาขา</span>
                    </div>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>สาขาวิชา</th><th>จำนวน</th></tr>
                    </thead>
                    <tbody>
                        @foreach($summary['by_department'] as $dep)
                            <tr>
                                <td class="cell-main">{{ $dep['department'] }}</td>
                                <td>{{ number_format($dep['count']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <div class="panel-head">
                <div class="panel-head-left">
                    <div class="step-badge" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M8 6h8M8 10h8M8 14h5M6 4h12v16H6z"/></svg>
                    </div>
                    <div>
                        <h3>รายการทั้งหมด</h3>
                        <span class="sub">รายละเอียดนักศึกษาที่เข้าสอบช้าตามเงื่อนไข</span>
                    </div>
                </div>
                <div style="display:flex;gap:.55rem;flex-wrap:wrap;align-items:center">
                    <span class="count-chip">{{ number_format($summary['total']) }} รายการ</span>
                    <a class="btn btn-excel"
                       href="{{ route('late-exam.summary.export', [
                           'year' => $year,
                           'terms' => $terms,
                           'exam_types' => $examTypes,
                       ]) }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8l-5-5Z"/><path d="M14 3v5h5M8 13h8M8 17h5"/></svg>
                        Export Excel
                    </a>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>วันเวลา</th>
                            <th>รหัส</th>
                            <th>ชื่อ</th>
                            <th>สาขาวิชา</th>
                            <th>วิชา</th>
                            <th>ช่วงสอบ</th>
                            <th>สาเหตุ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary['rows'] as $row)
                            <tr>
                                <td>{{ optional($row->LATETIME)->format('d/m/Y H:i') ?? '—' }}</td>
                                <td class="cell-main">{{ $row->STUDENTCODE }}</td>
                                <td>{{ $row->STUDENT_NAME }}</td>
                                <td>{{ $row->DEPARTMENT_NAME ?: '—' }}</td>
                                <td>
                                    <div class="cell-main">{{ $row->COURSE_CODE }}</div>
                                    <div class="muted">{{ $row->COURSE_NAME }}</div>
                                </td>
                                <td>
                                    @if($row->EXAM_TYPE === 'M')
                                        <span class="exam-badge mid">กลางภาค</span>
                                    @elseif($row->EXAM_TYPE === 'F')
                                        <span class="exam-badge final">ปลายภาค</span>
                                    @else
                                        <span class="muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="cell-main">{{ $row->REASON_NAME }}</div>
                                    @if($row->DESCI)
                                        <div class="muted">{{ $row->DESCI }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@if($ran && $summary['total'] > 0)
<script>
(() => {
    const reasonLabels = @json(collect($summary['by_reason'])->pluck('reason_name'));
    const reasonData = @json(collect($summary['by_reason'])->pluck('count'));
    const monthLabels = @json(collect($summary['by_month'])->pluck('label'));
    const monthData = @json(collect($summary['by_month'])->pluck('count'));
    const champaca = '#c9921a';
    const soft = 'rgba(230, 180, 34, 0.55)';

    new Chart(document.getElementById('chartReason'), {
        type: 'bar',
        data: {
            labels: reasonLabels,
            datasets: [{ label: 'จำนวน', data: reasonData, backgroundColor: soft, borderColor: champaca, borderWidth: 1 }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    new Chart(document.getElementById('chartMonth'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'จำนวน',
                data: monthData,
                borderColor: champaca,
                backgroundColor: soft,
                fill: true,
                tension: .25
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
})();
</script>
@endif
@endpush
