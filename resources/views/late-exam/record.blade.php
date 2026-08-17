@extends('layouts.app')

@section('title', 'บันทึกการเข้าสอบช้า')

@section('styles')
    main.app-main { max-width: min(100%, 1120px); width: 100%; }

    @keyframes rise {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes softPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(230,180,34,.28); }
        50% { box-shadow: 0 0 0 8px rgba(230,180,34,0); }
    }

    .page-head {
        margin-bottom: 1.35rem;
        animation: rise .45s ease both;
        display: grid;
        gap: .45rem;
    }
    .back {
        display: inline-flex; align-items: center; gap: .35rem;
        color: var(--ink-muted); text-decoration: none; font-size: .92rem; width: fit-content;
    }
    .back:hover { color: var(--champaca-deep); }
    .back svg { width: 1rem; height: 1rem; }
    .title-row {
        display: flex; align-items: center; gap: .85rem; flex-wrap: wrap;
    }
    .title-icon {
        width: 3rem; height: 3rem; border-radius: 1rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: linear-gradient(145deg, #f3d06a, var(--champaca-deep));
        color: #fffdf5;
        box-shadow: 0 12px 24px -14px rgba(150,100,10,.55);
        animation: softPulse 2.8s ease-in-out infinite;
    }
    .title-icon svg { width: 1.45rem; height: 1.45rem; }
    .page-head h1 {
        font: 700 clamp(1.4rem,3vw,1.85rem) 'Outfit','Sarabun',sans-serif;
        line-height: 1.2;
    }
    .page-head p { color: var(--ink-muted); line-height: 1.5; max-width: 42rem; }

    .panel {
        position: relative;
        background:
            linear-gradient(180deg, rgba(255,253,247,.98), rgba(255,250,236,.94));
        border: 1px solid var(--line);
        border-radius: 1.2rem;
        box-shadow: 0 18px 40px -30px rgba(120,80,10,.5);
        padding: 1.25rem 1.3rem 1.35rem;
        margin-bottom: 1rem;
        overflow: hidden;
        animation: rise .5s ease both;
    }
    .panel::before {
        content: '';
        position: absolute; inset: 0 auto 0 0; width: 4px;
        background: linear-gradient(180deg, var(--champaca), var(--champaca-deep));
        border-radius: 1.2rem 0 0 1.2rem;
    }
    .panel-search { animation-delay: .04s; }
    .panel-student { animation-delay: .08s; }
    .panel-course { animation-delay: .12s; }
    .panel-reason { animation-delay: .16s; }

    .panel-head {
        display: flex; align-items: center; gap: .7rem;
        margin-bottom: 1rem;
    }
    .step-badge {
        width: 2.15rem; height: 2.15rem; border-radius: .7rem;
        display: grid; place-items: center; flex-shrink: 0;
        background: rgba(230,180,34,.16);
        color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.28);
    }
    .step-badge svg { width: 1.1rem; height: 1.1rem; }
    .panel-head h2 {
        font: 700 1.05rem 'Outfit','Sarabun',sans-serif;
        letter-spacing: .01em;
    }
    .panel-head .sub {
        display: block; margin-top: .12rem;
        font-size: .82rem; font-weight: 500; color: var(--ink-muted);
    }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

    .field { display: grid; gap: .35rem; margin-bottom: .9rem; }
    .field label {
        font-size: .86rem; color: var(--ink-muted); font-weight: 600;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .field label svg { width: .95rem; height: .95rem; color: var(--champaca-deep); opacity: .85; }
    .field input, .field select, .field textarea {
        border: 1px solid rgba(201,146,26,.28);
        background: #fffef9;
        border-radius: .8rem;
        padding: .7rem .9rem;
        font: inherit; color: var(--ink); width: 100%;
        transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
    }
    .field input:hover, .field select:hover {
        border-color: rgba(201,146,26,.45);
    }
    .field input:focus, .field select:focus, .field textarea:focus {
        outline: none;
        border-color: var(--champaca);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(230,180,34,.18);
    }

    .suggest-wrap { position: relative; }
    .suggest-list {
        position: absolute; left: 0; right: 0; top: calc(100% + .3rem); z-index: 30;
        background: #fffef9;
        border: 1px solid rgba(201,146,26,.35);
        border-radius: .95rem;
        box-shadow: 0 20px 44px -22px rgba(90,60,10,.45);
        max-height: 280px; overflow: auto; display: none;
    }
    .suggest-list.is-open { display: block; animation: rise .2s ease both; }
    .suggest-item {
        width: 100%; text-align: left; border: 0; background: transparent;
        padding: .75rem .95rem; cursor: pointer; font: inherit; color: var(--ink);
        border-bottom: 1px solid rgba(201,146,26,.1);
    }
    .suggest-item:last-child { border-bottom: 0; }
    .suggest-item:hover, .suggest-item.is-active {
        background: linear-gradient(90deg, rgba(230,180,34,.16), rgba(230,180,34,.05));
    }
    .suggest-item .code { font-weight: 700; color: var(--champaca-deep); }
    .suggest-item .meta { display: block; font-size: .86rem; color: var(--ink-muted); margin-top: .12rem; }

    .hint {
        color: var(--ink-muted); font-size: .86rem; margin-top: .45rem; line-height: 1.45;
        display: flex; align-items: flex-start; gap: .35rem;
    }
    .hint::before {
        content: '';
        width: .45rem; height: .45rem; margin-top: .4rem; flex-shrink: 0;
        border-radius: 50%;
        background: var(--champaca);
        box-shadow: 0 0 0 3px rgba(230,180,34,.2);
    }

    .btn {
        border: 1px solid var(--line); background: #fffef9; color: var(--ink);
        padding: .7rem 1.2rem; border-radius: 999px; font: 600 .92rem inherit;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: .4rem;
        transition: transform .12s ease, border-color .12s ease, box-shadow .12s ease;
    }
    .btn svg { width: 1rem; height: 1rem; }
    .btn:hover { border-color: var(--champaca); transform: translateY(-1px); }
    .btn-primary {
        background: linear-gradient(145deg, #f0c54a, var(--champaca-deep));
        border-color: transparent; color: #fffdf5;
        box-shadow: 0 10px 22px -14px rgba(150,100,10,.7);
    }
    .btn-primary:hover { filter: brightness(1.03); }

    .alert {
        margin-bottom: 1rem; padding: .85rem 1rem; border-radius: .9rem; font-size: .95rem;
        animation: rise .35s ease both;
    }
    .alert-ok { background: #eefbf1; border: 1px solid #bbdfc4; color: #166534; }
    .alert-err { background: #fff1f0; border: 1px solid #f0c2be; color: #9a3412; }

    .reason-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .65rem;
    }
    .reason-row {
        display: flex; align-items: flex-start; gap: .7rem;
        padding: .8rem .85rem;
        border-radius: .95rem;
        background: #fffef9;
        border: 1px solid rgba(201,146,26,.22);
        cursor: pointer;
        transition: border-color .15s ease, background .15s ease, transform .12s ease;
    }
    .reason-row:hover {
        border-color: rgba(201,146,26,.45);
        transform: translateY(-1px);
    }
    .reason-row:has(input:checked) {
        border-color: rgba(201,146,26,.55);
        background: linear-gradient(145deg, rgba(230,180,34,.18), rgba(255,252,245,.9));
        box-shadow: 0 10px 22px -18px rgba(150,100,10,.55);
    }
    .reason-row input { margin-top: .35rem; accent-color: var(--champaca-deep); }
    .reason-icon {
        width: 2.15rem; height: 2.15rem; border-radius: .7rem; flex-shrink: 0;
        display: grid; place-items: center;
        background: rgba(230,180,34,.14);
        color: var(--champaca-deep);
        border: 1px solid rgba(201,146,26,.2);
    }
    .reason-icon svg { width: 1.1rem; height: 1.1rem; }
    .reason-row:has(input:checked) .reason-icon {
        background: linear-gradient(145deg, #f0c54a, var(--champaca-deep));
        color: #fffdf5;
        border-color: transparent;
    }
    .reason-row strong { display: block; line-height: 1.35; font-size: .95rem; }
    .reason-row small { display: block; color: var(--ink-muted); margin-top: .2rem; line-height: 1.4; }

    .actions {
        display: flex; gap: .6rem; flex-wrap: wrap; margin-top: 1.1rem;
        padding-top: 1rem;
        border-top: 1px dashed rgba(201,146,26,.28);
    }

    @media (max-width: 900px) {
        .grid-2, .reason-list { grid-template-columns: 1fr; }
    }
@endsection

@section('content')
    @php
        $reasonIcons = [
            9 => '<path d="M8 6h8l1 4H7l1-4Z"/><path d="M9 10v8M15 10v8M7 18h10"/>',
            1 => '<circle cx="7.5" cy="16.5" r="1.7"/><circle cx="16.5" cy="16.5" r="1.7"/><path d="M4 16.5h2M9.2 16.5h5.6M18.2 16.5H20M5 16.5l1.5-6h9l2.2 6M8 10.5V8h6"/>',
            7 => '<path d="M4 15h16"/><path d="M6 15V9.5L8.5 7h7L18 9.5V15"/><circle cx="8" cy="15.5" r="1.5"/><circle cx="16" cy="15.5" r="1.5"/>',
            10 => '<circle cx="12" cy="13" r="6.5"/><path d="M12 10v3.2l2 1.3M9.5 4h5"/>',
            2 => '<path d="M12 20s-6.5-4.1-6.5-9A3.8 3.8 0 0 1 12 8a3.8 3.8 0 0 1 6.5 3c0 4.9-6.5 9-6.5 9Z"/>',
            6 => '<rect x="5" y="7" width="14" height="11" rx="1.5"/><path d="M9 7V5.5A3 3 0 0 1 15 5.5V7M9 12h6"/>',
            3 => '<circle cx="12" cy="12" r="7"/><path d="M12 8v4.2l2.8 1.7"/>',
            4 => '<circle cx="12" cy="12" r="7"/><path d="M12 8v5M12 16.2h.01"/>',
        ];
        $defaultReasonIcon = '<circle cx="12" cy="12" r="7"/><path d="M8.5 12h7"/>';
    @endphp

    <div class="page-head">
        <a class="back" href="{{ route('home') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M15 6 9 12l6 6"/></svg>
            กลับเมนูหลัก
        </a>
        <div class="title-row">
            <div class="title-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="13" r="7"/><path d="M12 10v3.5l2.2 1.4M9 3.5h6"/>
                </svg>
            </div>
            <div>
                <h1>บันทึกการเข้าสอบช้า</h1>
                <p>ค้นหานักศึกษาและรายวิชา แล้วเลือกสาเหตุ เพื่อบันทึกและพิมพ์แบบฟอร์มทันที</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-err">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-err">
            <ul style="margin-left:1.1rem">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel panel-search">
        <div class="panel-head">
            <div class="step-badge" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
            </div>
            <div>
                <h2>1. ค้นหานักศึกษา</h2>
                <span class="sub">พิมพ์รหัสบางส่วน แล้วเลือกจากรายการ</span>
            </div>
        </div>
        <div class="field suggest-wrap">
            <label for="lookup_q">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><rect x="5" y="4" width="14" height="16" rx="2"/><path d="M8 8h8M8 12h6M8 16h4"/></svg>
                รหัสนักศึกษา
            </label>
            <input type="text" id="lookup_q" placeholder="พิมพ์รหัสบางส่วนหรือทั้งหมด เช่น 633020" autocomplete="off">
            <div class="suggest-list" id="suggestList" role="listbox" hidden></div>
        </div>
        <p class="hint" id="lookupHint">พิมพ์อย่างน้อย 2 ตัวอักษร ระบบจะแสดงรายการให้เลือกอัตโนมัติ หากไม่พบสามารถกรอกข้อมูลในส่วนที่ 2 ได้เอง</p>
    </div>

    <form method="POST" action="{{ route('late-exam.record.store') }}" id="recordForm">
        @csrf
        <input type="hidden" name="STUDENTID" id="STUDENTID" value="{{ old('STUDENTID', 0) }}">
        <input type="hidden" name="PROGRAM_NAME" id="PROGRAM_NAME" value="">

        <div class="grid-2">
            <div class="panel panel-student">
                <div class="panel-head">
                    <div class="step-badge" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="12" cy="8" r="3.2"/><path d="M5.5 19c1-3.2 3.2-5 6.5-5s5.5 1.8 6.5 5"/></svg>
                    </div>
                    <div>
                        <h2>2. ข้อมูลนักศึกษา</h2>
                        <span class="sub">เลือกจากค้นหา หรือพิมพ์เองได้ทั้งหมด</span>
                    </div>
                </div>
                <div class="field">
                    <label for="STUDENTCODE">รหัสนักศึกษา</label>
                    <input type="text" name="STUDENTCODE" id="STUDENTCODE" value="{{ old('STUDENTCODE') }}" required maxlength="20" placeholder="กรอกหรือเลือกจากรายการค้นหา">
                </div>
                <div class="field">
                    <label for="STUDENT_NAME">ชื่อ–สกุล</label>
                    <input type="text" name="STUDENT_NAME" id="STUDENT_NAME" value="{{ old('STUDENT_NAME') }}" required maxlength="255" placeholder="คำนำหน้า + ชื่อ + นามสกุล">
                </div>
                <div class="field suggest-wrap" id="deptSuggestWrap">
                    <label for="DEPARTMENT_NAME">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 20V6l8-3 8 3v14"/><path d="M9 20v-6h6v6"/></svg>
                        สาขาวิชา
                    </label>
                    <input type="text" name="DEPARTMENT_NAME" id="DEPARTMENT_NAME" value="{{ old('DEPARTMENT_NAME') }}" maxlength="255" placeholder="พิมพ์เช่น คณิตศาสตร์ หรือ สาขาวิชา..." autocomplete="off">
                    <div class="suggest-list" id="deptSuggestList" role="listbox" hidden></div>
                    <p class="hint" id="deptHint">ค้นจาก eoffice (เฉพาะชื่อขึ้นต้นด้วย สาขาวิชา) หากไม่พบสามารถพิมพ์เองได้</p>
                </div>
            </div>

            <div class="panel panel-course">
                <div class="panel-head">
                    <div class="step-badge" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M5 5h10v14H5z"/><path d="M9 9h2.5M9 12h4M9 15h3.5M15 8h4v11h-4"/></svg>
                    </div>
                    <div>
                        <h2>3. รายวิชาและช่วงสอบ</h2>
                        <span class="sub">ค้นหารหัสวิชาแล้วระบบเติมชื่อให้อัตโนมัติ</span>
                    </div>
                </div>
                <div class="field suggest-wrap" id="courseSuggestWrap">
                    <label for="COURSE_CODE">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/></svg>
                        รหัสวิชา
                    </label>
                    <input type="text" name="COURSE_CODE" id="COURSE_CODE" value="{{ old('COURSE_CODE') }}" required maxlength="20" placeholder="พิมพ์รหัสบางส่วน เช่น SC00" autocomplete="off">
                    <div class="suggest-list" id="courseSuggestList" role="listbox" hidden></div>
                    <p class="hint" id="courseHint">พิมพ์อย่างน้อย 2 ตัวอักษรเพื่อเลือกรายวิชาจากระบบ หากไม่พบสามารถพิมพ์รหัสและชื่อวิชาเองได้</p>
                </div>
                <div class="field">
                    <label for="COURSE_NAME">ชื่อวิชา</label>
                    <input type="text" name="COURSE_NAME" id="COURSE_NAME" value="{{ old('COURSE_NAME') }}" maxlength="255" placeholder="ชื่อรายวิชา (เลือกจากรายการหรือพิมพ์เอง)">
                </div>
                <div class="field suggest-wrap" id="roomSuggestWrap">
                    <label for="ROOM_NAME">ห้องสอบ</label>
                    <input type="text" name="ROOM_NAME" id="ROOM_NAME" value="{{ old('ROOM_NAME') }}" maxlength="100" placeholder="พิมพ์บางส่วน เช่น 8103" autocomplete="off">
                    <div class="suggest-list" id="roomSuggestList" role="listbox" hidden></div>
                    <p class="hint" id="roomHint">พิมพ์บางส่วนเพื่อเลือกห้องจากรายการ หากไม่มีในรายการพิมพ์ห้องเองได้</p>
                </div>
                <div class="field">
                    <label for="EXAM_TYPE">ช่วงสอบ</label>
                    <select name="EXAM_TYPE" id="EXAM_TYPE" required>
                        <option value="M" @selected(old('EXAM_TYPE', $defaultExamType ?? 'F') === 'M')>กลางภาค (M)</option>
                        <option value="F" @selected(old('EXAM_TYPE', $defaultExamType ?? 'F') === 'F')>ปลายภาค (F)</option>
                    </select>
                </div>
                <div class="field">
                    <label for="SEMESTER">ภาคการศึกษา</label>
                    <select name="SEMESTER" id="SEMESTER" required>
                        <option value="1" @selected((int)old('SEMESTER', $defaultTerm) === 1)>ภาคต้น</option>
                        <option value="2" @selected((int)old('SEMESTER', $defaultTerm) === 2)>ภาคปลาย</option>
                    </select>
                </div>
                <div class="field">
                    <label for="ACADYEAR">ปีการศึกษา</label>
                    <select name="ACADYEAR" id="ACADYEAR" required>
                        @foreach($years as $y)
                            <option value="{{ $y }}" @selected((int)old('ACADYEAR', $defaultYear) === (int)$y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="panel panel-reason">
            <div class="panel-head">
                <div class="step-badge" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 4v8l4 2"/><circle cx="12" cy="12" r="8"/></svg>
                </div>
                <div>
                    <h2>4. สาเหตุที่มาสอบช้า</h2>
                    <span class="sub">เลือกสาเหตุที่ตรงที่สุด หากเป็นอื่น ๆ ให้ระบุรายละเอียด</span>
                </div>
            </div>
            <div class="reason-list">
                @foreach($reasons as $reason)
                    @php $icon = $reasonIcons[$reason->ReasonID] ?? $defaultReasonIcon; @endphp
                    <label class="reason-row">
                        <input type="radio" name="ReasonID" value="{{ $reason->ReasonID }}"
                               @checked((int)old('ReasonID', 1) === (int)$reason->ReasonID)
                               data-other="{{ $reason->ReasonName === 'อื่น ๆ' ? '1' : '0' }}">
                        <span class="reason-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                        </span>
                        <span>
                            <strong>{{ $reason->ReasonName }}</strong>
                            @if($reason->ReasonDesc)
                                <small>{{ $reason->ReasonDesc }}</small>
                            @endif
                        </span>
                    </label>
                @endforeach
            </div>
            <div class="field" style="margin-top:1rem" id="descField">
                <label for="DESCI">รายละเอียดเพิ่มเติม (กรณี อื่น ๆ)</label>
                <input type="text" name="DESCI" id="DESCI" value="{{ old('DESCI') }}" maxlength="254" placeholder="โปรดระบุ">
            </div>
            <div class="actions">
                <button type="reset" class="btn" id="btnReset">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M4 12a8 8 0 1 0 2.2-5.5M4 5v5h5"/></svg>
                    ล้างข้อมูล
                </button>
                <button type="submit" class="btn btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M7 8V4h10v4"/><path d="M6 8h12v4H6z"/><path d="M7 12v8h10v-8"/><path d="M9 15h6"/></svg>
                    บันทึกและพิมพ์แบบฟอร์ม
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
(() => {
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function bindSuggest({ input, list, hint, defaultHint, fetchUrl, minLen, extractItems, mapItems, onPick, emptyMessage, onEmpty, localFilter }) {
        let items = [];
        let active = -1;
        let timer = null;
        let abortCtrl = null;

        function closeList() {
            list.classList.remove('is-open');
            list.hidden = true;
            list.innerHTML = '';
            items = [];
            active = -1;
        }

        function render() {
            list.innerHTML = '';
            items.forEach((item, i) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'suggest-item' + (i === active ? ' is-active' : '');
                btn.setAttribute('role', 'option');
                btn.innerHTML = mapItems(item);
                btn.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    onPick(item);
                    closeList();
                });
                list.appendChild(btn);
            });
            if (!items.length) {
                closeList();
                return;
            }
            list.hidden = false;
            list.classList.add('is-open');
        }

        async function search(value) {
            const term = (value || '').trim();
            if (term.length < minLen) {
                closeList();
                if (hint) hint.textContent = defaultHint;
                return;
            }

            if (typeof localFilter === 'function') {
                items = localFilter(term);
                active = items.length ? 0 : -1;
                if (!items.length) {
                    closeList();
                    if (hint) hint.textContent = emptyMessage;
                    if (typeof onEmpty === 'function') onEmpty();
                    return;
                }
                if (hint) hint.textContent = 'พบ ' + items.length + ' รายการ เลือกจากรายการด้านล่าง';
                render();
                return;
            }

            if (abortCtrl) abortCtrl.abort();
            abortCtrl = new AbortController();
            if (hint) hint.textContent = 'กำลังค้นหา...';

            try {
                const res = await fetch(fetchUrl + '?q=' + encodeURIComponent(term), {
                    headers: { 'Accept': 'application/json' },
                    signal: abortCtrl.signal,
                });
                const data = await res.json();
                items = extractItems(data);
                active = items.length ? 0 : -1;
                if (!items.length) {
                    closeList();
                    if (hint) hint.textContent = emptyMessage;
                    if (typeof onEmpty === 'function') onEmpty();
                    return;
                }
                if (hint) hint.textContent = 'พบ ' + items.length + ' รายการ เลือกจากรายการด้านล่าง';
                render();
            } catch (e) {
                if (e.name === 'AbortError') return;
                if (hint) hint.textContent = 'ค้นหาไม่สำเร็จ กรุณาลองใหม่ หรือกรอกข้อมูลเอง';
                closeList();
            }
        }

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => search(input.value), 250);
        });

        input.addEventListener('focus', () => {
            if ((input.value || '').trim().length >= minLen || minLen === 0) {
                search(input.value);
            }
        });

        input.addEventListener('keydown', (e) => {
            if (!list.classList.contains('is-open') || !items.length) return;
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                active = (active + 1) % items.length;
                render();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                active = (active - 1 + items.length) % items.length;
                render();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (active >= 0 && items[active]) {
                    onPick(items[active]);
                    closeList();
                }
            } else if (e.key === 'Escape') {
                closeList();
            }
        });

        return {
            closeList,
            resetHint() {
                if (hint) hint.textContent = defaultHint;
            },
        };
    }

    const studentDefaultHint = 'พิมพ์อย่างน้อย 2 ตัวอักษร ระบบจะแสดงรายการให้เลือกอัตโนมัติ หากไม่พบสามารถกรอกข้อมูลในส่วนที่ 2 ได้เอง';
    const courseDefaultHint = 'พิมพ์อย่างน้อย 2 ตัวอักษรเพื่อเลือกรายวิชาจากระบบ หากไม่พบสามารถพิมพ์รหัสและชื่อวิชาเองได้';
    const deptDefaultHint = 'พิมพ์ชื่อสาขาบางส่วน หรือคลิกช่องเพื่อเลือกรายการ (เฉพาะชื่อขึ้นต้นด้วย สาขาวิชา) หากไม่พบพิมพ์เองได้';
    const roomDefaultHint = 'พิมพ์บางส่วนเพื่อเลือกห้องจากรายการ หากไม่มีในรายการพิมพ์ห้องเองได้';
    const examRooms = ['8103', '8104', '8105', '8304', '8305', '8312', '8404', '8405', '8412', '8504', '8505', '8512', '8601'];

    const studentSuggest = bindSuggest({
        input: document.getElementById('lookup_q'),
        list: document.getElementById('suggestList'),
        hint: document.getElementById('lookupHint'),
        defaultHint: studentDefaultHint,
        fetchUrl: @json(route('late-exam.record.lookup')),
        minLen: 2,
        emptyMessage: 'ไม่พบรหัสที่ตรงกัน — กรอกข้อมูลนักศึกษาในส่วนที่ 2 ได้เอง',
        extractItems: (data) => Array.isArray(data.students) ? data.students : [],
        mapItems: (s) => '<span class="code">' + escapeHtml(s.STUDENTCODE || '') + '</span>'
            + '<span class="meta">' + escapeHtml(s.full_name || '')
            + (s.DEPARTMENTNAME ? ' · ' + escapeHtml(s.DEPARTMENTNAME) : '')
            + '</span>',
        onPick: (s) => {
            document.getElementById('STUDENTID').value = s.STUDENTID || 0;
            document.getElementById('STUDENTCODE').value = s.STUDENTCODE || '';
            document.getElementById('STUDENT_NAME').value = s.full_name || '';
            document.getElementById('DEPARTMENT_NAME').value = s.DEPARTMENTNAME || '';
            document.getElementById('PROGRAM_NAME').value = '';
            if (s.YEAR) document.getElementById('ACADYEAR').value = s.YEAR;
            if (s.TERM) document.getElementById('SEMESTER').value = s.TERM;
            document.getElementById('lookup_q').value = s.STUDENTCODE || '';
            document.getElementById('lookupHint').textContent =
                'เลือกแล้วจาก ' + (s.source === 'cache' ? 'แคชที่นำเข้า' : 'REG') + ' — แก้ไขข้อมูลในส่วนที่ 2 ได้หากต้องการ';
        },
        onEmpty: () => { document.getElementById('STUDENTID').value = 0; },
    });

    const courseSuggest = bindSuggest({
        input: document.getElementById('COURSE_CODE'),
        list: document.getElementById('courseSuggestList'),
        hint: document.getElementById('courseHint'),
        defaultHint: courseDefaultHint,
        fetchUrl: @json(route('late-exam.record.courses')),
        minLen: 2,
        emptyMessage: 'ไม่พบรายวิชา — พิมพ์รหัสและชื่อวิชาเองได้',
        extractItems: (data) => Array.isArray(data.courses) ? data.courses : [],
        mapItems: (c) => '<span class="code">' + escapeHtml(c.code || '') + '</span>'
            + '<span class="meta">' + escapeHtml(c.name || '')
            + (c.credit ? ' · ' + escapeHtml(c.credit) : '')
            + '</span>',
        onPick: (c) => {
            document.getElementById('COURSE_CODE').value = c.code || '';
            document.getElementById('COURSE_NAME').value = c.name || '';
            document.getElementById('courseHint').textContent = 'เลือกแล้ว — แก้ไขชื่อวิชาได้หากต้องการ';
        },
    });

    const deptSuggest = bindSuggest({
        input: document.getElementById('DEPARTMENT_NAME'),
        list: document.getElementById('deptSuggestList'),
        hint: document.getElementById('deptHint'),
        defaultHint: deptDefaultHint,
        fetchUrl: @json(route('late-exam.record.departments')),
        minLen: 0,
        emptyMessage: 'ไม่พบสาขาวิชา — พิมพ์ชื่อสาขาเองได้',
        extractItems: (data) => Array.isArray(data.departments) ? data.departments : [],
        mapItems: (d) => '<span class="code">' + escapeHtml(d.name || '') + '</span>',
        onPick: (d) => {
            document.getElementById('DEPARTMENT_NAME').value = d.name || '';
            document.getElementById('deptHint').textContent = 'เลือกแล้วจากรายการสาขาวิชา — แก้ไขได้หากต้องการ';
        },
    });

    const roomSuggest = bindSuggest({
        input: document.getElementById('ROOM_NAME'),
        list: document.getElementById('roomSuggestList'),
        hint: document.getElementById('roomHint'),
        defaultHint: roomDefaultHint,
        minLen: 0,
        emptyMessage: 'ไม่พบห้องในรายการ — พิมพ์ห้องสอบเองได้',
        localFilter: (term) => {
            const needle = term.toLowerCase();
            return examRooms
                .filter((room) => needle === '' || room.toLowerCase().includes(needle))
                .map((room) => ({ code: room }));
        },
        mapItems: (r) => '<span class="code">' + escapeHtml(r.code || '') + '</span>',
        onPick: (r) => {
            document.getElementById('ROOM_NAME').value = r.code || '';
            document.getElementById('roomHint').textContent = 'เลือกแล้วจากรายการห้องสอบ — แก้ไขหรือพิมพ์เองได้หากต้องการ';
        },
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#lookup_q') && !e.target.closest('#suggestList')) {
            studentSuggest.closeList();
        }
        if (!e.target.closest('#COURSE_CODE') && !e.target.closest('#courseSuggestList')) {
            courseSuggest.closeList();
        }
        if (!e.target.closest('#DEPARTMENT_NAME') && !e.target.closest('#deptSuggestList')) {
            deptSuggest.closeList();
        }
        if (!e.target.closest('#ROOM_NAME') && !e.target.closest('#roomSuggestList')) {
            roomSuggest.closeList();
        }
    });

    document.getElementById('btnReset')?.addEventListener('click', () => {
        setTimeout(() => {
            document.getElementById('STUDENTID').value = 0;
            document.getElementById('PROGRAM_NAME').value = '';
            document.getElementById('lookup_q').value = '';
            studentSuggest.resetHint();
            courseSuggest.resetHint();
            deptSuggest.resetHint();
            roomSuggest.resetHint();
            studentSuggest.closeList();
            courseSuggest.closeList();
            deptSuggest.closeList();
            roomSuggest.closeList();
        }, 0);
    });
})();
</script>
@endpush
