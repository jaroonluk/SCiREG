@extends('layouts.app')

@section('title', 'กำหนดผู้บริหารลงนามเอกสาร')

@section('styles')
    main.app-main { max-width: min(100%, 920px); width: 100%; }
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
        padding: 1.3rem; margin-bottom: 1rem; overflow: hidden;
        animation: rise .5s ease both;
    }
    .panel::before {
        content: ''; position: absolute; inset: 0 auto 0 0; width: 4px;
        background: linear-gradient(180deg, var(--champaca), var(--champaca-deep));
        border-radius: 1.2rem 0 0 1.2rem;
    }
    .alert {
        margin-bottom: 1rem; padding: .85rem 1rem; border-radius: .9rem; font-size: .95rem;
        display: flex; gap: .55rem; align-items: flex-start;
    }
    .alert svg { width: 1.1rem; height: 1.1rem; flex-shrink: 0; }
    .alert-ok { background: #eefbf1; border: 1px solid #bbdfc4; color: #166534; }
    .alert-err { background: #fff1f0; border: 1px solid #f0c2be; color: #9a3412; }

    .steps { display: grid; gap: 1rem; }
    .step {
        border: 1px solid rgba(201,146,26,.22);
        background: #fffef9;
        border-radius: 1rem; padding: 1rem 1.05rem;
    }
    .step-head { display: flex; align-items: center; gap: .7rem; margin-bottom: .8rem; }
    .step-no {
        width: 2.15rem; height: 2.15rem; border-radius: .7rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: rgba(230,180,34,.16); color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
    }
    .step-no svg { width: 1.1rem; height: 1.1rem; }
    .step-head strong { font-size: 1rem; }
    .step-head small { display: block; color: var(--ink-muted); font-size: .86rem; margin-top: .1rem; }

    .field { display: grid; gap: .35rem; }
    select {
        width: 100%; border: 1px solid rgba(201,146,26,.28); background: #fff;
        border-radius: .8rem; padding: .7rem .85rem; font: inherit; color: var(--ink);
    }
    select:focus {
        outline: none; border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.15);
    }

    .role-box { display: grid; gap: .55rem; }
    .radio-row {
        display: flex; align-items: flex-start; gap: .7rem;
        padding: .8rem .85rem; border-radius: .9rem;
        background: #fff; border: 1px solid var(--line); cursor: pointer;
        transition: border-color .15s ease, background .15s ease;
    }
    .radio-row:hover { border-color: rgba(201,146,26,.4); }
    .radio-row:has(input:checked) {
        border-color: rgba(201,146,26,.5);
        background: rgba(230,180,34,.12);
    }
    .radio-row input { margin-top: .25rem; accent-color: var(--champaca-deep); }
    .radio-row strong { display: block; line-height: 1.35; }

    .preview {
        margin-top: .25rem; padding: 1.15rem;
        border-radius: 1rem; border: 1px dashed rgba(201,146,26,.4);
        background: #fff; text-align: center;
        font-family: 'TH Sarabun New', 'Sarabun', sans-serif;
        font-size: 1.05rem; line-height: 1.4;
    }
    .preview .muted { color: var(--ink-muted); margin-bottom: .85rem; }
    .preview .name { font-weight: 700; margin-top: 1.4rem; color: var(--champaca-deep); }

    .uses { color: var(--ink-muted); font-size: .92rem; line-height: 1.55; }
    .uses-title {
        display: flex; align-items: center; gap: .5rem;
        color: var(--ink); font-weight: 700; margin-bottom: .45rem;
    }
    .uses-title svg { width: 1.05rem; height: 1.05rem; color: var(--champaca-deep); }
    .uses ul { margin: .35rem 0 0 1.2rem; }

    .actions { display: flex; flex-wrap: wrap; gap: .55rem; margin-top: 1.15rem; }
    .btn {
        border: 1px solid var(--line); background: #fffef9; color: var(--ink);
        padding: .7rem 1.15rem; border-radius: 999px; font: 600 .92rem inherit;
        text-decoration: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: .4rem;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn-primary {
        background: linear-gradient(145deg, #f0c54a, var(--champaca-deep));
        border-color: transparent; color: #fff;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.7);
    }
@endsection

@section('content')
    @php
        $active = $settings['active'];
        $selectedUsername = old('username', $settings['username']);
        $selectedRole = old('signing_role', $settings['signing_role']);
    @endphp

    <div class="page-head">
        <a class="back" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M15 6 9 12l6 6"/></svg>
            กลับเมนูหลัก
        </a>
        <div class="title-row">
            <div class="title-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                    <path d="M4 19l3.2-1.1L18 7.1a2.1 2.1 0 0 0-3-3L4.2 14.9 4 19z"/><path d="M13.8 5.2l3 3"/>
                </svg>
            </div>
            <div>
                <h1>กำหนดผู้บริหารลงนามเอกสาร</h1>
                <p>เลือกชื่อผู้บริหารและประเภทการลงนาม สำหรับเอกสารที่ออกจากระบบ</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-ok">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-err">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16.2h.01"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @foreach($errors->all() as $error)
        <div class="alert alert-err">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="12" r="8"/><path d="M12 8v5M12 16.2h.01"/></svg>
            <span>{{ $error }}</span>
        </div>
    @endforeach

    <form class="panel" method="POST" action="{{ route('late-exam.signers.update') }}">
        @csrf
        @method('PUT')

        <div class="steps">
            <div class="step">
                <div class="step-head">
                    <span class="step-no" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="8" r="3.2"/><path d="M5.5 19c1-3.2 3.2-5 6.5-5s5.5 1.8 6.5 5"/></svg>
                    </span>
                    <div>
                        <strong>1. เลือกผู้บริหาร</strong>
                        <small>รายชื่อจากตารางผู้บริหารในระบบ</small>
                    </div>
                </div>
                <div class="field">
                    <select id="username" name="username" required>
                        <option value="">— เลือกชื่อผู้บริหาร —</option>
                        @foreach($executives as $executive)
                            <option value="{{ $executive->username }}" @selected($selectedUsername === $executive->username)>
                                {{ $executive->option_label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="step">
                <div class="step-head">
                    <span class="step-no" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 19l3.2-1.1L18 7.1a2.1 2.1 0 0 0-3-3L4.2 14.9 4 19z"/><path d="M13.8 5.2l3 3"/></svg>
                    </span>
                    <div>
                        <strong>2. เลือกประเภทการลงนาม</strong>
                        <small>ใช้ข้อความนี้ตอนท้ายเอกสาร</small>
                    </div>
                </div>
                <div class="role-box">
                    @foreach($roleLabels as $roleKey => $roleLabel)
                        <label class="radio-row">
                            <input type="radio" name="signing_role" value="{{ $roleKey }}" @checked($selectedRole === $roleKey) required>
                            <span><strong>{{ $roleLabel }}</strong></span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="actions">
            <button class="btn btn-primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M20 6 9 17l-5-5"/></svg>
                บันทึกการกำหนด
            </button>
            <a class="btn" href="{{ route('home') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M6 6l12 12M18 6 6 18"/></svg>
                ยกเลิก
            </a>
        </div>
    </form>

    <div class="panel">
        <div class="uses">
            <div class="uses-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M8 6h8M8 10h8M8 14h5M6 4h12v16H6z"/></svg>
                ใช้กับเอกสารเหล่านี้
            </div>
            <ul>
                <li>พิมพ์แบบฟอร์มการเข้าสอบช้า</li>
                <li>หนังสือถึงนักศึกษา (Word) ในเมนูค่าธรรมเนียมวิจัย</li>
                <li>หนังสือถึงต้นสังกัด (Word) ในเมนูค่าธรรมเนียมวิจัย</li>
            </ul>
        </div>

        <div class="preview" style="margin-top:1rem">
            <div class="muted">ตัวอย่างข้อความลงนามที่จะถูกนำไปใช้</div>
            @if($active)
                <div>ขอแสดงความนับถือ</div>
                <div class="name">{{ $active['signature_name'] }}</div>
                <div>{{ $active['position'] ?: '—' }}</div>
                <div>{{ $active['role_label'] }}</div>
            @else
                <div class="muted">ยังไม่ได้กำหนดผู้บริหารสำหรับลงนาม</div>
            @endif
        </div>
    </div>
@endsection
