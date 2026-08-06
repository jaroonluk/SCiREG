@extends('layouts.app')

@section('title', 'กำหนดผู้บริหารลงนามเอกสาร')

@section('styles')
    main.app-main { max-width: min(100%, 920px); width: 100%; }
    .page-head { margin-bottom: 1.2rem; }
    .back { color: var(--ink-muted); text-decoration: none; font-size: .92rem; }
    .back:hover { color: var(--champaca-deep); }
    .page-head h1 {
        font: 700 clamp(1.35rem, 3vw, 1.75rem) 'Outfit', 'Sarabun', sans-serif;
        margin-top: .55rem;
    }
    .page-head p { margin-top: .35rem; color: var(--ink-muted); line-height: 1.5; }

    .panel {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 1.15rem;
        box-shadow: 0 16px 36px -28px rgba(120,80,10,.4);
        padding: 1.25rem;
        margin-bottom: 1rem;
    }
    .alert {
        margin-bottom: 1rem;
        padding: .75rem 1rem;
        border-radius: .8rem;
        font-size: .95rem;
    }
    .alert-ok { background: #eefbf1; border: 1px solid #bbdfc4; color: #166534; }
    .alert-err { background: #fff1f0; border: 1px solid #f0c2be; color: #9a3412; }

    .steps {
        display: grid;
        gap: 1rem;
    }
    .step {
        border: 1px solid rgba(201,146,26,.2);
        background: rgba(255,252,245,.7);
        border-radius: .95rem;
        padding: 1rem;
    }
    .step-head {
        display: flex;
        align-items: center;
        gap: .65rem;
        margin-bottom: .75rem;
    }
    .step-no {
        width: 1.7rem;
        height: 1.7rem;
        border-radius: 999px;
        display: grid;
        place-items: center;
        background: linear-gradient(145deg, var(--champaca), var(--champaca-deep));
        color: #fff;
        font-size: .85rem;
        font-weight: 700;
        flex-shrink: 0;
    }
    .step-head strong { font-size: 1rem; }
    .step-head small { display: block; color: var(--ink-muted); font-size: .86rem; margin-top: .1rem; }

    .field { display: grid; gap: .35rem; }
    select {
        width: 100%;
        border: 1px solid var(--line);
        background: #fff;
        border-radius: .75rem;
        padding: .7rem .85rem;
        font: inherit;
        color: var(--ink);
    }
    select:focus {
        outline: none;
        border-color: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.15);
    }

    .role-box {
        display: grid;
        gap: .55rem;
    }
    .radio-row {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        padding: .7rem .75rem;
        border-radius: .75rem;
        background: #fff;
        border: 1px solid var(--line);
        cursor: pointer;
    }
    .radio-row:has(input:checked) {
        border-color: rgba(201,146,26,.45);
        background: rgba(230,180,34,.1);
    }
    .radio-row input { margin-top: .2rem; }
    .radio-row strong { display: block; line-height: 1.35; }

    .preview {
        margin-top: .25rem;
        padding: 1rem;
        border-radius: .9rem;
        border: 1px dashed rgba(201,146,26,.35);
        background: #fff;
        text-align: center;
        font-family: 'TH Sarabun New', 'Sarabun', sans-serif;
        font-size: 1.05rem;
        line-height: 1.35;
    }
    .preview .muted { color: var(--ink-muted); margin-bottom: .85rem; }
    .preview .name { font-weight: 700; margin-top: 1.4rem; }

    .actions { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1.1rem; }
    .btn {
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink);
        padding: .62rem 1.05rem;
        border-radius: 999px;
        font: 600 .92rem inherit;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-primary {
        background: linear-gradient(145deg, var(--champaca), var(--champaca-deep));
        border-color: transparent;
        color: #fff;
    }
    .uses {
        color: var(--ink-muted);
        font-size: .9rem;
        line-height: 1.5;
    }
    .uses ul { margin: .4rem 0 0 1.1rem; }
@endsection

@section('content')
    @php
        $active = $settings['active'];
        $selectedUsername = old('username', $settings['username']);
        $selectedRole = old('signing_role', $settings['signing_role']);
    @endphp

    <div class="page-head">
        <a class="back" href="{{ route('home') }}">← กลับเมนูหลัก</a>
        <h1>กำหนดผู้บริหารลงนามเอกสาร</h1>
        <p>เลือกชื่อผู้บริหารก่อน แล้วเลือกประเภทการลงนามสำหรับเอกสารที่ออกจากระบบ</p>
    </div>

    @if(session('success')) <div class="alert alert-ok">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-err">{{ session('error') }}</div> @endif
    @foreach($errors->all() as $error)
        <div class="alert alert-err">{{ $error }}</div>
    @endforeach

    <form class="panel" method="POST" action="{{ route('late-exam.signers.update') }}">
        @csrf
        @method('PUT')

        <div class="steps">
            <div class="step">
                <div class="step-head">
                    <span class="step-no">1</span>
                    <div>
                        <strong>เลือกผู้บริหาร</strong>
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
                    <span class="step-no">2</span>
                    <div>
                        <strong>เลือกประเภทการลงนาม</strong>
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
            <button class="btn btn-primary" type="submit">บันทึกการกำหนด</button>
            <a class="btn" href="{{ route('home') }}">ยกเลิก</a>
        </div>
    </form>

    <div class="panel">
        <div class="uses">
            <strong style="color:var(--ink)">ใช้กับเอกสารเหล่านี้</strong>
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
