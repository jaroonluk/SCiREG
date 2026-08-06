@extends('layouts.app')

@section('title', $title)

@section('styles')
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
    .page-head h1 {
        font: 700 clamp(1.35rem,3vw,1.7rem) 'Outfit','Sarabun',sans-serif;
        margin-bottom: .2rem;
    }
    .page-head p { color: var(--ink-muted); line-height: 1.55; }
    .placeholder {
        position: relative;
        background: linear-gradient(180deg, rgba(255,253,247,.98), rgba(255,250,236,.94));
        border: 1px solid var(--line);
        border-radius: 1.2rem;
        padding: 1.75rem;
        color: var(--ink-muted);
        line-height: 1.6;
        overflow: hidden;
        animation: rise .5s .06s ease both;
        display: flex; gap: .75rem; align-items: flex-start;
    }
    .placeholder::before {
        content: ''; position: absolute; inset: 0 auto 0 0; width: 4px;
        background: linear-gradient(180deg, var(--champaca), var(--champaca-deep));
    }
    .placeholder svg { width: 1.35rem; height: 1.35rem; color: var(--champaca-deep); flex-shrink: 0; margin-top: .1rem; }
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
                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                    <path d="M8 9h8M8 12h8M8 15h5"/>
                </svg>
            </div>
            <div>
                <h1>{{ $title }}</h1>
                <p>{{ $description }}</p>
            </div>
        </div>
    </div>
    <div class="placeholder">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
        <span>หน้านี้พร้อมสำหรับพัฒนาฟังก์ชันถัดไป</span>
    </div>
@endsection
