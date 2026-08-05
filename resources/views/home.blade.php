@extends('layouts.app')

@section('title', 'หน้าหลัก')

@section('styles')
        .welcome {
            display: flex;
            align-items: center;
            gap: 1.15rem;
            margin-bottom: 1.65rem;
            animation: rise 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .welcome-avatar {
            width: 4.25rem;
            height: 4.25rem;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(230, 180, 34, 0.5);
            background: #fff;
            flex-shrink: 0;
        }
        .welcome-avatar.avatar-fallback { font-size: 1.35rem; }
        .welcome h1 {
            font-family: 'Outfit', 'Sarabun', sans-serif;
            font-size: clamp(1.35rem, 3vw, 1.7rem);
            margin-bottom: 0.25rem;
            line-height: 1.25;
        }
        .welcome p { color: var(--ink-muted); line-height: 1.55; }

        .menu-divider {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin: 0 0 1.5rem;
            animation: rise 0.55s 0.08s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .menu-divider::before,
        .menu-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(201, 146, 26, 0.45), transparent);
        }
        .menu-divider-mark {
            width: 0.55rem;
            height: 0.55rem;
            border-radius: 50%;
            background: linear-gradient(145deg, var(--champaca), var(--champaca-deep));
            box-shadow: 0 0 0 4px rgba(230, 180, 34, 0.18);
            flex-shrink: 0;
        }

        .menu-grid {
            display: grid;
            gap: 1.15rem;
        }

        .menu-section {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 1.15rem;
            padding: 1.25rem 1.25rem 0.85rem;
            box-shadow: 0 16px 36px -28px rgba(120, 80, 10, 0.4);
            animation: rise 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .menu-section:nth-child(1) { animation-delay: 0.12s; }
        .menu-section:nth-child(2) { animation-delay: 0.2s; }
        .menu-section:nth-child(3) { animation-delay: 0.28s; }

        .menu-section-head {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            margin-bottom: 0.85rem;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid rgba(201, 146, 26, 0.16);
        }
        .menu-icon {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 0.8rem;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            background: linear-gradient(145deg, rgba(230, 180, 34, 0.28), rgba(201, 146, 26, 0.18));
            color: var(--champaca-deep);
        }
        .menu-icon svg { width: 1.25rem; height: 1.25rem; }
        .menu-section-head h3 {
            font-size: 1.08rem;
            font-weight: 700;
            line-height: 1.3;
        }
        .menu-section-head p {
            margin-top: 0.2rem;
            color: var(--ink-muted);
            font-size: 0.9rem;
            line-height: 1.45;
        }

        .menu-links {
            list-style: none;
            display: grid;
            gap: 0.35rem;
            padding-bottom: 0.35rem;
        }
        .menu-links a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            text-decoration: none;
            padding: 0.75rem 0.85rem;
            border-radius: 0.75rem;
            color: var(--ink);
            font-weight: 500;
            transition: background 0.18s ease, transform 0.18s ease, color 0.18s ease;
        }
        .menu-links a:hover {
            background: rgba(230, 180, 34, 0.16);
            color: var(--champaca-deep);
            transform: translateX(3px);
        }
        .menu-links a span {
            line-height: 1.35;
        }
        .menu-links a svg {
            width: 1rem;
            height: 1rem;
            opacity: 0.55;
            flex-shrink: 0;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (min-width: 860px) {
            .menu-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            .menu-section:last-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 640px) {
            .welcome { flex-direction: column; text-align: center; }
        }
@endsection

@section('content')
    @php
        $avatar = session('google_avatar');
        $initial = mb_substr(auth()->user()->fname ?: auth()->user()->email ?: 'U', 0, 1);
    @endphp

    <section class="welcome">
        @if ($avatar)
            <img class="welcome-avatar" src="{{ $avatar }}" alt="รูปโปรไฟล์ {{ auth()->user()->full_name }}" referrerpolicy="no-referrer">
        @else
            <div class="welcome-avatar avatar-fallback" aria-hidden="true">{{ $initial }}</div>
        @endif
        <div>
            <h1>ยินดีต้อนรับ, {{ auth()->user()->full_name }}</h1>
            <p>ระบบสารสนเทศสำหรับงานบริการการศึกษา คณะวิทยาศาสตร์</p>
        </div>
    </section>

    <div class="menu-divider" aria-hidden="true">
        <span class="menu-divider-mark"></span>
    </div>

    <div class="menu-grid">
        <section class="menu-section" aria-labelledby="menu-research-fee">
            <div class="menu-section-head">
                <div class="menu-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M4 7h16M4 12h16M4 17h10"/>
                        <circle cx="18" cy="17" r="2.2"/>
                    </svg>
                </div>
                <div>
                    <h3 id="menu-research-fee">ค่าธรรมเนียมวิจัย</h3>
                    <p>จัดการชำระเงินและนำเข้าข้อมูลนักศึกษา</p>
                </div>
            </div>
            <ul class="menu-links">
                <li>
                    <a href="{{ route('research-fee.payments') }}">
                        <span>จัดการข้อมูลชำระเงินค่าธรรมเนียมวิจัย</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('research-fee.import') }}">
                        <span>นำเข้าข้อมูลนักศึกษาจาก REG</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
            </ul>
        </section>

        <section class="menu-section" aria-labelledby="menu-users">
            <div class="menu-section-head">
                <div class="menu-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="9" cy="8" r="3.2"/>
                        <path d="M3.5 19c.8-3.2 3-5 5.5-5s4.7 1.8 5.5 5"/>
                        <path d="M16 8h5M18.5 5.5v5"/>
                    </svg>
                </div>
                <div>
                    <h3 id="menu-users">จัดการสิทธิผู้ใช้</h3>
                    <p>กำหนดสิทธิการเข้าถึงระบบ</p>
                </div>
            </div>
            <ul class="menu-links">
                <li>
                    <a href="{{ route('users.permissions') }}">
                        <span>กำหนดสิทธิผู้ใช้งานระบบ</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
            </ul>
        </section>

        <section class="menu-section" aria-labelledby="menu-late-exam">
            <div class="menu-section-head">
                <div class="menu-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="13" r="7"/>
                        <path d="M12 10v3.5l2.2 1.4M9 3.5h6"/>
                    </svg>
                </div>
                <div>
                    <h3 id="menu-late-exam">รายงานการเข้าสอบสาย</h3>
                    <p>นำเข้าข้อมูล บันทึก และพิมพ์แบบฟอร์ม</p>
                </div>
            </div>
            <ul class="menu-links">
                <li>
                    <a href="{{ route('late-exam.import') }}">
                        <span>นำเข้าข้อมูลนักศึกษาจาก REG</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('late-exam.record') }}">
                        <span>บันทึกการเข้าสอบช้า</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ route('late-exam.print') }}">
                        <span>พิมพ์แบบฟอร์มการเข้าสอบช้า</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5l7 7-7 7"/></svg>
                    </a>
                </li>
            </ul>
        </section>
    </div>
@endsection
