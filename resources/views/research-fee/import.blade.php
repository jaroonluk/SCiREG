@extends('layouts.app')

@section('title', 'นำเข้าข้อมูลนักศึกษาจาก REG')

@section('styles')
        .page-head {
            margin-bottom: 1.35rem;
            animation: rise 0.45s ease both;
        }
        .page-head a.back {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            text-decoration: none;
            color: var(--ink-muted);
            font-size: 0.92rem;
            margin-bottom: 0.7rem;
        }
        .page-head a.back:hover { color: var(--champaca-deep); }
        .page-head h1 {
            font-family: 'Outfit', 'Sarabun', sans-serif;
            font-size: clamp(1.35rem, 3vw, 1.7rem);
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 1.15rem;
            padding: 1.35rem 1.35rem 1.2rem;
            box-shadow: 0 16px 36px -28px rgba(120, 80, 10, 0.4);
            animation: rise 0.45s 0.06s ease both;
            margin-bottom: 1.15rem;
        }

        .import-form {
            display: flex;
            flex-wrap: wrap;
            gap: 0.85rem 1rem;
            align-items: end;
        }
        .field {
            display: grid;
            gap: 0.35rem;
            min-width: 140px;
        }
        .field label {
            font-size: 0.88rem;
            color: var(--ink-muted);
            font-weight: 600;
        }
        .field select {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 0.75rem;
            padding: 0.65rem 0.85rem;
            font: inherit;
            color: var(--ink);
            min-width: 120px;
        }
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
        }
        .btn {
            appearance: none;
            border: 1px solid transparent;
            border-radius: 999px;
            padding: 0.7rem 1.2rem;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-primary {
            background: linear-gradient(145deg, var(--champaca), var(--champaca-deep));
            color: #fffdf5;
        }
        .btn-primary:hover { filter: brightness(1.03); }
        .btn-ghost {
            background: #fff;
            border-color: var(--line);
            color: var(--ink);
        }
        .btn-ghost:hover { border-color: var(--champaca); }
        .btn[disabled] {
            opacity: 0.55;
            cursor: wait;
        }

        .hint {
            margin-top: 0.95rem;
            color: var(--ink-muted);
            font-size: 0.92rem;
            line-height: 1.55;
        }

        .alert-success, .alert-info {
            margin-bottom: 1rem;
            padding: 0.85rem 1rem;
            border-radius: 0.85rem;
            font-size: 0.95rem;
            line-height: 1.5;
            animation: rise 0.35s ease both;
        }
        .alert-success {
            background: #eefbf1;
            border: 1px solid rgba(22, 101, 52, 0.18);
            color: #166534;
        }
        .alert-info {
            background: #fff9ef;
            border: 1px solid var(--line);
            color: var(--ink);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.1rem;
        }
        .stat {
            background: rgba(255, 252, 245, 0.9);
            border: 1px solid var(--line);
            border-radius: 0.95rem;
            padding: 0.9rem 1rem;
        }
        .stat .label {
            font-size: 0.82rem;
            color: var(--ink-muted);
            margin-bottom: 0.25rem;
        }
        .stat .value {
            font-family: 'Outfit', 'Sarabun', sans-serif;
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--champaca-deep);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem 0.85rem;
            text-align: left;
            border-bottom: 1px solid rgba(201, 146, 26, 0.12);
            vertical-align: top;
            font-size: 0.94rem;
        }
        th {
            font-size: 0.8rem;
            color: var(--ink-muted);
            background: rgba(230, 180, 34, 0.1);
        }
        tr:last-child td { border-bottom: 0; }
        .muted { color: var(--ink-muted); font-size: 0.86rem; }
        .empty {
            text-align: center;
            color: var(--ink-muted);
            padding: 1.5rem;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 800px) {
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .col-course { display: none; }
        }
@endsection

