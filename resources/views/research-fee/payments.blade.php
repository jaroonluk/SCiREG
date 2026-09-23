@extends('layouts.app')

@section('title', 'จัดการข้อมูลชำระเงินค่าธรรมเนียมวิจัย')

@section('styles')
    main.app-main { max-width: min(100%, 1480px); width: 100%; padding: 1.5rem 1.25rem 2.5rem; }

    @keyframes rise {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes softPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(230,180,34,.28); }
        50% { box-shadow: 0 0 0 8px rgba(230,180,34,0); }
    }

    .page-head { margin-bottom: 1.3rem; animation: rise .45s ease both; display: grid; gap: .45rem; }
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
        color: #fffdf5; box-shadow: 0 12px 24px -14px rgba(150,100,10,.55);
        animation: softPulse 2.8s ease-in-out infinite;
    }
    .title-icon svg { width: 1.45rem; height: 1.45rem; }
    .page-head h1 {
        font: 700 clamp(1.35rem,3vw,1.8rem) 'Outfit','Sarabun',sans-serif; line-height: 1.2;
    }
    .page-head p { color: var(--ink-muted); line-height: 1.5; max-width: 44rem; }

    .panel {
        position: relative;
        background: linear-gradient(180deg, rgba(255,253,247,.98), rgba(255,250,236,.94));
        border: 1px solid var(--line); border-radius: 1.2rem;
        box-shadow: 0 18px 40px -30px rgba(120,80,10,.5);
        padding: 1.2rem 1.25rem 1.3rem; margin-bottom: 1rem; overflow: hidden;
        animation: rise .5s ease both;
    }
    .panel::before {
        content: ''; position: absolute; inset: 0 auto 0 0; width: 4px;
        background: linear-gradient(180deg, var(--champaca), var(--champaca-deep));
        border-radius: 1.2rem 0 0 1.2rem;
    }
    .panel-head { display: flex; align-items: center; gap: .7rem; margin-bottom: 1rem; }
    .step-badge {
        width: 2.15rem; height: 2.15rem; border-radius: .7rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: rgba(230,180,34,.16); color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
    }
    .step-badge svg { width: 1.1rem; height: 1.1rem; }
    .panel-head h2 { font: 700 1.05rem 'Outfit','Sarabun',sans-serif; }
    .panel-head .sub { display: block; margin-top: .1rem; font-size: .82rem; color: var(--ink-muted); font-weight: 500; }

    .filters { display: grid; grid-template-columns: repeat(5,minmax(0,1fr)); gap: .75rem; align-items: end; }
    .field { display: grid; gap: .3rem; min-width: 0; }
    .field label {
        color: var(--ink-muted); font-size: .82rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: .3rem;
    }
    .field label svg { width: .9rem; height: .9rem; color: var(--champaca-deep); }
    select, input {
        width: 100%; max-width: 100%; border: 1px solid rgba(201,146,26,.28);
        background: #fffef9; border-radius: .75rem; padding: .62rem .75rem; font: inherit; color: var(--ink);
    }
    select:focus, input:focus {
        outline: none; border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.15);
    }
    .actions { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .9rem; }
    .btn {
        border: 1px solid rgba(201,146,26,.28); background: #fffef9; color: var(--ink);
        padding: .58rem 1rem; border-radius: .85rem; font: 600 .9rem inherit;
        text-decoration: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center; gap: .35rem; white-space: nowrap;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn-primary {
        background: linear-gradient(145deg, #f0c94a, var(--champaca-deep));
        border-color: transparent; color: #fffdf5;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.65);
    }
    .btn-primary:hover { filter: brightness(1.03); }
    .btn:hover { border-color: var(--champaca); color: var(--champaca-deep); }
    .btn-primary:hover { color: #fffdf5; }

    .summary {
        display: grid; grid-template-columns: repeat(5,minmax(0,1fr)); gap: .7rem;
        margin-bottom: 1rem; animation: rise .5s .05s ease both;
    }
    .stat {
        position: relative; overflow: hidden;
        background: linear-gradient(160deg, #fffef8, #fff6d8);
        border: 1px solid rgba(201,146,26,.28); border-radius: 1rem;
        padding: .9rem 1rem; display: flex; align-items: center; gap: .7rem; min-width: 0;
    }
    .stat::after {
        content: ''; position: absolute; right: -10px; top: -14px;
        width: 56px; height: 56px; border-radius: 50%; background: rgba(230,180,34,.12);
    }
    .stat-icon {
        width: 2.35rem; height: 2.35rem; border-radius: .75rem;
        display: grid; place-items: center; flex-shrink: 0; z-index: 1;
        background: rgba(230,180,34,.18); color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
    }
    .stat-icon svg { width: 1.1rem; height: 1.1rem; }
    .stat.pending .stat-icon { background: rgba(234,88,12,.12); color: #9a3412; border-color: rgba(234,88,12,.22); }
    .stat.pending strong { color: #9a3412; }
    .stat.exempt .stat-icon { background: rgba(100,116,139,.12); color: #475569; border-color: rgba(100,116,139,.22); }
    .stat.exempt strong { color: #475569; }
    .stat.paid .stat-icon { background: rgba(34,197,94,.12); color: #166534; border-color: rgba(34,197,94,.22); }
    .stat.paid strong { color: #166534; }
    .stat small { display: block; color: var(--ink-muted); font-size: .78rem; font-weight: 600; }
    .stat strong { font: 700 1.25rem 'Outfit','Sarabun',sans-serif; color: var(--champaca-deep); word-break: break-word; }

    .alert {
        margin-bottom: 1rem; padding: .8rem 1rem; border-radius: .9rem;
        background: #eefbf1; border: 1px solid #bbdfc4; color: #166534;
        display: flex; gap: .5rem; align-items: flex-start; animation: rise .35s ease both;
    }
    .alert svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .05rem; }

    .table-card { padding: 0; animation-delay: .1s; }
    .table-card .panel-head { padding: 1.15rem 1.25rem 0; }
    .table-wrap { overflow-x: auto; }
    table { border-collapse: collapse; width: 100%; table-layout: fixed; min-width: 1020px; }
    col.col-no { width: 2.8rem; }
    col.col-student { width: 13%; }
    col.col-course { width: 14%; }
    col.col-depart { width: 11%; }
    col.col-payment { width: auto; }
    col.col-docs { width: 8.5rem; }
    th, td {
        padding: .72rem .55rem; border-bottom: 1px solid rgba(201,146,26,.12);
        text-align: left; vertical-align: top; font-size: .88rem; word-break: break-word;
    }
    th {
        background: rgba(230,180,34,.1); color: var(--champaca-deep);
        font-size: .78rem; font-weight: 700;
    }
    tbody tr:hover td { background: rgba(230,180,34,.06); }
    .student strong { display: block; }
    .student small { color: var(--ink-muted); }
    .payment-form { display: flex; flex-wrap: wrap; gap: .35rem; align-items: center; min-width: 0; }
    .payment-form select { flex: 0 1 8.5rem; max-width: 9.25rem; }
    .payment-form .paid-fields { display: contents; }
    .payment-form input[type="number"] { flex: 0 1 6rem; max-width: 7rem; }
    .payment-form input[name="slip_no"] {
        flex: 1 1 13rem;
        min-width: 12rem;
        max-width: 17rem;
        letter-spacing: .02em;
    }
    .payment-form .btn { flex: 0 0 auto; border-radius: .7rem; padding: .45rem .75rem; font-size: .86rem; }
    .status-1 { color: #9a3412; } .status-2 { color: #475569; } .status-3 { color: #166534; }

    .docs-head { display: flex; flex-direction: column; gap: .35rem; align-items: flex-start; }
    .docs-legend {
        display: flex; flex-direction: column; gap: .2rem;
        font-size: .72rem; font-weight: 500; color: var(--ink-muted); line-height: 1.25;
    }
    .docs-legend span { display: inline-flex; align-items: center; gap: .3rem; }
    .docs-legend .dot { width: .45rem; height: .45rem; border-radius: 50%; flex-shrink: 0; }
    .docs-legend .dot.student { background: #2563eb; }
    .docs-legend .dot.sponsor { background: #0f766e; }
    .doc-actions { display: flex; align-items: center; gap: .45rem; }
    .doc-btn {
        width: 2.35rem; height: 2.35rem; border-radius: .75rem;
        border: 1px solid var(--line); background: #fff; color: var(--champaca-deep);
        display: inline-grid; place-items: center; text-decoration: none;
        transition: background .15s ease, border-color .15s ease, transform .15s ease;
    }
    .doc-btn svg { width: 1.2rem; height: 1.2rem; }
    .doc-btn:hover { transform: translateY(-1px); border-color: var(--champaca); }
    .doc-btn.student { color: #1d4ed8; background: rgba(37,99,235,.08); border-color: rgba(37,99,235,.22); }
    .doc-btn.student:hover { background: rgba(37,99,235,.16); }
    .doc-btn.sponsor { color: #0f766e; background: rgba(15,118,110,.08); border-color: rgba(15,118,110,.22); }
    .doc-btn.sponsor:hover { background: rgba(15,118,110,.16); }

    .empty-row { text-align: center; padding: 2.2rem !important; color: var(--ink-muted); }

    @media (max-width: 1100px) {
        .filters { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .summary { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }
    @media (max-width: 800px) {
        .filters, .summary { grid-template-columns: 1fr 1fr; }
        table { table-layout: auto; }
        col.col-no, col.col-student, col.col-course, col.col-depart, col.col-payment, col.col-docs { width: auto; }
    }
@endsection

@section('content')
    <div class="page-head">
        <a class="back" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 18l-6-6 6-6"/></svg>
            กลับหน้าหลัก
        </a>
        <div class="title-row">
            <div class="title-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="6" width="18" height="13" rx="2"/>
                    <path d="M3 10h18M8 6V4h8v2M9 14h2M14 14h3"/>
                </svg>
            </div>
            <div>
                <h1>จัดการข้อมูลชำระเงินค่าธรรมเนียมวิจัย</h1>
                <p>บันทึกสถานะการชำระ ดาวน์โหลดหนังสือแจ้ง และพิมพ์รายงานรวมตามภาคการศึกษา</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <form class="panel" method="GET">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/></svg>
            </div>
            <div>
                <h2>ค้นหาและกรองข้อมูล</h2>
                <span class="sub">เลือกปี/ภาค หน่วยงาน สถานะ หรือค้นหาด้วยรหัสชื่อ</span>
            </div>
        </div>
        <div class="filters">
            <div class="field">
                <label><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>ปีการศึกษา</label>
                <select name="year">@foreach($years as $year)<option value="{{ $year }}" @selected($filters['year']===$year)>{{ $year }}</option>@endforeach</select>
            </div>
            <div class="field">
                <label><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l2.5 1.5"/></svg>ภาคการศึกษา</label>
                <select name="term"><option value="1" @selected($filters['term']===1)>1</option><option value="2" @selected($filters['term']===2)>2</option></select>
            </div>
            <div class="field">
                <label><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 20V9l9-5 9 5v11"/><path d="M9 20v-7h6v7"/></svg>หน่วยงาน</label>
                <select name="depart_id"><option value="0">ทั้งหมด</option>@foreach($departments as $department)<option value="{{ $department->depart_id }}" @selected($filters['depart_id']===$department->depart_id)>{{ $department->depart_name }}</option>@endforeach</select>
            </div>
            <div class="field">
                <label><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h16M7 12h10M10 18h4"/></svg>สถานะ</label>
                <select name="status"><option value="">ทุกสถานะ</option><option value="1" @selected($filters['status']==='1')>ค้างชำระ</option><option value="2" @selected($filters['status']==='2')>ได้รับการยกเว้น</option><option value="3" @selected($filters['status']==='3')>ชำระแล้ว</option></select>
            </div>
            <div class="field">
                <label><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/></svg>ค้นหา</label>
                <input name="q" value="{{ $filters['q'] }}" placeholder="รหัส ชื่อ หรือหลักสูตร">
            </div>
        </div>
        <div class="actions">
            <button class="btn btn-primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/></svg>
                แสดงข้อมูล
            </button>
            <a class="btn" href="{{ route('research-fee.payments.report', request()->query()) }}" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 8V4h10v4M7 16H5a2 2 0 0 1-2-2v-4h18v4a2 2 0 0 1-2 2h-2"/><path d="M7 12h10v8H7z"/></svg>
                พิมพ์รายงานรวม
            </a>
        </div>
    </form>

    <div class="summary">
        <div class="stat">
            <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c.7-3 2.8-4.8 5.5-4.8S14 16 14.7 19"/><path d="M16 8h5M18.5 5.5v5"/></svg></div>
            <div><small>ทั้งหมด</small><strong>{{ number_format($summary->total ?? 0) }}</strong></div>
        </div>
        <div class="stat pending">
            <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 7.5v5l3 1.8"/></svg></div>
            <div><small>ค้างชำระ</small><strong>{{ number_format($summary->pending ?? 0) }}</strong></div>
        </div>
        <div class="stat exempt">
            <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/></svg></div>
            <div><small>ได้รับยกเว้น</small><strong>{{ number_format($summary->exempt ?? 0) }}</strong></div>
        </div>
        <div class="stat paid">
            <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M8.5 12.2l2.3 2.3 4.7-4.8"/></svg></div>
            <div><small>ชำระแล้ว</small><strong>{{ number_format($summary->paid ?? 0) }}</strong></div>
        </div>
        <div class="stat paid">
            <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="13" rx="2"/><path d="M3 10h18M8 14h3"/></svg></div>
            <div><small>ยอดรับรวม</small><strong>{{ number_format($summary->received ?? 0, 2) }}</strong></div>
        </div>
    </div>

    <div class="panel table-card">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8v3H8zM6 7h12v13H6z"/><path d="M9 11h6M9 14h6M9 17h4"/></svg>
            </div>
            <div>
                <h2>รายการนักศึกษา</h2>
                <span class="sub">อัปเดตสถานะชำระเงิน และดาวน์โหลดหนังสือแจ้งเป็นรายคน</span>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <colgroup>
                    <col class="col-no">
                    <col class="col-student">
                    <col class="col-course">
                    <col class="col-depart">
                    <col class="col-payment">
                    <col class="col-docs">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>นักศึกษา</th>
                        <th>ระดับ/หลักสูตร</th>
                        <th>หน่วยงาน</th>
                        <th>สถานะการชำระเงิน</th>
                        <th>
                            <div class="docs-head">
                                <span>ดาวน์โหลดเอกสาร</span>
                                <div class="docs-legend" aria-label="คำอธิบายสัญลักษณ์">
                                    <span><i class="dot student" aria-hidden="true"></i> หนังสือถึงนักศึกษา (Word)</span>
                                    <span><i class="dot sponsor" aria-hidden="true"></i> หนังสือถึงต้นสังกัด (Word)</span>
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                @forelse($rows as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="student"><strong>{{ $row->name }}</strong><small>{{ $row->std_code }}</small></td>
                        <td><strong>{{ $row->level }}</strong><br><small>{{ $row->couse ?: '—' }}</small></td>
                        <td>{{ $row->depart_name ?: 'ไม่ระบุ' }}</td>
                        <td>
                            <form class="payment-form" method="POST" action="{{ route('research-fee.payments.update') }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="std_code" value="{{ $row->std_code }}">
                                <input type="hidden" name="term" value="{{ $row->term }}">
                                <input type="hidden" name="year" value="{{ $row->year }}">
                                <select name="status" class="payment-status status-{{ $row->status }}">
                                    <option value="1" @selected($row->status==='1')>ค้างชำระ</option>
                                    <option value="2" @selected($row->status==='2')>ได้รับการยกเว้น</option>
                                    <option value="3" @selected($row->status==='3')>ชำระแล้ว</option>
                                </select>
                                <span class="paid-fields">
                                    <input type="number" name="amount" min="0" step="0.01" value="{{ $row->amount ?: '' }}" placeholder="จำนวนเงิน">
                                    <input name="slip_no" value="{{ $row->slip_no }}" placeholder="เลขที่ใบเสร็จ">
                                </span>
                                <button class="btn btn-primary" type="submit">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/></svg>
                                    บันทึก
                                </button>
                            </form>
                        </td>
                        <td>
                            <div class="doc-actions">
                                <a class="doc-btn student" href="{{ route('research-fee.notice.student', ['std_code'=>$row->std_code,'term'=>$row->term,'year'=>$row->year]) }}" title="ดาวน์โหลดหนังสือถึงนักศึกษา (Word)" aria-label="ดาวน์โหลดหนังสือถึงนักศึกษา Word">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <circle cx="12" cy="8" r="3.1"/>
                                        <path d="M5.8 19c1.1-3.1 3.3-4.7 6.2-4.7s5.1 1.6 6.2 4.7"/>
                                        <path d="M16.2 3.8h4.3v4.3M20.5 3.8l-5.2 5.2"/>
                                    </svg>
                                </a>
                                <a class="doc-btn sponsor" href="{{ route('research-fee.notice.sponsor', ['std_code'=>$row->std_code,'term'=>$row->term,'year'=>$row->year]) }}" title="ดาวน์โหลดหนังสือถึงต้นสังกัด (Word)" aria-label="ดาวน์โหลดหนังสือถึงต้นสังกัด Word">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M4 20V9l8-5 8 5v11"/>
                                        <path d="M9.5 20v-6h5v6"/>
                                        <path d="M16.2 3.8h4.3v4.3M20.5 3.8l-5.2 5.2"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty-row">ไม่พบข้อมูลตามเงื่อนไข</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.payment-status').forEach(select => {
    const form = select.closest('form');
    const fields = form.querySelectorAll('.paid-fields input');
    const sync = () => {
        const paid = select.value === '3';
        fields.forEach(field => { field.disabled = !paid; field.style.display = paid ? '' : 'none'; });
        select.className = `payment-status status-${select.value}`;
    };
    select.addEventListener('change', sync);
    sync();
});
</script>
@endpush
