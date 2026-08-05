@extends('layouts.app')

@section('title', 'พิมพ์แบบฟอร์มการเข้าสอบช้า')

@section('styles')
    main.app-main { max-width: min(100%, 900px); width: 100%; }
    .page-head { margin-bottom: 1.2rem; }
    .back { color: var(--ink-muted); text-decoration: none; font-size: .92rem; }
    .page-head h1 {
        font: 700 clamp(1.35rem,3vw,1.7rem) 'Outfit','Sarabun',sans-serif;
        margin-top: .55rem;
    }
    .panel {
        background: var(--surface);
        border: 1px solid var(--line);
        border-radius: 1.15rem;
        box-shadow: 0 16px 36px -28px rgba(120,80,10,.4);
        padding: 1.25rem;
    }
    .note { color: var(--ink-muted); line-height: 1.55; margin-bottom: 1rem; }
    .signer-box {
        border: 1px dashed rgba(201,146,26,.4);
        border-radius: .95rem;
        padding: 1.1rem;
        text-align: center;
        font-family: 'TH Sarabun New','Sarabun',sans-serif;
        font-size: 1.1rem;
        line-height: 1.35;
        background: #fff;
    }
    .signer-box .name { font-weight: 700; margin-top: 1.5rem; }
    .actions { margin-top: 1rem; display: flex; gap: .5rem; flex-wrap: wrap; }
    .btn {
        border: 1px solid var(--line);
        background: #fff;
        color: var(--ink);
        padding: .58rem 1rem;
        border-radius: 999px;
        font: 600 .9rem inherit;
        text-decoration: none;
    }
    .btn-primary {
        background: linear-gradient(145deg, var(--champaca), var(--champaca-deep));
        border-color: transparent;
        color: #fff;
    }
@endsection

@section('content')
    <div class="page-head">
        <a class="back" href="{{ route('home') }}">← กลับเมนูหลัก</a>
        <h1>พิมพ์แบบฟอร์มการเข้าสอบช้า</h1>
    </div>

    <div class="panel">
        <p class="note">
            ส่วนเนื้อหาแบบฟอร์มจะพัฒนาต่อในขั้นถัดไป ส่วนท้ายเอกสารจะดึงผู้ลงนามจากเมนูกำหนดผู้บริหารลงนาม ดังนี้
        </p>

        <div class="signer-box">
            @if($signer)
                <div>ขอแสดงความนับถือ</div>
                <div class="name">{{ $signer['signature_name'] }}</div>
                <div>{{ $signer['position'] ?: '—' }}</div>
                <div>{{ $signer['role_label'] }}</div>
            @else
                <div>ยังไม่ได้กำหนดผู้บริหารสำหรับลงนาม</div>
            @endif
        </div>

        <div class="actions">
            <a class="btn btn-primary" href="{{ route('late-exam.signers') }}">ไปกำหนดผู้บริหารลงนามเอกสาร</a>
        </div>
    </div>
@endsection
