@extends('layouts.app')

@section('title', 'อัปโหลดไฟล์ชำระค่าธรรมเนียม')

@section('styles')
    main.app-main { max-width: min(100%, 1180px); width: 100%; }

    @keyframes rise {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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
    }
    .title-icon svg { width: 1.45rem; height: 1.45rem; }
    .page-head h1 {
        font: 700 clamp(1.4rem,3vw,1.8rem) 'Outfit','Sarabun',sans-serif; line-height: 1.2;
    }
    .page-head p { color: var(--ink-muted); line-height: 1.5; max-width: 44rem; }

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

    .upload-form { display: flex; flex-wrap: wrap; gap: .85rem 1rem; align-items: end; }
    .field { display: grid; gap: .35rem; min-width: 140px; }
    .field.grow { flex: 1 1 220px; }
    .field label {
        font-size: .86rem; color: var(--ink-muted); font-weight: 600;
        display: inline-flex; align-items: center; gap: .3rem;
    }
    .field label svg { width: .95rem; height: .95rem; color: var(--champaca-deep); }
    .field select, .field input[type="file"] {
        border: 1px solid rgba(201,146,26,.28); background: #fffef9; border-radius: .8rem;
        padding: .65rem .85rem; font: inherit; color: var(--ink); min-width: 120px; width: 100%;
    }
    .field select:focus, .field input[type="file"]:focus {
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

    .alert {
        margin-bottom: 1rem; padding: .85rem 1rem; border-radius: .9rem;
        display: flex; gap: .5rem; align-items: flex-start; animation: rise .35s ease both;
    }
    .alert svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .05rem; }
    .alert-ok { background: #eefbf1; border: 1px solid #bbdfc4; color: #166534; }
    .alert-err { background: #fff1e8; border: 1px solid rgba(154,52,18,.2); color: #9a3412; }

    .summary {
        display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: .75rem; margin-bottom: 1rem;
    }
    .stat {
        background: linear-gradient(160deg, #fffef8, #fff6d8);
        border: 1px solid rgba(201,146,26,.28); border-radius: 1rem;
        padding: .85rem 1rem;
    }
    .stat small { display: block; color: var(--ink-muted); font-size: .78rem; font-weight: 600; }
    .stat strong { font: 700 1.25rem 'Outfit','Sarabun',sans-serif; color: var(--champaca-deep); }
    .stat.ok strong { color: #166534; }
    .stat.warn strong { color: #9a3412; }
    .stat.muted strong { color: #475569; }

    .table-wrap { overflow-x: auto; margin: 0 -.2rem; }
    table { width: 100%; border-collapse: collapse; min-width: 860px; }
    th, td {
        padding: .65rem .55rem; border-bottom: 1px solid rgba(201,146,26,.12);
        text-align: left; vertical-align: top; font-size: .88rem;
    }
    th {
        background: rgba(230,180,34,.1); color: var(--champaca-deep);
        font-size: .78rem; font-weight: 700;
    }
    .num { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }
    .meta { color: var(--ink-muted); font-size: .8rem; }
    .empty { text-align: center; color: var(--ink-muted); padding: 1.5rem !important; }
    .section-gap { margin-top: 1.1rem; }
    .confirm-bar {
        margin-top: 1rem; display: flex; flex-wrap: wrap; gap: .65rem;
        align-items: center; justify-content: space-between;
        padding-top: 1rem; border-top: 1px solid rgba(201,146,26,.16);
    }
    .confirm-bar p { color: var(--ink-muted); font-size: .9rem; line-height: 1.45; max-width: 36rem; }

    @media (max-width: 900px) {
        .summary { grid-template-columns: 1fr 1fr; }
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
                    <path d="M12 3v10"/><path d="M8.5 9.5 12 13l3.5-3.5"/><path d="M5 18h14"/>
                </svg>
            </div>
            <div>
                <h1>อัปโหลดไฟล์ชำระค่าธรรมเนียม</h1>
                <p>เลือกปี/ภาคการศึกษา แล้วอัปโหลดไฟล์ Excel เพื่อจับคู่ชื่อนักศึกษาและตรวจยอดก่อนบันทึก</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-ok" role="status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-err" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v4M12 16h.01"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-err" role="alert">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v4M12 16h.01"/></svg>
            {{ $errors->first() }}
        </div>
    @endif

    @if($result)
        <div class="panel">
            <div class="panel-head">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8.5 12.2l2.3 2.3 4.7-4.8"/><circle cx="12" cy="12" r="8"/></svg>
                </div>
                <div>
                    <h2>ผลการบันทึก</h2>
                    <span class="sub">อัปเดตสถานะเป็นชำระแล้ว พร้อมจำนวนเงินและเลขที่ใบเสร็จ</span>
                </div>
            </div>
            <div class="summary">
                <div class="stat ok"><small>บันทึกสำเร็จ</small><strong>{{ number_format($result['updated'] ?? 0) }}</strong></div>
                <div class="stat muted"><small>ข้าม</small><strong>{{ number_format($result['skipped'] ?? 0) }}</strong></div>
                <div class="stat"><small>ตรงกันตอนตรวจ</small><strong>{{ number_format($result['matched_count'] ?? 0) }}</strong></div>
                <div class="stat warn"><small>ไม่พบ/ชื่อซ้ำ</small><strong>{{ number_format(($result['unmatched_count'] ?? 0)+($result['ambiguous_count'] ?? 0)) }}</strong></div>
            </div>
            @if(!empty($result['storage_path']))
                <p class="meta">ไฟล์ต้นทาง ({{ $result['storage_disk'] ?? 'storage' }}): {{ $result['storage_path'] }}</p>
            @endif
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v10"/><path d="M8.5 9.5 12 13l3.5-3.5"/><path d="M5 18h14"/></svg>
            </div>
            <div>
                <h2>1) อัปโหลดและตรวจสอบ</h2>
                <span class="sub">ระบบจะอัปโหลดไฟล์ไป MinIO แล้วจับคู่ชื่อกับฐานค่าธรรมเนียมตามปี/ภาคที่เลือก</span>
            </div>
        </div>

        <form class="upload-form" method="POST" action="{{ route('research-fee.payments.upload.preview') }}" enctype="multipart/form-data" id="upload-form">
            @csrf
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                    ปีการศึกษา
                </label>
                <select name="year" required>
                    @foreach($years as $y)
                        <option value="{{ $y }}" @selected((int)$year === (int)$y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v4l2.5 1.5"/></svg>
                    ภาคการศึกษา
                </label>
                <select name="term" required>
                    <option value="1" @selected((int)$term === 1)>ต้น</option>
                    <option value="2" @selected((int)$term === 2)>ปลาย</option>
                </select>
            </div>
            <div class="field grow">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8v3H8zM6 7h12v13H6z"/></svg>
                    ไฟล์ Excel
                </label>
                <input type="file" name="file" accept=".xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel" required>
            </div>
            <div class="actions">
                <button class="btn btn-primary" type="submit" id="preview-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.2-3.2"/></svg>
                    อ่านและตรวจสอบ
                </button>
            </div>
        </form>

        <div class="hint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 8v4M12 16h.01"/></svg>
            <div>
                รูปแบบไฟล์ตามตัวอย่างค่าธรรมเนียมวิจัย: อ่านชื่อจากคอลัมน์ H, จำนวนเงินจากคอลัมน์ G, เลขที่ใบเสร็จจากคอลัมน์ C
                แล้วจับคู่กับชื่อในระบบของปี/ภาคที่เลือก หากไม่พบจะแจ้งแยกไว้ก่อนบันทึก
            </div>
        </div>
    </div>

    @if($preview)
        <div class="panel">
            <div class="panel-head">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8a2 2 0 0 1 2 2v14l-3-1.5L12 20l-3-1.5L6 20V6a2 2 0 0 1 2-2z"/></svg>
                </div>
                <div>
                    <h2>2) ตรวจรายการก่อนบันทึก</h2>
                    <span class="sub">
                        ไฟล์ {{ $preview['original_name'] ?? '—' }}
                        · ภาค {{ (int)$preview['term'] === 1 ? 'ต้น' : 'ปลาย' }}/{{ $preview['year'] }}
                    </span>
                </div>
            </div>

            <div class="summary">
                <div class="stat"><small>ทั้งหมดในไฟล์</small><strong>{{ number_format($preview['total_rows'] ?? 0) }}</strong></div>
                <div class="stat ok"><small>พบชื่อตรงกัน</small><strong>{{ number_format(count($preview['matched'] ?? [])) }}</strong></div>
                <div class="stat warn"><small>ไม่พบในระบบ</small><strong>{{ number_format(count($preview['unmatched'] ?? [])) }}</strong></div>
                <div class="stat muted"><small>ชื่อซ้ำ</small><strong>{{ number_format(count($preview['ambiguous'] ?? [])) }}</strong></div>
            </div>

            <h3 style="font:700 1rem 'Outfit','Sarabun',sans-serif;margin-bottom:.55rem">รายการที่จะอัปเดต</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อนักศึกษา</th>
                            <th>สาขาวิชา</th>
                            <th class="num">จำนวนเงิน</th>
                            <th>เลขที่ใบเสร็จ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($preview['matched'] ?? []) as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row['std_code'] }}</td>
                                <td>
                                    <strong>{{ $row['name'] }}</strong>
                                    <div class="meta">จากไฟล์: {{ $row['excel_name'] }}</div>
                                </td>
                                <td>{{ $row['depart_name'] ?: '—' }}</td>
                                <td class="num">{{ number_format((float)$row['amount'], 2) }}</td>
                                <td>{{ $row['slip_no'] ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="empty">ไม่มีรายการที่จับคู่ได้</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(!empty($preview['unmatched']))
                <div class="section-gap">
                    <h3 style="font:700 1rem 'Outfit','Sarabun',sans-serif;margin-bottom:.55rem;color:#9a3412">ไม่พบชื่อในระบบ</h3>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ชื่อในไฟล์</th>
                                    <th class="num">จำนวนเงิน</th>
                                    <th>เลขที่ใบเสร็จ</th>
                                    <th>หมายเหตุ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($preview['unmatched'] as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $row['excel_name'] }}</td>
                                        <td class="num">{{ number_format((float)$row['amount'], 2) }}</td>
                                        <td>{{ $row['slip_no'] ?: '—' }}</td>
                                        <td class="meta">{{ $row['reason'] ?? 'ไม่พบ' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if(!empty($preview['ambiguous']))
                <div class="section-gap">
                    <h3 style="font:700 1rem 'Outfit','Sarabun',sans-serif;margin-bottom:.55rem;color:#475569">ชื่อซ้ำ — ไม่บันทึกอัตโนมัติ</h3>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ชื่อในไฟล์</th>
                                    <th class="num">จำนวนเงิน</th>
                                    <th>เลขที่ใบเสร็จ</th>
                                    <th>รายการซ้ำในระบบ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($preview['ambiguous'] as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $row['excel_name'] }}</td>
                                        <td class="num">{{ number_format((float)$row['amount'], 2) }}</td>
                                        <td>{{ $row['slip_no'] ?: '—' }}</td>
                                        <td class="meta">
                                            @foreach(($row['candidates'] ?? []) as $c)
                                                {{ $c['std_code'] }} {{ $c['name'] }} ({{ $c['depart_name'] ?: '—' }})@if(!$loop->last)<br>@endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="confirm-bar">
                <p>กดยืนยันเฉพาะรายการที่พบชื่อตรงกันเท่านั้น ระบบจะตั้งสถานะเป็น <strong>ชำระแล้ว</strong> พร้อมอัปเดตจำนวนเงินและเลขที่ใบเสร็จ</p>
                <form method="POST" action="{{ route('research-fee.payments.upload.confirm') }}" id="confirm-form">
                    @csrf
                    <input type="hidden" name="token" value="{{ $preview['token'] }}">
                    <input type="hidden" name="term" value="{{ $preview['term'] }}">
                    <input type="hidden" name="year" value="{{ $preview['year'] }}">
                    <button class="btn btn-primary" type="submit" @disabled(empty($preview['matched'])) id="confirm-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/></svg>
                        ยืนยันบันทึก {{ number_format(count($preview['matched'] ?? [])) }} รายการ
                    </button>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
document.getElementById('upload-form')?.addEventListener('submit', () => {
    const btn = document.getElementById('preview-btn');
    if (btn) { btn.disabled = true; btn.textContent = 'กำลังอ่านไฟล์...'; }
});
document.getElementById('confirm-form')?.addEventListener('submit', () => {
    const btn = document.getElementById('confirm-btn');
    if (btn) { btn.disabled = true; btn.textContent = 'กำลังบันทึก...'; }
});
</script>
@endpush
