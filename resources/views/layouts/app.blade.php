<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SCiREG') — SCiREG</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --champaca: #e6b422;
            --champaca-deep: #c9921a;
            --ink: #2a2214;
            --ink-muted: #6b5d45;
            --line: rgba(201, 146, 26, 0.22);
            --surface: rgba(255, 252, 245, 0.92);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            font-family: 'Sarabun', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(ellipse 70% 50% at 8% 12%, rgba(230, 180, 34, 0.22), transparent 55%),
                radial-gradient(ellipse 60% 45% at 92% 88%, rgba(201, 146, 26, 0.16), transparent 50%),
                linear-gradient(165deg, #fffdf7 0%, #f6e7c4 52%, #f0db9a 100%);
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            background-image:
                linear-gradient(rgba(201, 146, 26, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(201, 146, 26, 0.04) 1px, transparent 1px);
            background-size: 52px 52px;
            mask-image: radial-gradient(ellipse at center, black 25%, transparent 78%);
        }
        header.app-header,
        main.app-main { position: relative; z-index: 1; }
        a { color: inherit; }
        header.app-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.75rem;
            border-bottom: 1px solid var(--line);
            background: rgba(255, 252, 245, 0.85);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 0;
            z-index: 20;
        }
        .logo {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.03em;
            text-decoration: none;
            line-height: 1;
            white-space: nowrap;
        }
        .logo-mark {
            height: 1.25em;
            width: auto;
            display: block;
            flex-shrink: 0;
            object-fit: contain;
        }
        .logo-text {
            display: inline-block;
            letter-spacing: -0.04em;
        }
        .logo-text .accent {
            color: var(--champaca-deep);
            margin: 0;
            padding: 0;
            letter-spacing: inherit;
        }
        .user {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            font-size: 0.95rem;
            color: var(--ink-muted);
            min-width: 0;
        }
        .user-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.1rem;
            min-width: 0;
        }
        .user-name {
            color: var(--ink);
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: min(42vw, 280px);
        }
        .user-email {
            font-size: 0.82rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: min(42vw, 280px);
        }
        .avatar {
            width: 2.6rem;
            height: 2.6rem;
            border-radius: 50%;
            object-fit: cover;
            background: #fff;
            border: 2px solid rgba(230, 180, 34, 0.55);
            box-shadow: 0 6px 16px -10px rgba(42, 34, 20, 0.55);
            flex-shrink: 0;
        }
        .avatar-fallback {
            display: grid;
            place-items: center;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #fffdf5;
            background: linear-gradient(145deg, var(--champaca), var(--champaca-deep));
        }
        .logout {
            appearance: none;
            border: 1px solid var(--line);
            background: #fffef9;
            color: var(--ink);
            font: inherit;
            font-weight: 600;
            padding: 0.45rem 0.95rem;
            border-radius: 999px;
            cursor: pointer;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }
        .logout svg { width: 0.95rem; height: 0.95rem; }
        .logout:hover { border-color: var(--champaca); color: var(--champaca-deep); }
        main.app-main {
            max-width: 960px;
            margin: 0 auto;
            padding: 2rem 1.5rem 3.5rem;
        }
        @media (max-width: 640px) {
            header.app-header { flex-wrap: wrap; }
            .user-meta { align-items: flex-start; }
        }
        @yield('styles')
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $avatar = session('google_avatar');
        $initial = mb_substr($user->fname ?: $user->email ?: 'U', 0, 1);
    @endphp
    <header class="app-header">
        <a href="{{ route('home') }}" class="logo">
            <img class="logo-mark" src="{{ asset('faculty-logo-cut.png') }}" alt="ตราสัญลักษณ์คณะวิทยาศาสตร์" width="40" height="40">
            <span class="logo-text">SCi<span class="accent">REG</span></span>
        </a>
        <div class="user">
            @if ($avatar)
                <img class="avatar" src="{{ $avatar }}" alt="รูปโปรไฟล์" referrerpolicy="no-referrer">
            @else
                <div class="avatar avatar-fallback" aria-hidden="true">{{ $initial }}</div>
            @endif
            <div class="user-meta">
                <span class="user-name">{{ $user->full_name }}</span>
                <span class="user-email">{{ $user->email }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M10 4H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h3"/>
                        <path d="M14 16l4-4-4-4M10 12h8"/>
                    </svg>
                    ออกจากระบบ
                </button>
            </form>
        </div>
    </header>
    <main class="app-main">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
