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
    .alert-warn {
        background: #fff7ed; border: 1px solid rgba(194,65,12,.22); color: #9a3412;
        display: grid; gap: .45rem;
    }
    .alert-warn strong { font-weight: 700; }
    .dup-list { margin: .2rem 0 0; padding-left: 1.15rem; line-height: 1.55; }
    .dup-list li { margin: .15rem 0; }
    .stat.dup strong { color: #c2410c; }

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

    .term-overview {
        margin: 0 0 1.15rem;
        padding: 1rem;
        border-radius: 1rem;
        border: 1px solid rgba(201,146,26,.22);
        background: linear-gradient(165deg, rgba(255,252,240,.95), rgba(255,246,214,.55));
    }
    .term-overview-head {
        display: flex; align-items: baseline; justify-content: space-between; gap: .75rem; flex-wrap: wrap;
        margin-bottom: .75rem;
    }
    .term-overview-head h3 {
        margin: 0; font: 700 .98rem 'Outfit','Sarabun',sans-serif; color: var(--champaca-deep);
    }
    .term-overview-head .meta { margin: 0; }
    .term-chip-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: .65rem;
    }
    .term-chip {
        display: grid; gap: .35rem; text-decoration: none; color: inherit;
        padding: .75rem .85rem; border-radius: .9rem;
        border: 1px solid rgba(201,146,26,.25); background: #fffef9;
        transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
    }
    .term-chip:hover {
        border-color: var(--champaca); box-shadow: 0 8px 18px -14px rgba(150,100,10,.55);
        transform: translateY(-1px);
    }
    .term-chip .chip-title {
        font: 700 1rem 'Outfit','Sarabun',sans-serif; color: var(--champaca-deep);
    }
    .term-chip .chip-stats {
        display: flex; flex-wrap: wrap; gap: .35rem .65rem; font-size: .78rem; color: var(--ink-muted);
    }
    .term-chip .chip-stats b { font-weight: 700; color: var(--ink); }
    .term-chip .chip-stats .ok { color: #166534; }
    .term-chip .chip-stats .warn { color: #9a3412; }
    .term-chip .chip-stats .dup { color: #c2410c; }
    .term-chip .chip-amount {
        font-variant-numeric: tabular-nums; font-size: .82rem; color: var(--ink-muted);
    }

    .term-block {
        margin-top: 1.15rem; padding-top: 1rem;
        border-top: 1px dashed rgba(201,146,26,.28);
        scroll-margin-top: 1.25rem;
    }
    .term-block:first-of-type { margin-top: .35rem; }
    .term-block-head {
        display: flex; align-items: flex-start; justify-content: space-between; gap: .75rem; flex-wrap: wrap;
        margin-bottom: .7rem;
    }
    .term-block-head h3 {
        margin: 0; font: 700 1.05rem 'Outfit','Sarabun',sans-serif;
        display: inline-flex; align-items: center; gap: .45rem;
    }
    .term-pill {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .2rem .55rem; border-radius: .55rem;
        background: rgba(230,180,34,.16); color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28); font-size: .78rem; font-weight: 700;
    }
    .term-block-meta {
        display: flex; flex-wrap: wrap; gap: .4rem .75rem;
        font-size: .8rem; color: var(--ink-muted);
    }
    .term-block-meta strong { color: var(--ink); font-weight: 700; }
    .subtable-title {
        font: 700 .92rem 'Outfit','Sarabun',sans-serif;
        margin: .85rem 0 .45rem;
    }
    .subtable-title.warn { color: #9a3412; }
    .subtable-title.dup { color: #c2410c; }

    .table-wrap { overflow-x: auto; margin: 0 -.2rem; }
    table { width: 100%; border-collapse: collapse; min-width: 760px; }
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
                <p>อัปโหลดไฟล์ Excel แล้วระบบจะอ่านรหัส/ชื่อ ภาค/ปี จำนวนเงิน และเลขที่ใบเสร็จ แล้วแสดงรายการก่อนให้ยืนยันบันทึก</p>
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
                <span class="sub">ปี/ภาคในฟอร์มใช้เป็นค่าสำรองเมื่ออ่านจากไฟล์ไม่ได้ — ระบบจับคู่จากรหัสนักศึกษา + ภาค/ปี + ชื่อ</span>
            </div>
        </div>

        <form class="upload-form" method="POST" action="{{ route('research-fee.payments.upload.preview') }}" enctype="multipart/form-data" id="upload-form">
            @csrf
            <div class="field">
                <label>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                    ปีการศึกษา (สำรอง)
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
                    ภาคการศึกษา (สำรอง)
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
                รูปแบบไฟล์: คอลัมน์ H = ชื่อและรหัสนักศึกษา, G = จำนวนเงิน, C = เลขที่ใบเสร็จ, D = ภาคการศึกษา/ปี
                ถือว่าซ้ำเมื่ออยู่ในภาค/ปีเดียวกัน และมีรหัสนักศึกษา + ชื่อเดียวกัน — ระบบจะแสดงผลแยกตามภาค/ปีก่อนให้ยืนยันบันทึก
            </div>
        </div>
    </div>

    @if($preview)
        @php
            $termGroups = $preview['term_groups'] ?? [];
            $hasAmbiguous = !empty($preview['ambiguous']);
        @endphp
        <div class="panel">
            <div class="panel-head">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M8 4h8a2 2 0 0 1 2 2v14l-3-1.5L12 20l-3-1.5L6 20V6a2 2 0 0 1 2-2z"/></svg>
                </div>
                <div>
                    <h2>2) ตรวจรายการก่อนบันทึก</h2>
                    <span class="sub">
                        ไฟล์ {{ $preview['original_name'] ?? '—' }}
                        · แบ่งตามภาค/ปีที่อ่านจากไฟล์
                    </span>
                </div>
            </div>

            <div class="summary">
                <div class="stat"><small>ทั้งหมดในไฟล์</small><strong>{{ number_format($preview['total_rows'] ?? 0) }}</strong></div>
                <div class="stat ok"><small>จะอัปเดต</small><strong>{{ number_format(count($preview['matched'] ?? [])) }}</strong></div>
                <div class="stat warn"><small>ไม่พบในฐานข้อมูล</small><strong>{{ number_format(count($preview['unmatched'] ?? [])) }}</strong></div>
                <div class="stat dup"><small>รายการซ้ำ</small><strong>{{ number_format(count($preview['ambiguous'] ?? [])) }}</strong></div>
            </div>

            @if(!empty($termGroups))
                <div class="term-overview">
                    <div class="term-overview-head">
                        <h3>ภาคการศึกษา / ปีการศึกษา ที่จะอัปเดต</h3>
                        <p class="meta">พบ {{ number_format(count($termGroups)) }} ภาค — กดเพื่อไปดูรายละเอียด</p>
                    </div>
                    <div class="term-chip-grid">
                        @foreach($termGroups as $group)
                            <a class="term-chip" href="#{{ $group['anchor'] }}">
                                <div class="chip-title">ภาค{{ $group['label'] }}</div>
                                <div class="chip-stats">
                                    <span class="ok">อัปเดต <b>{{ number_format($group['matched_count']) }}</b></span>
                                    <span class="warn">ไม่พบ <b>{{ number_format($group['unmatched_count']) }}</b></span>
                                    @if(($group['ambiguous_count'] ?? 0) > 0)
                                        <span class="dup">ซ้ำ <b>{{ number_format($group['ambiguous_count']) }}</b></span>
                                    @endif
                                </div>
                                <div class="chip-amount">
                                    ยอดที่จะอัปเดต {{ number_format((float)$group['matched_amount'], 2) }} บาท
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($hasAmbiguous)
                <div class="alert alert-warn" role="alert" style="margin-bottom:1rem">
                    <div style="display:flex;gap:.5rem;align-items:flex-start">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:1.15rem;height:1.15rem;flex-shrink:0;margin-top:.05rem"><circle cx="12" cy="12" r="8"/><path d="M12 8v4M12 16h.01"/></svg>
                        <div>
                            <strong>พบรายการซ้ำ — ระบบยังไม่บันทึกข้อมูลใด ๆ</strong>
                            <div class="meta" style="margin-top:.2rem;color:#9a3412">
                                ถือว่าซ้ำเมื่ออยู่ในภาค/ปีเดียวกัน และมีรหัสนักศึกษา + ชื่อเดียวกัน
                            </div>
                            <ul class="dup-list">
                                @foreach($preview['ambiguous'] as $row)
                                    <li>
                                        <strong>{{ $row['excel_name'] ?: '—' }}</strong>
                                        @if(!empty($row['excel_std_code']))
                                            · {{ $row['excel_std_code'] }}
                                        @endif
                                        · {{ $row['term_year_label'] ?? '—' }}
                                        <span class="meta"> — {{ $row['reason'] ?? 'รายการซ้ำ' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @forelse($termGroups as $group)
                <section class="term-block" id="{{ $group['anchor'] }}">
                    <div class="term-block-head">
                        <h3>
                            <span class="term-pill">ภาค{{ $group['label'] }}</span>
                        </h3>
                        <div class="term-block-meta">
                            <span>อัปเดต <strong>{{ number_format($group['matched_count']) }}</strong></span>
                            <span>ไม่พบ <strong>{{ number_format($group['unmatched_count']) }}</strong></span>
                            @if(($group['ambiguous_count'] ?? 0) > 0)
                                <span>ซ้ำ <strong>{{ number_format($group['ambiguous_count']) }}</strong></span>
                            @endif
                            <span>ยอดอัปเดต <strong>{{ number_format((float)$group['matched_amount'], 2) }}</strong> บาท</span>
                        </div>
                    </div>

                    <div class="subtable-title">
                        @if($hasAmbiguous)
                            รายการที่จับคู่ได้ (ยังไม่บันทึก จนกว่าจะไม่มีรายการซ้ำ)
                        @else
                            รายการที่จะอัปเดต
                        @endif
                    </div>
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
                                @forelse($group['matched'] as $i => $row)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $row['std_code'] }}</td>
                                        <td>
                                            <strong>{{ $row['name'] }}</strong>
                                            <div class="meta">
                                                จากไฟล์: {{ $row['excel_name'] }}
                                                @if(!empty($row['excel_std_code']))
                                                    · {{ $row['excel_std_code'] }}
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $row['depart_name'] ?: '—' }}</td>
                                        <td class="num">{{ number_format((float)$row['amount'], 2) }}</td>
                                        <td>{{ $row['slip_no'] ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="empty">ไม่มีรายการที่จะอัปเดตในภาคนี้</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(!empty($group['unmatched']))
                        <div class="subtable-title warn">ไม่พบในฐานข้อมูล — ภาค{{ $group['label'] }}</div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>รหัสในไฟล์</th>
                                        <th>ชื่อในไฟล์</th>
                                        <th class="num">จำนวนเงิน</th>
                                        <th>เลขที่ใบเสร็จ</th>
                                        <th>หมายเหตุ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group['unmatched'] as $i => $row)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $row['excel_std_code'] ?: '—' }}</td>
                                            <td>{{ $row['excel_name'] }}</td>
                                            <td class="num">{{ number_format((float)$row['amount'], 2) }}</td>
                                            <td>{{ $row['slip_no'] ?: '—' }}</td>
                                            <td class="meta">{{ $row['reason'] ?? 'ไม่พบ' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if(!empty($group['ambiguous']))
                        <div class="subtable-title dup">รายการซ้ำ — ภาค{{ $group['label'] }} (ไม่บันทึก)</div>
                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>รหัสในไฟล์</th>
                                        <th>ชื่อในไฟล์</th>
                                        <th class="num">จำนวนเงิน</th>
                                        <th>เลขที่ใบเสร็จ</th>
                                        <th>สาเหตุ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group['ambiguous'] as $i => $row)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $row['excel_std_code'] ?: '—' }}</td>
                                            <td><strong>{{ $row['excel_name'] }}</strong></td>
                                            <td class="num">{{ number_format((float)$row['amount'], 2) }}</td>
                                            <td>{{ $row['slip_no'] ?: '—' }}</td>
                                            <td class="meta">
                                                {{ $row['reason'] ?? 'รายการซ้ำ' }}
                                                @if(!empty($row['candidates']))
                                                    <br>
                                                    @foreach($row['candidates'] as $c)
                                                        {{ $c['std_code'] }} {{ $c['name'] }}
                                                        ({{ $c['depart_name'] ?: '—' }})@if(!$loop->last)<br>@endif
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>
            @empty
                <p class="empty">ไม่พบรายการจากไฟล์</p>
            @endforelse

            <div class="confirm-bar">
                @if($hasAmbiguous)
                    <p>
                        พบรายการซ้ำในภาค/ปีเดียวกัน (รหัส + ชื่อเดียวกัน) จึง<strong>ยังไม่บันทึกข้อมูล</strong>
                        กรุณาตรวจสอบไฟล์ให้ถูกต้อง แล้วอัปโหลดใหม่
                    </p>
                    <button class="btn btn-primary" type="button" disabled>
                        ยังไม่สามารถบันทึกได้
                    </button>
                @else
                    <p>
                        ตรวจแยกตามภาค/ปีด้านบนแล้วกดบันทึก — อัปเดตเฉพาะรายการที่จับคู่ได้
                        (สถานะเป็น <strong>ชำระแล้ว</strong> พร้อมเลขที่ใบเสร็จและจำนวนเงิน)
                    </p>
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
                @endif
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
