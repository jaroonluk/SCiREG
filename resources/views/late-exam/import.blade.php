@extends('layouts.app')

@section('title', 'นำเข้าข้อมูลสอบช้าจาก REG')

@section('styles')
    main.app-main { max-width: min(100%, 1100px); width: 100%; }
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
        text-decoration: none; color: var(--ink-muted); font-size: .92rem; width: fit-content;
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
    .page-head h1 { font: 700 clamp(1.4rem,3vw,1.8rem) 'Outfit','Sarabun',sans-serif; line-height: 1.2; }
    .page-head p { color: var(--ink-muted); line-height: 1.5; max-width: 42rem; }

    .panel {
        position: relative;
        background: linear-gradient(180deg, rgba(255,253,247,.98), rgba(255,250,236,.94));
        border: 1px solid var(--line); border-radius: 1.2rem;
        box-shadow: 0 18px 40px -30px rgba(120,80,10,.5);
        padding: 1.3rem; margin-bottom: 1.1rem; overflow: hidden;
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
    .panel-head .sub { display: block; margin-top: .12rem; font-size: .82rem; color: var(--ink-muted); }

    .import-form { display: flex; flex-wrap: wrap; gap: .85rem 1rem; align-items: end; }
    .field { display: grid; gap: .35rem; min-width: 140px; }
    .field label {
        font-size: .86rem; color: var(--ink-muted); font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .field label svg { width: .95rem; height: .95rem; color: var(--champaca-deep); }
    .field select {
        border: 1px solid rgba(201,146,26,.28); background: #fffef9; border-radius: .8rem;
        padding: .7rem .9rem; font: inherit; color: var(--ink); min-width: 130px;
    }
    .field select:focus {
        outline: none; border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.18);
    }
    .actions { display: flex; flex-wrap: wrap; gap: .55rem; }
    .btn {
        appearance: none; border: 1px solid var(--line); border-radius: 999px;
        padding: .7rem 1.2rem; font: 600 .92rem inherit; cursor: pointer;
        text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
        background: #fffef9; color: var(--ink);
        transition: transform .12s ease, border-color .12s ease;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn:hover { border-color: var(--champaca); transform: translateY(-1px); }
    .btn-primary {
        background: linear-gradient(145deg, #f0c54a, var(--champaca-deep));
        border-color: transparent; color: #fffdf5;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.7);
    }
    .hint {
        margin-top: 1rem; color: var(--ink-muted); font-size: .9rem; line-height: 1.55;
        display: flex; gap: .45rem; align-items: flex-start;
    }
    .hint svg { width: 1rem; height: 1rem; color: var(--champaca-deep); flex-shrink: 0; margin-top: .15rem; }
    .alert-success, .alert-info {
        margin-bottom: 1rem; padding: .85rem 1rem; border-radius: .9rem;
        font-size: .95rem; display: flex; gap: .55rem; align-items: flex-start;
        animation: rise .35s ease both;
    }
    .alert-success { background: #eefbf1; border: 1px solid #bbdfc4; color: #166534; }
    .alert-info { background: rgba(255,249,239,.95); border: 1px solid rgba(201,146,26,.28); color: var(--ink); }
    .alert-success svg, .alert-info svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .1rem; }

    .stats { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: .75rem; margin-bottom: 1.1rem; }
    .stat {
        background: #fffef9; border: 1px solid var(--line); border-radius: 1rem;
        padding: 1rem; display: flex; gap: .75rem; align-items: center;
    }
    .stat-icon {
        width: 2.4rem; height: 2.4rem; border-radius: .75rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: rgba(230,180,34,.16); color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.25);
    }
    .stat-icon svg { width: 1.15rem; height: 1.15rem; }
    .stat .label { font-size: .82rem; color: var(--ink-muted); font-weight: 600; }
    .stat .value { font: 700 1.45rem 'Outfit','Sarabun',sans-serif; color: var(--champaca-deep); margin-top: .1rem; }

    .table-wrap { overflow-x: auto; border-radius: .85rem; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; }
    th, td { padding: .75rem .85rem; text-align: left; font-size: .92rem; }
    th {
        color: var(--champaca-deep); font-weight: 700; font-size: .78rem;
        text-transform: uppercase; letter-spacing: .03em;
        background: rgba(230,180,34,.12); border-bottom: 1px solid rgba(201,146,26,.25);
    }
    tbody tr { background: #fffef9; }
    tbody tr:hover { background: rgba(230,180,34,.08); }
    td { border-bottom: 1px solid rgba(201,146,26,.14); }
    @media (max-width: 720px) { .stats { grid-template-columns: 1fr; } }
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
                    <path d="M12 3v10"/><path d="M8.5 9.5 12 13l3.5-3.5"/><path d="M5 18h14"/>
                </svg>
            </div>
            <div>
                <h1>นำเข้าข้อมูลสอบช้าจาก REG</h1>
                <p>ดึงนักศึกษาคณะวิทยาศาสตร์ที่กำลังศึกษา เก็บในแคชสำหรับค้นหาตอนบันทึกเข้าสอบช้า</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="panel">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
            </div>
            <div>
                <h2>เลือกปี / ภาค และนำเข้า</h2>
                <span class="sub">ดูตัวอย่างก่อน หรือนำเข้าแทนที่แคชของภาคที่เลือก</span>
            </div>
        </div>

        <form class="import-form" method="GET" action="{{ route('late-exam.import') }}">
            <input type="hidden" name="preview" value="1">
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
            <div class="actions">
                <button class="btn" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
                    ดูตัวอย่างจำนวน
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('late-exam.import.store') }}" style="margin-top:1rem" onsubmit="this.querySelector('button').disabled=true">
            @csrf
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="term" value="{{ $term }}">
            <div class="actions">
                <button class="btn btn-primary" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 3v10"/><path d="M8.5 9.5 12 13l3.5-3.5"/><path d="M5 18h14"/></svg>
                    นำเข้า / แทนที่ข้อมูลภาคนี้
                </button>
            </div>
        </form>
        <p class="hint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16.2h.01"/></svg>
            <span>การนำเข้าจะลบแคชเดิมของปี/ภาคที่เลือก แล้วใส่ข้อมูลใหม่จาก REG (FACULTYID=2, STUDENTSTATUS=10)</span>
        </p>
    </div>

    @php $viewData = $result ?? $summary; @endphp
    @if($viewData)
        <div class="panel">
            <div class="panel-head">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M8 6h8M8 10h8M8 14h5M6 4h12v16H6z"/></svg>
                </div>
                <div>
                    <h2>ผลการนำเข้า / ตัวอย่าง</h2>
                    <span class="sub">สรุปจำนวนและตัวอย่างสูงสุด 20 รายการ</span>
                </div>
            </div>

            <div class="stats">
                <div class="stat">
                    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><ellipse cx="12" cy="6" rx="7" ry="3"/><path d="M5 6v6c0 1.7 3.1 3 7 3s7-1.3 7-3V6M5 12v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/></svg></div>
                    <div>
                        <div class="label">พบใน REG</div>
                        <div class="value">{{ number_format($viewData['reg_total']) }}</div>
                    </div>
                </div>
                <div class="stat">
                    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 7h16v12H4z"/><path d="M8 7V5h8v2"/></svg></div>
                    <div>
                        <div class="label">มีในแคชเดิม</div>
                        <div class="value">{{ number_format($viewData['existing']) }}</div>
                    </div>
                </div>
                <div class="stat">
                    <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M20 6 9 17l-5-5"/></svg></div>
                    <div>
                        <div class="label">นำเข้าแล้ว</div>
                        <div class="value">{{ number_format($viewData['imported'] ?? 0) }}</div>
                    </div>
                </div>
            </div>

            @if(!empty($viewData['preview']))
                <div class="alert-info">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16.2h.01"/></svg>
                    <span>ตัวอย่างสูงสุด 20 รายการ</span>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>รหัส</th>
                                <th>ชื่อ–สกุล</th>
                                <th>สาขา</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($viewData['preview'] as $row)
                                <tr>
                                    <td>{{ $row['STUDENTCODE'] }}</td>
                                    <td>{{ trim(($row['PREFIXABB'] ?? '').($row['STUDENTNAME'] ?? '').' '.($row['STUDENTSURNAME'] ?? '')) }}</td>
                                    <td>{{ $row['PROGRAMNAME'] ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
@endsection
