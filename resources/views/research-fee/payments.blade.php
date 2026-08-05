@extends('layouts.app')

@section('title', 'จัดการข้อมูลชำระเงินค่าธรรมเนียมวิจัย')

@section('styles')
    main.app-main {
        max-width: min(100%, 1480px);
        width: 100%;
        padding: 1.5rem 1.25rem 2.5rem;
    }
    .page-head { margin-bottom: 1.2rem; }
    .back { color: var(--ink-muted); text-decoration: none; font-size: .92rem; }
    .page-head h1 { font: 700 clamp(1.35rem,3vw,1.75rem) 'Outfit','Sarabun',sans-serif; margin-top: .65rem; }
    .filter-card, .table-card { background: var(--surface); border: 1px solid var(--line); border-radius: 1.1rem; box-shadow: 0 16px 36px -28px rgba(120,80,10,.4); }
    .filter-card { padding: 1.15rem; margin-bottom: 1rem; }
    .filters { display: grid; grid-template-columns: repeat(5,minmax(0,1fr)); gap: .75rem; align-items: end; }
    .field { display: grid; gap: .3rem; min-width: 0; }
    .field label { color: var(--ink-muted); font-size: .82rem; font-weight: 600; }
    select, input { width: 100%; max-width: 100%; border: 1px solid var(--line); background: #fff; border-radius: .7rem; padding: .62rem .75rem; font: inherit; color: var(--ink); }
    select:focus, input:focus { outline: none; border-color: var(--champaca); box-shadow: 0 0 0 3px rgba(230,180,34,.15); }
    .actions { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .85rem; }
    .btn { border: 1px solid var(--line); background: #fff; color: var(--ink); padding: .58rem .95rem; border-radius: 999px; font: 600 .9rem inherit; text-decoration: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; }
    .btn-primary { background: linear-gradient(145deg,var(--champaca),var(--champaca-deep)); border-color: transparent; color: #fff; }
    .btn:hover { border-color: var(--champaca); }
    .summary { display: grid; grid-template-columns: repeat(5,minmax(0,1fr)); gap: .65rem; margin-bottom: 1rem; }
    .stat { background: rgba(255,252,245,.92); border: 1px solid var(--line); border-radius: .85rem; padding: .8rem .9rem; min-width: 0; }
    .stat small { display: block; color: var(--ink-muted); }
    .stat strong { font: 700 1.3rem 'Outfit','Sarabun'; color: var(--champaca-deep); }
    .alert { margin-bottom: 1rem; padding: .75rem 1rem; background: #eefbf1; border: 1px solid #bbdfc4; color: #166534; border-radius: .8rem; }
    .table-card { overflow: hidden; }
    table { border-collapse: collapse; width: 100%; table-layout: fixed; }
    col.col-no { width: 3.2rem; }
    col.col-student { width: 16%; }
    col.col-course { width: 18%; }
    col.col-depart { width: 14%; }
    col.col-payment { width: auto; }
    col.col-docs { width: 9.5rem; }
    th, td { padding: .72rem .55rem; border-bottom: 1px solid rgba(201,146,26,.12); text-align: left; vertical-align: top; font-size: .88rem; word-break: break-word; }
    th { background: rgba(230,180,34,.1); color: var(--ink-muted); font-size: .78rem; }
    .student strong { display: block; }
    .student small { color: var(--ink-muted); }
    .payment-form { display: flex; flex-wrap: wrap; gap: .4rem; align-items: center; min-width: 0; }
    .payment-form select { flex: 1 1 9.5rem; max-width: 11rem; }
    .payment-form .paid-fields { display: contents; }
    .payment-form input[type="number"],
    .payment-form input[name="slip_no"] { flex: 1 1 6.5rem; max-width: 8.5rem; }
    .payment-form .btn { flex: 0 0 auto; }
    .status-1 { color: #9a3412; } .status-2 { color: #475569; } .status-3 { color: #166534; }
    .docs-head {
        display: flex;
        flex-direction: column;
        gap: .35rem;
        align-items: flex-start;
    }
    .docs-legend {
        display: flex;
        flex-direction: column;
        gap: .2rem;
        font-size: .72rem;
        font-weight: 500;
        color: var(--ink-muted);
        line-height: 1.25;
        text-transform: none;
    }
    .docs-legend span {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
    }
    .docs-legend .dot {
        width: .45rem;
        height: .45rem;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .docs-legend .dot.student { background: #2563eb; }
    .docs-legend .dot.sponsor { background: #0f766e; }
    .doc-actions {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: .45rem;
    }
    .doc-btn {
        width: 2.35rem;
        height: 2.35rem;
        border-radius: .75rem;
        border: 1px solid var(--line);
        background: #fff;
        color: var(--champaca-deep);
        display: inline-grid;
        place-items: center;
        text-decoration: none;
        transition: background .15s ease, border-color .15s ease, transform .15s ease, color .15s ease;
    }
    .doc-btn svg { width: 1.2rem; height: 1.2rem; }
    .doc-btn:hover {
        transform: translateY(-1px);
        border-color: var(--champaca);
    }
    .doc-btn.student {
        color: #1d4ed8;
        background: rgba(37, 99, 235, 0.08);
        border-color: rgba(37, 99, 235, 0.22);
    }
    .doc-btn.student:hover { background: rgba(37, 99, 235, 0.16); }
    .doc-btn.sponsor {
        color: #0f766e;
        background: rgba(15, 118, 110, 0.08);
        border-color: rgba(15, 118, 110, 0.22);
    }
    .doc-btn.sponsor:hover { background: rgba(15, 118, 110, 0.16); }
    .pager { padding: .85rem 1rem; display: flex; justify-content: space-between; align-items: center; gap: .75rem; flex-wrap: wrap; color: var(--ink-muted); font-size: .88rem; }
    .pager-links { display: flex; flex-wrap: wrap; gap: .35rem; }
    .pager-links a, .pager-links span { min-width: 2rem; height: 2rem; display: grid; place-items: center; padding: 0 .4rem; border: 1px solid var(--line); border-radius: .5rem; text-decoration: none; background: #fff; }
    .pager-links .current { background: rgba(230,180,34,.2); color: var(--champaca-deep); }
    @media (max-width: 1100px) {
        .filters { grid-template-columns: repeat(3,minmax(0,1fr)); }
        col.col-docs { width: 9.5rem; }
    }
    @media (max-width: 800px) {
        .filters, .summary { grid-template-columns: repeat(2,minmax(0,1fr)); }
        table { table-layout: auto; }
        col.col-no, col.col-student, col.col-course, col.col-depart, col.col-payment, col.col-docs { width: auto; }
    }
@endsection

@section('content')
    <div class="page-head">
        <a class="back" href="{{ route('home') }}">← กลับเมนูหลัก</a>
        <h1>จัดการข้อมูลชำระเงินค่าธรรมเนียมวิจัย</h1>
    </div>

    @if(session('success')) <div class="alert">{{ session('success') }}</div> @endif

    <form class="filter-card" method="GET">
        <div class="filters">
            <div class="field"><label>ปีการศึกษา</label><select name="year">@foreach($years as $year)<option value="{{ $year }}" @selected($filters['year']===$year)>{{ $year }}</option>@endforeach</select></div>
            <div class="field"><label>ภาคการศึกษา</label><select name="term"><option value="1" @selected($filters['term']===1)>1</option><option value="2" @selected($filters['term']===2)>2</option></select></div>
            <div class="field"><label>หน่วยงาน</label><select name="depart_id"><option value="0">ทั้งหมด</option>@foreach($departments as $department)<option value="{{ $department->depart_id }}" @selected($filters['depart_id']===$department->depart_id)>{{ $department->depart_name }}</option>@endforeach</select></div>
            <div class="field"><label>สถานะ</label><select name="status"><option value="">ทุกสถานะ</option><option value="1" @selected($filters['status']==='1')>ค้างชำระ</option><option value="2" @selected($filters['status']==='2')>ได้รับการยกเว้น</option><option value="3" @selected($filters['status']==='3')>ชำระแล้ว</option></select></div>
            <div class="field"><label>ค้นหา</label><input name="q" value="{{ $filters['q'] }}" placeholder="รหัส ชื่อ หรือหลักสูตร"></div>
        </div>
        <div class="actions">
            <button class="btn btn-primary">แสดงข้อมูล</button>
            <a class="btn" href="{{ route('research-fee.payments.report', request()->query()) }}" target="_blank">พิมพ์รายงานรวม</a>
        </div>
    </form>

    <div class="summary">
        <div class="stat"><small>ทั้งหมด</small><strong>{{ number_format($summary->total ?? 0) }}</strong></div>
        <div class="stat"><small>ค้างชำระ</small><strong>{{ number_format($summary->pending ?? 0) }}</strong></div>
        <div class="stat"><small>ได้รับยกเว้น</small><strong>{{ number_format($summary->exempt ?? 0) }}</strong></div>
        <div class="stat"><small>ชำระแล้ว</small><strong>{{ number_format($summary->paid ?? 0) }}</strong></div>
        <div class="stat"><small>ยอดรับรวม</small><strong>{{ number_format($summary->received ?? 0, 2) }}</strong></div>
    </div>

    <div class="table-card">
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
                    <td>{{ $rows->firstItem()+$index }}</td>
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
                            <button class="btn btn-primary">บันทึก</button>
                        </form>
                    </td>
                    <td>
                        <div class="doc-actions">
                            <a
                                class="doc-btn student"
                                href="{{ route('research-fee.notice.student', ['std_code'=>$row->std_code,'term'=>$row->term,'year'=>$row->year]) }}"
                                title="ดาวน์โหลดหนังสือถึงนักศึกษา (Word)"
                                aria-label="ดาวน์โหลดหนังสือถึงนักศึกษา Word"
                            >
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <circle cx="12" cy="8" r="3.1"/>
                                    <path d="M5.8 19c1.1-3.1 3.3-4.7 6.2-4.7s5.1 1.6 6.2 4.7"/>
                                    <path d="M16.2 3.8h4.3v4.3M20.5 3.8l-5.2 5.2"/>
                                </svg>
                            </a>
                            <a
                                class="doc-btn sponsor"
                                href="{{ route('research-fee.notice.sponsor', ['std_code'=>$row->std_code,'term'=>$row->term,'year'=>$row->year]) }}"
                                title="ดาวน์โหลดหนังสือถึงต้นสังกัด (Word)"
                                aria-label="ดาวน์โหลดหนังสือถึงต้นสังกัด Word"
                            >
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
                <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--ink-muted)">ไม่พบข้อมูลตามเงื่อนไข</td></tr>
            @endforelse
            </tbody>
        </table>
        @if($rows->hasPages())
            <div class="pager">
                <span>แสดง {{ $rows->firstItem() }}–{{ $rows->lastItem() }} จาก {{ number_format($rows->total()) }}</span>
                <div class="pager-links">
                    @if($rows->previousPageUrl())<a href="{{ $rows->previousPageUrl() }}">‹</a>@endif
                    @foreach($rows->getUrlRange(max(1,$rows->currentPage()-2),min($rows->lastPage(),$rows->currentPage()+2)) as $page=>$url)
                        @if($page===$rows->currentPage())<span class="current">{{ $page }}</span>@else<a href="{{ $url }}">{{ $page }}</a>@endif
                    @endforeach
                    @if($rows->nextPageUrl())<a href="{{ $rows->nextPageUrl() }}">›</a>@endif
                </div>
            </div>
        @endif
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
