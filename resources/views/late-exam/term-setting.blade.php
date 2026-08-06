@extends('layouts.app')

@section('title', 'กำหนดภาคการศึกษาปัจจุบัน')

@section('styles')
    main.app-main { max-width: min(100%, 760px); width: 100%; }
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
    .page-head h1 { font: 700 clamp(1.4rem,3vw,1.8rem) 'Outfit','Sarabun',sans-serif; line-height: 1.2; }
    .page-head p { color: var(--ink-muted); line-height: 1.5; }

    .panel {
        position: relative;
        background: linear-gradient(180deg, rgba(255,253,247,.98), rgba(255,250,236,.94));
        border: 1px solid var(--line); border-radius: 1.2rem;
        box-shadow: 0 18px 40px -30px rgba(120,80,10,.5);
        padding: 1.35rem; overflow: hidden; animation: rise .5s ease both;
    }
    .panel::before {
        content: ''; position: absolute; inset: 0 auto 0 0; width: 4px;
        background: linear-gradient(180deg, var(--champaca), var(--champaca-deep));
        border-radius: 1.2rem 0 0 1.2rem;
    }
    .alert {
        margin-bottom: 1rem; padding: .85rem 1rem; border-radius: .9rem; font-size: .95rem;
        display: flex; gap: .55rem; align-items: flex-start; animation: rise .35s ease both;
    }
    .alert svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; margin-top: .1rem; }
    .alert-ok { background: #eefbf1; border: 1px solid #bbdfc4; color: #166534; }
    .alert-err { background: #fff1f0; border: 1px solid #f0c2be; color: #9a3412; }

    .current-box {
        display: flex; gap: .85rem; align-items: flex-start;
        background: rgba(230,180,34,.12);
        border: 1px solid rgba(201,146,26,.3);
        border-radius: 1rem; padding: 1rem 1.1rem; margin-bottom: 1.2rem;
    }
    .current-icon {
        width: 2.5rem; height: 2.5rem; border-radius: .8rem; flex-shrink: 0;
        display: grid; place-items: center;
        background: linear-gradient(145deg, #f0c54a, var(--champaca-deep));
        color: #fffdf5;
    }
    .current-icon svg { width: 1.2rem; height: 1.2rem; }
    .current-box .label { font-size: .82rem; color: var(--ink-muted); font-weight: 600; }
    .current-box .value {
        font: 700 1.3rem 'Outfit','Sarabun',sans-serif;
        color: var(--champaca-deep); margin-top: .2rem;
    }
    .current-box .meta { margin-top: .4rem; font-size: .86rem; color: var(--ink-muted); }

    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }
    .field { display: grid; gap: .35rem; }
    .field label {
        font-size: .86rem; color: var(--ink-muted); font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .field label svg { width: .95rem; height: .95rem; color: var(--champaca-deep); }
    .field select {
        width: 100%; border: 1px solid rgba(201,146,26,.28); background: #fffef9;
        border-radius: .8rem; padding: .7rem .85rem; font: inherit; color: var(--ink);
    }
    .field select:focus {
        outline: none; border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.15);
    }
    .hint {
        margin-top: 1rem; color: var(--ink-muted); font-size: .9rem; line-height: 1.55;
        display: flex; gap: .45rem; align-items: flex-start;
    }
    .hint svg { width: 1rem; height: 1rem; color: var(--champaca-deep); flex-shrink: 0; margin-top: .15rem; }
    .actions { display: flex; gap: .55rem; flex-wrap: wrap; margin-top: 1.2rem; }
    .btn {
        appearance: none; border: 1px solid var(--line); background: #fffef9; color: var(--ink);
        padding: .7rem 1.2rem; border-radius: 999px; font: 600 .92rem inherit;
        cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn-primary {
        background: linear-gradient(145deg, #f0c54a, var(--champaca-deep));
        border-color: transparent; color: #fff;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.7);
    }
    @media (max-width: 720px) { .grid-3 { grid-template-columns: 1fr; } }
@endsection

@section('content')
    <div class="page-head">
        <a class="back" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M15 6 9 12l6 6"/></svg>
            กลับเมนูหลัก
        </a>
        <div class="title-row">
            <div class="title-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                    <rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/>
                </svg>
            </div>
            <div>
                <h1>กำหนดภาคการศึกษาปัจจุบัน</h1>
                <p>ตั้งค่าปี / ภาค / ช่วงสอบ เป็นค่าเริ่มต้นของเมนูรายงานเข้าสอบช้า</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-ok">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-err">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16.2h.01"/></svg>
            <ul style="margin-left:1.1rem">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="panel" method="POST" action="{{ route('late-exam.term-setting.update') }}">
        @csrf
        @method('PUT')

        <div class="current-box">
            <div class="current-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="13" r="7"/><path d="M12 10v3.5l2.2 1.4M9 3.5h6"/></svg>
            </div>
            <div>
                <div class="label">ค่าที่ใช้อยู่ตอนนี้</div>
                <div class="value">
                    {{ (int)$term === 1 ? 'ภาคต้น' : 'ภาคปลาย' }} · ปีการศึกษา {{ $year }}
                    · {{ ($examType ?? 'F') === 'M' ? 'กลางภาค' : 'ปลายภาค' }}
                </div>
                @if($updatedAt)
                    <div class="meta">
                        บันทึกล่าสุด
                        {{ $updatedAt->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                        @if($updatedBy) โดย {{ $updatedBy }} @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="grid-3">
            <div class="field">
                <label for="year">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/></svg>
                    ปีการศึกษา
                </label>
                <select id="year" name="year" required>
                    @foreach($years as $y)
                        <option value="{{ $y }}" @selected((int)old('year', $year) === (int)$y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="term">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
                    ภาคการศึกษา
                </label>
                <select id="term" name="term" required>
                    <option value="1" @selected((int)old('term', $term) === 1)>ภาคต้น</option>
                    <option value="2" @selected((int)old('term', $term) === 2)>ภาคปลาย</option>
                </select>
            </div>
            <div class="field">
                <label for="exam_type">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="13" r="7"/><path d="M12 10v3.5l2.2 1.4M9 3.5h6"/></svg>
                    ช่วงสอบ
                </label>
                <select id="exam_type" name="exam_type" required>
                    <option value="M" @selected(old('exam_type', $examType ?? 'F') === 'M')>กลางภาค</option>
                    <option value="F" @selected(old('exam_type', $examType ?? 'F') === 'F')>ปลายภาค</option>
                </select>
            </div>
        </div>

        <p class="hint">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16.2h.01"/></svg>
            <span>ใช้เป็นค่าเริ่มต้นในหน้านำเข้า / บันทึก / พิมพ์ / รายงานสรุป โดยยังเปลี่ยนในแต่ละหน้าได้</span>
        </p>

        <div class="actions">
            <a class="btn" href="{{ route('home') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M6 6l12 12M18 6 6 18"/></svg>
                ยกเลิก
            </a>
            <button class="btn btn-primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M20 6 9 17l-5-5"/></svg>
                บันทึกภาคการศึกษาปัจจุบัน
            </button>
        </div>
    </form>
@endsection
