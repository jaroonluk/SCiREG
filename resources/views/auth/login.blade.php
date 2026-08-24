<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ — SCiREG</title>
    <link rel="icon" type="image/png" href="{{ asset('faculty-logo-cut.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('faculty-logo-cut.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --champaca: #e6b422;
            --champaca-deep: #c9921a;
            --champaca-soft: #fff8e8;
            --champaca-mist: #f7e7b8;
            --ink: #2a2214;
            --ink-muted: #6b5d45;
            --surface: rgba(255, 252, 245, 0.92);
            --line: rgba(201, 146, 26, 0.22);
            --danger: #9a3412;
            --danger-bg: #fff1e8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            font-family: 'Sarabun', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(230, 180, 34, 0.28), transparent 55%),
                radial-gradient(ellipse 70% 50% at 90% 80%, rgba(201, 146, 26, 0.18), transparent 50%),
                linear-gradient(165deg, #fffdf7 0%, #f6e7c4 48%, #efd89a 100%);
            display: grid;
            place-items: center;
            padding: 1.5rem;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(201, 146, 26, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201, 146, 26, 0.05) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            mask-image: radial-gradient(ellipse at center, black 30%, transparent 75%);
        }

        .shell {
            width: min(100%, 440px);
            position: relative;
            z-index: 1;
            animation: rise 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes softPulse {
            0%, 100% { opacity: 0.55; transform: scale(1); }
            50% { opacity: 0.85; transform: scale(1.04); }
        }

        .glow {
            position: absolute;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(230, 180, 34, 0.45), transparent 70%);
            top: -70px;
            right: -40px;
            animation: softPulse 5s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        .panel {
            background: var(--surface);
            backdrop-filter: blur(14px);
            border: 1px solid var(--line);
            border-radius: 1.5rem;
            padding: 2.25rem 2rem 2rem;
            box-shadow:
                0 1px 0 rgba(255, 255, 255, 0.7) inset,
                0 24px 48px -20px rgba(120, 80, 10, 0.28);
        }

        .brand-mark-wrap {
            width: 7.25rem;
            height: 7.25rem;
            margin: 0 auto 1.4rem;
            border-radius: 50%;
            padding: 0.28rem;
            background: linear-gradient(160deg, #ffffff 0%, #fff8e8 100%);
            box-shadow:
                0 0 0 1px rgba(201, 146, 26, 0.18),
                0 2px 4px rgba(42, 34, 20, 0.04),
                0 14px 28px -14px rgba(120, 80, 10, 0.4);
            display: grid;
            place-items: center;
        }

        .brand-mark {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            object-position: center;
            display: block;
            background: #fff;
        }

        .brand {
            font-family: 'Outfit', 'Sarabun', sans-serif;
            font-size: clamp(1.65rem, 4vw, 1.9rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            line-height: 1.15;
            color: var(--ink);
            margin-bottom: 0.55rem;
            text-align: center;
        }

        .brand span {
            color: var(--champaca-deep);
        }

        .lead {
            font-size: 1.02rem;
            font-weight: 400;
            color: var(--ink-muted);
            line-height: 1.55;
            margin-bottom: 1.75rem;
            text-align: center;
        }

        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--line), transparent);
            margin: 0 0 1.5rem;
        }

        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.9rem 1.25rem;
            border-radius: 999px;
            border: 1px solid rgba(42, 34, 20, 0.12);
            background: #fff;
            color: var(--ink);
            font-family: inherit;
            font-size: 1.05rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            box-shadow: 0 8px 20px -14px rgba(42, 34, 20, 0.45);
        }

        .google-btn:hover {
            transform: translateY(-2px);
            border-color: var(--champaca);
            box-shadow: 0 14px 28px -14px rgba(201, 146, 26, 0.55);
        }

        .google-btn:active {
            transform: translateY(0);
        }

        .google-btn svg {
            width: 1.25rem;
            height: 1.25rem;
            flex-shrink: 0;
        }

        .hint {
            margin-top: 1.25rem;
            text-align: center;
            font-size: 0.92rem;
            color: var(--ink-muted);
            line-height: 1.5;
        }

        .alert {
            margin-bottom: 1.25rem;
            padding: 0.95rem 1rem;
            border-radius: 0.9rem;
            font-size: 0.95rem;
            line-height: 1.55;
            animation: rise 0.45s ease both;
        }

        .alert-error {
            background: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(154, 52, 18, 0.18);
        }

        .alert-denied {
            background: linear-gradient(180deg, #fff9ef, #fff3d6);
            color: var(--ink);
            border: 1px solid var(--line);
        }

        .alert-denied strong {
            display: block;
            font-weight: 600;
            margin-bottom: 0.35rem;
            color: var(--champaca-deep);
        }

        .alert-denied .email {
            display: inline-block;
            margin-top: 0.45rem;
            font-size: 0.88rem;
            color: var(--ink-muted);
            word-break: break-all;
        }

        .footer {
            margin-top: 1.35rem;
            text-align: center;
            font-size: 0.85rem;
            color: var(--ink-muted);
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="glow" aria-hidden="true"></div>
        <div class="panel">
            <div class="brand-mark-wrap">
                <img
                    class="brand-mark"
                    src="{{ asset('faculty-logo-cut.png') }}"
                    alt="ตราสัญลักษณ์คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น"
                    width="116"
                    height="116"
                >
            </div>
            <h1 class="brand">SCi<span>REG</span></h1>
            <p class="lead">
                ระบบสารสนเทศสำหรับงานบริการการศึกษา<br>
                คณะวิทยาศาสตร์
            </p>

            <div class="divider"></div>

            @if (session('error'))
                <div class="alert alert-error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('auth_denied'))
                <div class="alert alert-denied" role="alert">
                    <strong>ไม่สามารถเข้าสู่ระบบได้</strong>
                    ระบบสารสนเทศสำหรับงานบริการการศึกษา คณะวิทยาศาสตร์
                    หากไม่สามารถเข้าสู่ระบบได้ กรุณาติดต่อผู้ดูแลระบบ
                    @if (session('denied_email'))
                        <span class="email">บัญชีที่พยายามเข้าใช้: {{ session('denied_email') }}</span>
                    @endif
                </div>
            @endif

            @if (session('auth_no_access'))
                <div class="alert alert-denied" role="alert">
                    <strong>ไม่สามารถใช้งานระบบได้</strong>
                    กรุณาติดต่อผู้ดูแลระบบเพื่อขอสิทธิ์เข้าใช้งาน
                    @if (session('denied_email'))
                        <span class="email">บัญชีที่พยายามเข้าใช้: {{ session('denied_email') }}</span>
                    @endif
                </div>
            @endif

            <a href="{{ route('auth.google') }}" class="google-btn">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#EA4335" d="M12 10.2v3.6h5.1c-.2 1.2-.9 2.3-1.9 3l3.1 2.4c1.8-1.7 2.9-4.1 2.9-7 0-.7-.1-1.3-.2-1.9H12z"/>
                    <path fill="#34A853" d="M6.6 14.3l-.9.7-2.5 1.9C5 19.1 8.2 21 12 21c2.4 0 4.4-.8 5.9-2.1l-3.1-2.4c-.8.6-1.9.9-2.8.9-2.2 0-4-1.5-4.7-3.5z"/>
                    <path fill="#4A90E2" d="M3.2 7.1C2.4 8.6 2 10.2 2 12s.4 3.4 1.2 4.9l3.4-2.6C6.2 13.5 6 12.8 6 12s.2-1.5.6-2.3L3.2 7.1z"/>
                    <path fill="#FBBC05" d="M12 6c1.3 0 2.5.5 3.4 1.3l2.6-2.6C16.4 3.3 14.4 2.5 12 2.5 8.2 2.5 5 4.4 3.2 7.1l3.4 2.6C7.9 7.5 9.8 6 12 6z"/>
                </svg>
                เข้าสู่ระบบด้วย Google
            </a>

            <p class="hint">ใช้บัญชี Google ของมหาวิทยาลัยที่ลงทะเบียนในระบบ</p>
        </div>
        <p class="footer">Faculty of Science · Khon Kaen University</p>
    </div>
</body>
</html>
