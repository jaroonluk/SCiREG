@extends('layouts.app')

@section('title', 'พิมพ์แบบฟอร์มการเข้าสอบช้า')

@section('styles')
    main.app-main { max-width: min(100%, 1240px); width: 100%; }

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
        display: grid;
        gap: .45rem;
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
    .page-head p { color: var(--ink-muted); line-height: 1.5; max-width: 44rem; }

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
    .panel-filter { animation-delay: .05s; }
    .panel-list { animation-delay: .1s; }

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
    .panel-head h2 {
        font: 700 1.05rem 'Outfit','Sarabun',sans-serif;
    }
    .panel-head .sub {
        display: block; margin-top: .12rem;
        font-size: .82rem; font-weight: 500; color: var(--ink-muted);
    }
    .count-chip {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .35rem .75rem; border-radius: 999px;
        background: rgba(230,180,34,.16);
        color: var(--champaca-deep);
        font-size: .86rem; font-weight: 700;
        border: 1px solid rgba(201,146,26,.25);
    }
    .count-chip svg { width: .95rem; height: .95rem; }

    .filters {
        display: grid;
        grid-template-columns: repeat(3, minmax(140px, 1fr)) auto auto;
        gap: .85rem 1rem;
        align-items: end;
    }
    .field { display: grid; gap: .35rem; }
    .field label {
        font-size: .86rem; color: var(--ink-muted); font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .field label svg { width: .95rem; height: .95rem; color: var(--champaca-deep); }
    .field select {
        border: 1px solid rgba(201,146,26,.28);
        background: #fffef9;
        border-radius: .8rem;
        padding: .7rem .9rem;
        font: inherit; color: var(--ink); width: 100%;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .field select:focus {
        outline: none;
        border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.18);
    }

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
    .btn-primary:hover { filter: brightness(1.03); }
    .btn-sm { padding: .45rem .9rem; font-size: .85rem; }

    .alert {
        margin-bottom: 1rem; padding: .85rem 1rem; border-radius: .9rem;
        animation: rise .35s ease both;
        display: flex; align-items: flex-start; gap: .55rem;
    }
    .alert svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .1rem; }
    .alert-ok { background: #eefbf1; border: 1px solid #bbdfc4; color: #166534; }
    .alert-info {
        background: rgba(255,249,239,.95);
        border: 1px solid rgba(201,146,26,.28);
        color: var(--ink);
    }

    .signer-note {
        margin-top: 1.1rem;
        display: flex; align-items: flex-start; gap: .75rem;
        border: 1px dashed rgba(201,146,26,.4);
        border-radius: 1rem;
        padding: .95rem 1.05rem;
        background: rgba(230,180,34,.08);
        font-size: .92rem;
        color: var(--ink-muted);
    }
    .signer-icon {
        width: 2.2rem; height: 2.2rem; border-radius: .7rem; flex-shrink: 0;
        display: grid; place-items: center;
        background: linear-gradient(145deg, #f0c54a, var(--champaca-deep));
        color: #fffdf5;
    }
    .signer-icon svg { width: 1.1rem; height: 1.1rem; }
    .signer-note strong { color: var(--ink); }
    .signer-note a { color: var(--champaca-deep); font-weight: 600; }

    .table-wrap { overflow-x: auto; border-radius: .85rem; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; }
    th, td {
        padding: .85rem .8rem;
        text-align: left;
        font-size: .9rem;
        vertical-align: top;
    }
    th {
        color: var(--champaca-deep);
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .03em;
        background: rgba(230,180,34,.12);
        border-bottom: 1px solid rgba(201,146,26,.25);
    }
    th:first-child { border-radius: .7rem 0 0 0; }
    th:last-child { border-radius: 0 .7rem 0 0; }
    tbody tr {
        background: #fffef9;
        transition: background .12s ease;
    }
    tbody tr:hover { background: rgba(230,180,34,.08); }
    td { border-bottom: 1px solid rgba(201,146,26,.14); }
    .id-pill {
        display: inline-flex; align-items: center;
        padding: .2rem .55rem; border-radius: 999px;
        background: rgba(230,180,34,.14);
        color: var(--champaca-deep);
        font-weight: 700; font-size: .82rem;
    }
    .cell-main { font-weight: 600; color: var(--ink); }
    .muted { color: var(--ink-muted); font-size: .86rem; margin-top: .15rem; }
    .exam-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .28rem .65rem; border-radius: 999px;
        font-size: .8rem; font-weight: 700;
        border: 1px solid rgba(201,146,26,.28);
        background: rgba(230,180,34,.12);
        color: var(--champaca-deep);
    }
    .exam-badge.mid {
        background: rgba(59,130,246,.1);
        border-color: rgba(59,130,246,.25);
        color: #1d4ed8;
    }
    .exam-badge.final {
        background: rgba(230,180,34,.16);
        border-color: rgba(201,146,26,.35);
        color: var(--champaca-deep);
    }
    .empty-state {
        text-align: center;
        padding: 2rem 1rem;
        color: var(--ink-muted);
    }
    .empty-state .icon {
        width: 3.2rem; height: 3.2rem; margin: 0 auto .85rem;
        border-radius: 1rem;
        display: grid; place-items: center;
        background: rgba(230,180,34,.14);
        color: var(--champaca-deep);
    }
    .empty-state .icon svg { width: 1.5rem; height: 1.5rem; }

    @media (max-width: 960px) {
        .filters { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 640px) {
        .filters { grid-template-columns: 1fr; }
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
                    <path d="M7 8V4h10v4"/><path d="M6 8h12v4H6z"/><path d="M7 12v8h10v-8"/><path d="M9 15h6"/>
                </svg>
            </div>
            <div>
                <h1>พิมพ์แบบฟอร์มการเข้าสอบช้า</h1>
                <p>เลือกปี ภาค และช่วงสอบเพื่อดูรายการที่บันทึกไว้ แล้วเปิดพิมพ์แบบฟอร์ม A4</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-ok">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="panel panel-filter">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                </div>
                <div>
                    <h2>ตัวกรองรายการ</h2>
                    <span class="sub">ใช้ค่าเริ่มต้นจากภาคการศึกษาปัจจุบันได้ทันที</span>
                </div>
            </div>
        </div>

        <form class="filters" method="GET" action="{{ route('late-exam.print') }}">
            <input type="hidden" name="filter" value="1">
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
                <label for="term">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
                    ภาคการศึกษา
                </label>
                <select id="term" name="term">
                    <option value="1" @selected((int)$term === 1)>ภาคต้น</option>
                    <option value="2" @selected((int)$term === 2)>ภาคปลาย</option>
                </select>
            </div>
            <div class="field">
                <label for="exam_type">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="13" r="7"/><path d="M12 10v3.5l2.2 1.4M9 3.5h6"/></svg>
                    ช่วงสอบ
                </label>
                <select id="exam_type" name="exam_type">
                    <option value="M" @selected(($examType ?? 'F') === 'M')>กลางภาค</option>
                    <option value="F" @selected(($examType ?? 'F') === 'F')>ปลายภาค</option>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
                แสดงรายการ
            </button>
            <a class="btn" href="{{ route('late-exam.signers') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 19l3.2-1.1L18 7.1a2.1 2.1 0 0 0-3-3L4.2 14.9 4 19z"/><path d="M13.8 5.2l3 3"/></svg>
                กำหนดผู้ลงนาม
            </a>
        </form>

        <div class="signer-note">
            <div class="signer-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 19l3.2-1.1L18 7.1a2.1 2.1 0 0 0-3-3L4.2 14.9 4 19z"/><path d="M13.8 5.2l3 3"/></svg>
            </div>
            <div>
                @if($signer)
                    ผู้ลงนามปัจจุบัน: <strong>{{ $signer['signature_name'] }}</strong>
                    — {{ $signer['position'] ?: 'ไม่ระบุตำแหน่ง' }}
                    ({{ $signer['role_label'] }})
                @else
                    ยังไม่ได้กำหนดผู้บริหารสำหรับลงนาม —
                    <a href="{{ route('late-exam.signers') }}">ไปตั้งค่า</a>
                @endif
            </div>
        </div>
    </div>

    <div class="panel panel-list">
        <div class="panel-head">
            <div class="panel-head-left">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M8 4h8v4H8z"/><path d="M6 8h12v12H6z"/><path d="M9 13h6M9 16h4"/></svg>
                </div>
                <div>
                    <h2>รายการแบบฟอร์ม</h2>
                    <span class="sub">
                        ปี {{ $year }} · ภาค{{ (int)$term === 1 ? 'ต้น' : 'ปลาย' }} ·
                        {{ ($examType ?? 'F') === 'M' ? 'กลางภาค' : 'ปลายภาค' }}
                    </span>
                </div>
            </div>
            @if($filtered && $records->isNotEmpty())
                <span class="count-chip">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M8 6h8M8 10h8M8 14h5M6 4h12v16H6z"/></svg>
                    {{ number_format($records->count()) }} รายการ
                </span>
            @endif
        </div>

        @if(!$filtered)
            <div class="empty-state">
                <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg></div>
                เลือกปี/ภาค แล้วกด “แสดงรายการ”
            </div>
        @elseif($records->isEmpty())
            <div class="empty-state">
                <div class="icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M9 12h6"/></svg></div>
                ไม่พบรายการสำหรับปี {{ $year }} ภาค{{ (int)$term === 1 ? 'ต้น' : 'ปลาย' }}
                ช่วง{{ ($examType ?? 'F') === 'M' ? 'กลางภาค' : 'ปลายภาค' }}
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>วันเวลา</th>
                            <th>รหัส / ชื่อ</th>
                            <th>วิชา</th>
                            <th>ห้อง</th>
                            <th>ช่วงสอบ</th>
                            <th>สาเหตุ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $row)
                            <tr>
                                <td><span class="id-pill">{{ $row->formID }}</span></td>
                                <td>
                                    <div class="cell-main">{{ optional($row->LATETIME)->format('d/m/Y') ?? '—' }}</div>
                                    <div class="muted">{{ optional($row->LATETIME)->format('H:i') ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="cell-main">{{ $row->STUDENTCODE }}</div>
                                    <div class="muted">{{ $row->STUDENT_NAME }}</div>
                                </td>
                                <td>
                                    <div class="cell-main">{{ $row->COURSE_CODE }}</div>
                                    <div class="muted">{{ $row->COURSE_NAME }}</div>
                                </td>
                                <td>{{ $row->ROOM_NAME ?: '—' }}</td>
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
                                    <div class="cell-main">{{ $row->REASON_NAME ?: '—' }}</div>
                                    @if($row->DESCI)
                                        <div class="muted">{{ $row->DESCI }}</div>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" target="_blank"
                                       href="{{ route('late-exam.print.show', $row->formID) }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M7 8V4h10v4"/><path d="M6 8h12v4H6z"/><path d="M7 12v8h10v-8"/><path d="M9 15h6"/></svg>
                                        พิมพ์
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
