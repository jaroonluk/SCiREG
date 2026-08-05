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

    .field { display: grid; gap: .35rem; margin-bottom: 1rem; }
    .field label { font-weight: 650; color: var(--ink); }
    .field .hint { color: var(--ink-muted); font-size: .86rem; line-height: 1.4; }
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

    .active-box {
        border: 1px solid rgba(201,146,26,.25);
        background: rgba(230,180,34,.08);
        border-radius: .95rem;
        padding: 1rem;
        display: grid;
        gap: .65rem;
    }
    .active-box legend {
        font-weight: 700;
        margin-bottom: .15rem;
    }
    .radio-row {
        display: flex;
        align-items: flex-start;
        gap: .65rem;
        padding: .55rem .65rem;
        border-radius: .7rem;
        background: rgba(255,252,245,.85);
        border: 1px solid transparent;
    }
    .radio-row:has(input:checked) {
        border-color: rgba(201,146,26,.4);
        background: #fff;
    }
    .radio-row input { margin-top: .25rem; }
    .radio-row strong { display: block; }
    .radio-row small { color: var(--ink-muted); }

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
        $actingFor = $settings['acting_for_dean'];
        $actingDean = $settings['acting_dean'];
        $active = $settings['active'];
    @endphp

    <div class="page-head">
        <a class="back" href="{{ route('home') }}">← กลับเมนูหลัก</a>
        <h1>กำหนดผู้บริหารลงนามเอกสาร</h1>
        <p>เลือกผู้บริหารจากรายชื่อในระบบ เพื่อใช้ตอนท้ายเอกสารที่ต้องลงนาม</p>
    </div>

    @if(session('success')) <div class="alert alert-ok">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-err">{{ session('error') }}</div> @endif
    @foreach($errors->all() as $error)
        <div class="alert alert-err">{{ $error }}</div>
    @endforeach

    <form class="panel" method="POST" action="{{ route('late-exam.signers.update') }}">
        @csrf
        @method('PUT')

        <div class="field">
            <label for="acting_for_dean">ปฏิบัติการแทนคณบดีคณะวิทยาศาสตร์</label>
            <div class="hint">เลือกจากรายชื่อผู้บริหารในตาราง tbluser_ex</div>
            <select id="acting_for_dean" name="acting_for_dean">
                <option value="">— ยังไม่กำหนด —</option>
                @foreach($executives as $executive)
                    <option value="{{ $executive->username }}" @selected(old('acting_for_dean', $actingFor?->username) === $executive->username)>
                        {{ $executive->option_label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="field">
            <label for="acting_dean">รักษาการแทนคณบดีคณะวิทยาศาสตร์</label>
            <div class="hint">เลือกจากรายชื่อผู้บริหารในตาราง tbluser_ex</div>
            <select id="acting_dean" name="acting_dean">
                <option value="">— ยังไม่กำหนด —</option>
                @foreach($executives as $executive)
                    <option value="{{ $executive->username }}" @selected(old('acting_dean', $actingDean?->username) === $executive->username)>
                        {{ $executive->option_label }}
                    </option>
                @endforeach
            </select>
        </div>

        <fieldset class="active-box">
            <legend>ใช้ผู้ลงนามแบบใดกับเอกสาร</legend>
            <label class="radio-row">
                <input type="radio" name="active_role" value="acting_for_dean" @checked(old('active_role', $settings['active_role']) === 'acting_for_dean')>
                <span>
                    <strong>ปฏิบัติการแทนคณบดีคณะวิทยาศาสตร์</strong>
                    <small>ใช้ผู้ที่กำหนดในช่องด้านบนสำหรับเอกสารที่ออกจากระบบ</small>
                </span>
            </label>
            <label class="radio-row">
                <input type="radio" name="active_role" value="acting_dean" @checked(old('active_role', $settings['active_role']) === 'acting_dean')>
                <span>
                    <strong>รักษาการแทนคณบดีคณะวิทยาศาสตร์</strong>
                    <small>ใช้ผู้ที่กำหนดในช่องด้านบนสำหรับเอกสารที่ออกจากระบบ</small>
                </span>
            </label>
        </fieldset>

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