@section('content')
    @php
        $display = $result ?? $summary;
    @endphp

    <div class="page-head">
        <a class="back" href="{{ route('home') }}">← กลับเมนูหลัก</a>
        <h1>นำเข้าข้อมูลนักศึกษาจาก REG</h1>
    </div>

    @if (session('success'))
        <div class="alert-success" role="status">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <form class="import-form" method="GET" action="{{ route('research-fee.import') }}" id="preview-form">
            <div class="field">
                <label for="term">ภาคการศึกษา</label>
                <select name="term" id="term">
                    <option value="1" @selected($term === 1)>1</option>
                    <option value="2" @selected($term === 2)>2</option>
                </select>
            </div>
            <div class="field">
                <label for="year">ปีการศึกษา</label>
                <select name="year" id="year">
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected($year === $y)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="preview" value="1">
            <div class="actions">
                <button type="submit" class="btn btn-ghost" id="btn-preview">ตรวจสอบข้อมูล</button>
            </div>
        </form>

        <form method="POST" action="{{ route('research-fee.import.store') }}" id="import-form" class="actions" style="margin-top:0.85rem">
            @csrf
            <input type="hidden" name="term" value="{{ $term }}">
            <input type="hidden" name="year" value="{{ $year }}">
            <button
                type="submit"
                class="btn btn-primary"
                id="btn-import"
                @disabled(! $display || ($display['to_insert'] ?? 0) < 1)
                onclick="return confirm('ยืนยันนำเข้าข้อมูลภาค {{ $term }}/{{ $year }} หรือไม่?')"
            >
                ดึงข้อมูลและนำเข้า
            </button>
        </form>

        <p class="hint">
            ระบบจะดึงนักศึกษาระดับบัณฑิตศึกษา (โท–เอก) คณะวิทยาศาสตร์ ที่สถานะกำลังศึกษาในภาค/ปีที่เลือกจากฐานข้อมูล REG
            แล้วเพิ่มเฉพาะรายการใหม่ลงตารางค่าธรรมเนียมวิจัย โดยไม่ทับสถานะการชำระเงินที่มีอยู่แล้ว
        </p>
    </div>

    @if ($display)
        <div class="stats">
            <div class="stat">
                <div class="label">พบใน REG</div>
                <div class="value">{{ number_format($display['reg_total']) }}</div>
            </div>
            <div class="stat">
                <div class="label">มีในระบบแล้ว</div>
                <div class="value">{{ number_format($display['existing']) }}</div>
            </div>
            <div class="stat">
                <div class="label">รอเพิ่มใหม่</div>
                <div class="value">{{ number_format($display['to_insert']) }}</div>
            </div>
            <div class="stat">
                <div class="label">นำเข้าสำเร็จ</div>
                <div class="value">{{ number_format($display['inserted']) }}</div>
            </div>
        </div>

        <div class="panel">
            @if (($display['to_insert'] ?? 0) === 0)
                <div class="empty">ไม่มีรายการใหม่ที่ต้องนำเข้าสำหรับภาค {{ $display['term'] }}/{{ $display['year'] }}</div>
            @else
                <div class="alert-info">
                    ตัวอย่างรายการที่จะเพิ่ม (แสดงไม่เกิน 20 รายการจากทั้งหมด {{ number_format($display['to_insert']) }})
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>รหัสนักศึกษา</th>
                            <th>ชื่อ–สกุล</th>
                            <th>ระดับ</th>
                            <th class="col-course">หลักสูตร</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($display['preview'] as $row)
                            <tr>
                                <td>{{ $row['std_code'] }}</td>
                                <td>{{ $row['name'] }}</td>
                                <td>{{ $row['level'] }}</td>
                                <td class="col-course">{{ $row['couse'] ?: '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
@endsection

@push('scripts')
<script>
(() => {
    const previewForm = document.getElementById('preview-form');
    const importForm = document.getElementById('import-form');
    const btnPreview = document.getElementById('btn-preview');
    const btnImport = document.getElementById('btn-import');
    const termSelect = document.getElementById('term');
    const yearSelect = document.getElementById('year');

    const syncHidden = () => {
        if (!importForm) return;
        importForm.querySelector('input[name="term"]').value = termSelect.value;
        importForm.querySelector('input[name="year"]').value = yearSelect.value;
        if (btnImport) btnImport.disabled = true;
    };

    termSelect?.addEventListener('change', syncHidden);
    yearSelect?.addEventListener('change', syncHidden);

    previewForm?.addEventListener('submit', () => {
        btnPreview.disabled = true;
        btnPreview.textContent = 'กำลังตรวจสอบ...';
    });

    importForm?.addEventListener('submit', () => {
        if (btnImport) {
            btnImport.disabled = true;
            btnImport.textContent = 'กำลังนำเข้า...';
        }
    });
})();
</script>
@endpush
