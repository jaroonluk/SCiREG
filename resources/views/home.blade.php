@extends('layouts.app')

@section('title', 'หน้าหลัก')

@section('styles')
        main.app-main {
            max-width: min(100%, 1180px);
            width: 100%;
        }

        .welcome {
            display: flex;
            align-items: center;
            gap: 1.15rem;
            margin-bottom: 1.1rem;
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
            font-size: clamp(1.35rem, 3vw, 1.75rem);
            margin-bottom: 0.25rem;
            line-height: 1.25;
        }
        .welcome p { color: var(--ink-muted); line-height: 1.5; }
        .role-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            margin-top: 0.45rem;
            padding: 0.28rem 0.75rem;
            border-radius: 999px;
            background: rgba(230, 180, 34, 0.16);
            color: var(--champaca-deep);
            font-size: 0.86rem;
            font-weight: 600;
        }
        .role-chip svg { width: 0.95rem; height: 0.95rem; }

        .menu-divider {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin: 0 0 1.35rem;
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

        .menu-zones {
            display: grid;
            gap: 1.15rem;
        }

        .menu-zone {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 1.25rem;
            padding: 1.15rem 1.15rem 1rem;
            box-shadow: 0 16px 36px -28px rgba(120, 80, 10, 0.4);
            animation: rise 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        .menu-zone:nth-child(1) { animation-delay: 0.1s; }
        .menu-zone:nth-child(2) { animation-delay: 0.16s; }
        .menu-zone:nth-child(3) { animation-delay: 0.22s; }

        .zone-head {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            margin-bottom: 0.95rem;
            padding-bottom: 0.9rem;
            border-bottom: 1px solid rgba(201, 146, 26, 0.14);
        }
        .zone-icon {
            width: 2.85rem;
            height: 2.85rem;
            border-radius: 0.9rem;
            display: grid;
            place-items: center;
            color: #fff;
            flex-shrink: 0;
        }
        .zone-icon svg { width: 1.35rem; height: 1.35rem; }
        .zone-icon.fee { background: linear-gradient(145deg, #e6b422, #c9921a); }
        .zone-icon.late { background: linear-gradient(145deg, #f0c94a, #c9921a); }
        .zone-icon.users { background: linear-gradient(145deg, #f3d06a, #b8860b); }
        .zone-head h2 {
            font-size: 1.12rem;
            font-weight: 700;
            line-height: 1.3;
        }
        .zone-head p {
            margin-top: 0.15rem;
            color: var(--ink-muted);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .zone-links {
            list-style: none;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 0.55rem;
        }
        .zone-links a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--ink);
            background: rgba(255, 252, 245, 0.72);
            border: 1px solid rgba(201, 146, 26, 0.14);
            border-radius: 0.95rem;
            padding: 0.8rem 0.9rem;
            transition: background 0.16s ease, border-color 0.16s ease, transform 0.16s ease;
            min-height: 4.1rem;
        }
        .zone-links a:hover {
            background: rgba(230, 180, 34, 0.14);
            border-color: rgba(201, 146, 26, 0.35);
            transform: translateY(-1px);
        }
        .item-icon {
            width: 2.35rem;
            height: 2.35rem;
            border-radius: 0.75rem;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            background: rgba(230, 180, 34, 0.16);
            color: var(--champaca-deep);
        }
        .item-icon svg { width: 1.15rem; height: 1.15rem; }
        .item-text {
            flex: 1;
            min-width: 0;
        }
        .item-text strong {
            display: block;
            font-size: 0.96rem;
            font-weight: 650;
            line-height: 1.35;
        }
        .item-text small {
            display: block;
            margin-top: 0.15rem;
            color: var(--ink-muted);
            font-size: 0.82rem;
            line-height: 1.35;
        }
        .item-arrow {
            width: 1rem;
            height: 1rem;
            opacity: 0.45;
            flex-shrink: 0;
            color: var(--champaca-deep);
        }

        .empty-access {
            background: var(--surface);
            border: 1px dashed var(--line);
            border-radius: 1.1rem;
            padding: 2rem 1.25rem;
            text-align: center;
            color: var(--ink-muted);
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 640px) {
            .welcome { flex-direction: column; text-align: center; }
            .role-chip { justify-content: center; }
            .zone-head { align-items: flex-start; }
        }
@endsection

@section('content')
    @php
        $user = auth()->user();
        $avatar = session('google_avatar');
        $initial = mb_substr($user->fname ?: $user->email ?: 'U', 0, 1);

        $zones = [];

        $feeMenus = [];
        if ($user->canAccessPayments()) {
            $feeMenus[] = [
                'route' => 'research-fee.payments',
                'title' => 'จัดการข้อมูลชำระเงินค่าธรรมเนียมวิจัย',
                'desc' => 'บันทึกสถานะ ออกเอกสาร และสรุปยอดชำระ',
                'svg' => '<path d="M4 7h16v10H4z"/><path d="M4 10h16M8 14h3"/><circle cx="17" cy="14" r="1.2"/>',
            ];
        }
        if ($user->canAccessImport()) {
            $feeMenus[] = [
                'route' => 'research-fee.import',
                'title' => 'นำเข้าข้อมูลนักศึกษาจาก REG',
                'desc' => 'ดึงรายชื่อนักศึกษาระดับบัณฑิตศึกษา',
                'svg' => '<path d="M12 3v10"/><path d="M8.5 9.5 12 13l3.5-3.5"/><path d="M5 18h14"/>',
            ];
        }
        if ($user->canAccessSummaryReport()) {
            $feeMenus[] = [
                'route' => 'research-fee.summary',
                'title' => 'รายงานค่าธรรมเนียมวิจัย',
                'desc' => $user->isDepartmentOfficer()
                    ? 'สรุปและส่งออกเฉพาะสาขาที่สังกัด'
                    : 'สรุปจำนวนและยอดรับชำระตามสาขาวิชา',
                'svg' => '<path d="M4 19V5M4 19h16"/><path d="M8 16V10M12 16V7M16 16v-4"/>',
            ];
        }
        if ($feeMenus !== []) {
            $zones[] = [
                'title' => 'จัดการค่าธรรมเนียมวิจัย',
                'desc' => 'ชำระเงิน นำเข้าข้อมูล และรายงานสรุป',
                'icon' => 'fee',
                'icon_svg' => '<path d="M4 7h16M4 12h16M4 17h10"/><circle cx="18" cy="17" r="2.2"/>',
                'menus' => $feeMenus,
            ];
        }

        if ($user->canAccessLateExam()) {
            $zones[] = [
                'title' => 'รายงานเข้าสอบช้านักศึกษา',
                'desc' => 'นำเข้า บันทึก พิมพ์ และสรุปการเข้าสอบช้า',
                'icon' => 'late',
                'icon_svg' => '<circle cx="12" cy="13" r="7"/><path d="M12 10v3.5l2.2 1.4M9 3.5h6"/>',
                'menus' => [
                    [
                        'route' => 'late-exam.import',
                        'title' => 'นำเข้าข้อมูลสอบช้าจาก REG',
                        'desc' => 'เตรียมรายชื่อนักศึกษาสำหรับบันทึก',
                        'svg' => '<path d="M12 3v10"/><path d="M8.5 9.5 12 13l3.5-3.5"/><path d="M5 18h14"/>',
                    ],
                    [
                        'route' => 'late-exam.record',
                        'title' => 'บันทึกการเข้าสอบช้า',
                        'desc' => 'บันทึกรายการนักศึกษาที่เข้าสอบช้า',
                        'svg' => '<path d="M5 5h10v14H5z"/><path d="M9 9h2.5M9 12h4M9 15h3.5M15 8h4v11h-4"/>',
                    ],
                    [
                        'route' => 'late-exam.print',
                        'title' => 'พิมพ์แบบฟอร์มการเข้าสอบช้า',
                        'desc' => 'จัดพิมพ์แบบฟอร์มสำหรับดำเนินการ',
                        'svg' => '<path d="M7 8V4h10v4"/><path d="M6 8h12v4H6z"/><path d="M7 12v8h10v-8"/><path d="M9 15h6"/>',
                    ],
                    [
                        'route' => 'late-exam.summary',
                        'title' => 'รายงานสรุปการเข้าสอบช้า',
                        'desc' => 'ดูสรุปรายงานการเข้าสอบช้า',
                        'svg' => '<path d="M4 19V5M4 19h16"/><path d="M8 16V11M12 16V8M16 16v-3"/>',
                    ],
                    [
                        'route' => 'late-exam.term-setting',
                        'title' => 'กำหนดภาคการศึกษาปัจจุบัน',
                        'desc' => 'ตั้งค่าปี/ภาคเริ่มต้นของเมนูสอบช้า',
                        'svg' => '<rect x="4" y="5" width="16" height="15" rx="2"/><path d="M8 3v4M16 3v4M4 10h16"/>',
                    ],
                ],
            ];
        }

        if ($user->canManageUsers()) {
            $zones[] = [
                'title' => 'กำหนดสิทธิใช้งาน',
                'desc' => 'จัดการสิทธิผู้ใช้ ผู้ลงนาม และประวัติการใช้งาน',
                'icon' => 'users',
                'icon_svg' => '<circle cx="9" cy="8" r="3"/><path d="M3.5 19c.7-3 2.8-4.8 5.5-4.8S14 16 14.7 19"/><path d="M16 8h5M18.5 5.5v5"/>',
                'menus' => [
                    [
                        'route' => 'users.permissions',
                        'title' => 'กำหนดสิทธิผู้ใช้งานระบบ',
                        'desc' => 'กำหนดสิทธิงานบริการ สาขาวิชา และการเงิน',
                        'svg' => '<path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/><path d="M9.2 12.1l1.8 1.8 3.8-3.8"/>',
                    ],
                    [
                        'route' => 'late-exam.signers',
                        'title' => 'กำหนดผู้บริหารลงนามเอกสาร',
                        'desc' => 'กำหนดผู้ปฏิบัติการแทน/รักษาการแทนคณบดี',
                        'svg' => '<path d="M4 19l3.2-1.1L18 7.1a2.1 2.1 0 0 0-3-3L4.2 14.9 4 19z"/><path d="M13.8 5.2l3 3"/>',
                    ],
                    [
                        'route' => 'audit-logs.index',
                        'title' => 'เข้าดูข้อมูล log',
                        'desc' => 'ติดตามประวัติการใช้งานระบบ (audit log)',
                        'svg' => '<path d="M8 4h8a2 2 0 0 1 2 2v14l-3-1.5L12 20l-3-1.5L6 20V6a2 2 0 0 1 2-2z"/><path d="M9 9h6M9 12h6M9 15h4"/>',
                    ],
                ],
            ];
        }
    @endphp

    <section class="welcome">
        @if ($avatar)
            <img class="welcome-avatar" src="{{ $avatar }}" alt="รูปโปรไฟล์ {{ $user->full_name }}" referrerpolicy="no-referrer">
        @else
            <div class="welcome-avatar avatar-fallback" aria-hidden="true">{{ $initial }}</div>
        @endif
        <div>
            <h1>ยินดีต้อนรับ, {{ $user->full_name }}</h1>
            <p>ระบบสารสนเทศสำหรับงานบริการการศึกษา คณะวิทยาศาสตร์</p>
            <span class="role-chip">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l8 4.5v5.2c0 4.4-2.9 7.8-8 9.3-5.1-1.5-8-4.9-8-9.3V7.5L12 3Z"/></svg>
                {{ $user->sciregRoleLabel() }}
            </span>
        </div>
    </section>

    <div class="menu-divider" aria-hidden="true">
        <span class="menu-divider-mark"></span>
    </div>

    @if (count($zones) === 0)
        <div class="empty-access">บัญชีนี้ยังไม่มีเมนูที่สามารถเข้าใช้งานได้</div>
    @else
        <div class="menu-zones">
            @foreach ($zones as $zone)
                <section class="menu-zone" aria-label="{{ $zone['title'] }}">
                    <div class="zone-head">
                        <div class="zone-icon {{ $zone['icon'] }}" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $zone['icon_svg'] !!}</svg>
                        </div>
                        <div>
                            <h2>{{ $zone['title'] }}</h2>
                            <p>{{ $zone['desc'] }}</p>
                        </div>
                    </div>
                    <ul class="zone-links">
                        @foreach ($zone['menus'] as $menu)
                            <li>
                                <a href="{{ route($menu['route']) }}">
                                    <span class="item-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">{!! $menu['svg'] !!}</svg>
                                    </span>
                                    <span class="item-text">
                                        <strong>{{ $menu['title'] }}</strong>
                                        <small>{{ $menu['desc'] }}</small>
                                    </span>
                                    <svg class="item-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    @endif
@endsection
