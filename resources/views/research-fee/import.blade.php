@extends('layouts.app')

@section('title', 'นำเข้าข้อมูลนักศึกษาจาก REG')

@section('styles')
    main.app-main { max-width: min(100%, 980px); width: 100%; }

    @keyframes rise {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes softPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(230,180,34,.28); }
        50% { box-shadow: 0 0 0 8px rgba(230,180,34,0); }
    }

    .page-head { margin-bottom: 1.35rem; animation: rise .45s ease both; display: grid; gap: .45rem; }
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
        font: 700 clamp(1.4rem,3vw,1.8rem) 'Outfit','Sarabun',sans-serif; line-height: 1.2;
    }
    .page-head p { color: var(--ink-muted); line-height: 1.5; max-width: 40rem; }

    .panel {
        position: relative;
        background: linear-gradient(180deg, rgba(255,253,247,.98), rgba(255,250,236,.94));
        border: 1px solid var(--line); border-radius: 1.2rem;
        box-shadow: 0 18px 40px -30px rgba(120,80,10,.5);
        padding: 1.3rem; margin-bottom: 1rem; overflow: hidden;
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

    .import-form { display: flex; flex-wrap: wrap; gap: .85rem 1rem; align-items: end; }
    .field { display: grid; gap: .35rem; min-width: 140px; }
    .field label {
        font-size: .86rem; color: var(--ink-muted); font-weight: 600;
        display: inline-flex; align-items: center; gap: .3rem;
    }
    .field label svg { width: .95rem; height: .95rem; color: var(--champaca-deep); }
    .field select {
        border: 1px solid rgba(201,146,26,.28); background: #fffef9; border-radius: .8rem;
        padding: .65rem .85rem; font: inherit; color: var(--ink); min-width: 120px;
    }
    .field select:focus {
        outline: none; border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.15);
    }
    .actions { display: flex; flex-wrap: wrap; gap: .55rem; }
    .btn {
        appearance: none; border: 1px solid rgba(201,146,26,.28); border-radius: .85rem;
        padding: .7rem 1.15rem; font: inherit; font-weight: 600; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
        background: #fffef9; color: var(--ink);
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn-primary {
        background: linear-gradient(145deg, #f0c94a, var(--champaca-deep));
        border-color: transparent; color: #fffdf5;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.65);
    }
    .btn-primary:hover { filter: brightness(1.03); }
    .btn-ghost:hover { border-color: var(--champaca); color: var(--champaca-deep); }
    .btn[disabled] { opacity: .55; cursor: wait; }

    .hint {
        margin-top: 1rem; color: var(--ink-muted); font-size: .9rem; line-height: 1.55;
        padding: .85rem 1rem; border-radius: .9rem;
        background: rgba(230,180,34,.08); border: 1px solid rgba(201,146,26,.2);
        display: flex; gap: .55rem; align-items: flex-start;
    }
    .hint svg { width: 1.1rem; height: 1.1rem; color: var(--champaca-deep); flex-shrink: 0; margin-top: .1rem; }

    .alert-success, .alert-info {
        margin-bottom: 1rem; padding: .85rem 1rem; border-radius: .9rem;
        font-size: .95rem; line-height: 1.5;
        display: flex; gap: .55rem; align-items: flex-start;
        animation: rise .35s ease both;
    }
    .alert-success svg, .alert-info svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .05rem; }
    .alert-success { background: #eefbf1; border: 1px solid rgba(22,101,52,.18); color: #166534; }
    .alert-info { background: #fff9ef; border: 1px solid var(--line); color: var(--ink); }

    .stats {
        display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .75rem;
        margin-bottom: 1rem; animation: rise .5s .05s ease both;
    }
    .stat {
        position: relative; overflow: hidden;
        background: linear-gradient(160deg, #fffef8, #fff6d8);
        border: 1px solid rgba(201,146,26,.28); border-radius: 1rem;
        padding: .95rem 1rem; display: flex; align-items: center; gap: .7rem;
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
    .stat .label { font-size: .8rem; color: var(--ink-muted); font-weight: 600; display: block; }
    .stat .value { font: 700 1.4rem 'Outfit','Sarabun',sans-serif; color: var(--champaca-deep); }

    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    th, td {
        padding: .75rem .85rem; text-align: left;
        border-bottom: 1px solid rgba(201,146,26,.12); vertical-align: top; font-size: .94rem;
    }
    th {
        font-size: .78rem; font-weight: 700; color: var(--champaca-deep);
        background: rgba(230,180,34,.1);
    }
    tbody tr:hover td { background: rgba(230,180,34,.06); }
    tr:last-child td { border-bottom: 0; }
    .empty {
        text-align: center; color: var(--ink-muted); padding: 2rem 1rem;
    }
    .empty svg {
        width: 2.6rem; height: 2.6rem; margin: 0 auto .65rem; color: var(--champaca); display: block;
    }

    @media (max-width: 800px) {
        .stats { grid-template-columns: 1fr 1fr; }
        .col-course { display: none; }
    }
@endsection

@section('content')
    @php
        $display = $result ?? $summary;
    @endphp

    <div class="page-head">
        <a class="back" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M15 18l-6-6 6-6"/></svg>
            กลับหน้าหลัก
        </a>
        <div class="title-row">
            <div class="title-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M12 3v12"/>
                    <path d="M8 11l4 4 4-4"/>
                    <path d="M5 19h14"/>
                </svg>
            </div>
            <div>
                <h1>นำเข้าข้อมูลนักศึกษาจาก REG</h1>
                <p>ดึงนักศึกษาระดับบัณฑิตศึกษาจาก REG มาเพิ่มในระบบค่าธรรมเนียมวิจัยโดยไม่ทับสถานะเดิม</p>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert-success" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
            </div>
            <div>
                <h2>เลือกภาคการศึกษา</h2>
                <span class="sub">ตรวจสอบข้อมูลก่อน แล้วกดนำเข้าเฉพาะรายการใหม่</span>
            </div>
        </div>

        <form class="import-form" method="GET" action="{{ route('research-fee.import') }}" id="preview-form">
            <div class="field">
                <label for="term">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l2.5 1.5"/></svg>
                    ภาคการศึกษา
                </label>
                <select name="term" id="term">
                    <option value="1" @selected($term === 1)>1</option>
                    <option value="2" @selected($term === 2)>2</option>
                </select>
            </div>
            <div class="field">
                <label for="year">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                    ปีการศึกษา
                </label>
                <select name="year" id="year">
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected($year === $y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="preview" value="1">
            <div class="actions">
                <button type="submit" class="btn btn-ghost" id="btn-preview">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/></svg>
                    ตรวจสอบข้อมูล
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('research-fee.import.store') }}" id="import-form" class="actions" style="margin-top:.85rem">
            @csrf
            <input type="hidden" name="term" value="{{ $term }}">
            <input type="hidden" name="year" value="{{ $year }}">
            <button
                type="submit"
                class="btn btn-primary"
                id="btn-import"
                @disabled(! $display || ($display['to_insert'] ?? 0) < 1)
                onclick="return confirm('ยืนยันนำเข้าข้อมูลภาค {{ $term }}/{{ $year }} หรือไม่?')"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v12"/><path d="M8 11l4 4 4-4"/><path d="M5 19h14"/></svg>
                ดึงข้อมูลและนำเข้า
            </button>
        </form>

        <div class="hint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
            <span>
                ระบบจะดึงนักศึกษาระดับบัณฑิตศึกษา (โท–เอก) คณะวิทยาศาสตร์ ที่สถานะกำลังศึกษาในภาค/ปีที่เลือกจากฐานข้อมูล REG
                แล้วเพิ่มเฉพาะรายการใหม่ลงตารางค่าธรรมเนียมวิจัย โดยไม่ทับสถานะการชำระเงินที่มีอยู่แล้ว
            </span>
        </div>
    </div>

    @if ($display)
        <div class="stats">
            <div class="stat">
                <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/></svg></div>
                <div><span class="label">พบใน REG</span><span class="value">{{ number_format($display['reg_total']) }}</span></div>
            </div>
            <div class="stat">
                <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8v3H8zM6 7h12v13H6z"/><path d="M9 12h6"/></svg></div>
                <div><span class="label">มีในระบบแล้ว</span><span class="value">{{ number_format($display['existing']) }}</span></div>
            </div>
            <div class="stat">
                <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 5v14M5 12h14"/></svg></div>
                <div><span class="label">รอเพิ่มใหม่</span><span class="value">{{ number_format($display['to_insert']) }}</span></div>
            </div>
            <div class="stat">
                <div class="stat-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M8.5 12.2l2.3 2.3 4.7-4.8"/></svg></div>
                <div><span class="label">นำเข้าสำเร็จ</span><span class="value">{{ number_format($display['inserted']) }}</span></div>
            </div>
        </div>

        <div class="panel" style="animation-delay:.08s">
            <div class="panel-head">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c.7-3 2.8-4.8 5.5-4.8S14 16 14.7 19"/><path d="M16 8h5M18.5 5.5v5"/></svg>
                </div>
                <div>
                    <h2>ตัวอย่างรายการ</h2>
                    <span class="sub">รายการใหม่ที่จะถูกเพิ่มเข้าสู่ระบบ</span>
                </div>
            </div>

            @if (($display['to_insert'] ?? 0) === 0)
                <div class="empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="8"/><path d="M8.5 12.2l2.3 2.3 4.7-4.8"/></svg>
                    <p>ไม่มีรายการใหม่ที่ต้องนำเข้าสำหรับภาค {{ $display['term'] }}/{{ $display['year'] }}</p>
                </div>
            @else
                <div class="alert-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
                    ตัวอย่างรายการที่จะเพิ่ม (แสดงไม่เกิน 20 รายการจากทั้งหมด {{ number_format($display['to_insert']) }})
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>รหัสนักศึกษา</th>
                                <th>ชื่อ–สกุล</th>
                                <th>ระดับ</th>
                                <th class="col-course">หลักสูตร</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($display['preview'] as $row)
                                <tr>
                                    <td>{{ $row['std_code'] }}</td>
                                    <td>{{ $row['name'] }}</td>
                                    <td>{{ $row['level'] }}</td>
                                    <td class="col-course">{{ $row['couse'] ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
@endsection

@push('scripts')
<script>
(() => {
    const previewForm = document.getElementById('preview-form');
    const importForm = document.getElementById('import-form');
    const btnPreview = document.getElementById('btn-preview');
    const btnImport = document.getElementById('btn-import');
    const termSelect = document.getElementById('term');
    const yearSelect = document.getElementById('year');

    const syncHidden = () => {
        if (!importForm) return;
        importForm.querySelector('input[name="term"]').value = termSelect.value;
        importForm.querySelector('input[name="year"]').value = yearSelect.value;
        if (btnImport) btnImport.disabled = true;
    };

    termSelect?.addEventListener('change', syncHidden);
    yearSelect?.addEventListener('change', syncHidden);

    previewForm?.addEventListener('submit', () => {
        btnPreview.disabled = true;
        btnPreview.innerHTML = 'กำลังตรวจสอบ...';
    });

    importForm?.addEventListener('submit', () => {
        if (btnImport) {
            btnImport.disabled = true;
            btnImport.textContent = 'กำลังนำเข้า...';
        }
    });
})();
</script>
@endpush
