@extends('layouts.app')

@section('title', $title)

@section('styles')
        .page-head {
            margin-bottom: 1.5rem;
        }
        .page-head a {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none;
            color: var(--ink-muted);
            font-size: 0.92rem;
            margin-bottom: 0.75rem;
        }
        .page-head a:hover { color: var(--champaca-deep); }
        .page-head h1 {
            font-family: 'Outfit', 'Sarabun', sans-serif;
            font-size: clamp(1.35rem, 3vw, 1.7rem);
            margin-bottom: 0.35rem;
        }
        .page-head p { color: var(--ink-muted); line-height: 1.55; }
        .placeholder {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 1.15rem;
            padding: 1.75rem;
            color: var(--ink-muted);
            line-height: 1.6;
        }
@endsection

@section('content')
    <div class="page-head">
        <a href="{{ route('home') }}">← กลับเมนูหลัก</a>
        <h1>{{ $title }}</h1>
        <p>{{ $description }}</p>
    </div>
    <div class="placeholder">
        หน้านี้พร้อมสำหรับพัฒนาฟังก์ชันถัดไป
    </div>
@endsection
