<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title>รายงานค่าธรรมเนียมวิจัย {{ $filters['term'] }}/{{ $filters['year'] }}</title>
    <link rel="icon" type="image/png" href="{{ asset('faculty-logo-cut.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('faculty-logo-cut.png') }}">
    <style>
        @page { size: A4 landscape; margin: 12mm; }
        body { font-family: Tahoma, "Sarabun", sans-serif; color:#111; font-size:12px; background: #fffdf7; }
        .toolbar { display:flex; gap:8px; margin-bottom:14px; }
        button {
            padding:8px 14px; cursor:pointer; border-radius: 8px; font-weight: 600;
            border: 1px solid rgba(201,146,26,.35); background: #fffef9; color: #2a2214;
        }
        button.primary {
            background: linear-gradient(145deg, #f0c94a, #c9921a);
            border-color: transparent; color: #fffdf5;
        }
        h1,h2 { text-align:center; margin:3px; }
        h1 { font-size:19px; color: #8a6510; } h2 { font-size:15px; font-weight:normal; }
        .meta { text-align:center; margin:10px 0 14px; color: #6b5d45; }
        table { width:100%; border-collapse:collapse; }
        th,td { border:1px solid #c9a86a; padding:5px 6px; vertical-align:top; }
        th { background:#f7e7b8; text-align:center; color: #8a6510; }
        .num { text-align:right; white-space:nowrap; }
        .summary { margin-top:14px; display:grid; grid-template-columns:repeat(5,1fr); gap:8px; }
        .summary div { border:1px solid #e6b422; padding:8px; border-radius:8px; background: #fff8e8; }
        @media print { .toolbar { display:none; } body { background: #fff; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="primary" onclick="window.print()">พิมพ์รายงาน</button>
        <button onclick="window.close()">ปิด</button>
    </div>
    <h1>รายงานการชำระเงินค่าธรรมเนียมวิจัย</h1>
    <h2>สำหรับนักศึกษาระดับบัณฑิตศึกษา คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น</h2>
    <div class="meta">
        ภาคการศึกษา {{ $filters['term'] }} ปีการศึกษา {{ $filters['year'] }}
        @if($filters['depart_id'] > 0)
            · {{ optional($departments->firstWhere('depart_id',$filters['depart_id']))->depart_name }}
        @else · ทุกหน่วยงาน @endif
    </div>
    <table>
        <thead><tr><th>#</th><th>รหัสนักศึกษา</th><th>ชื่อ–สกุล</th><th>ระดับ</th><th>หลักสูตร</th><th>หน่วยงาน</th><th>สถานะ</th><th>จำนวนเงิน</th><th>เลขที่ใบเสร็จ</th></tr></thead>
        <tbody>
        @foreach($rows as $index => $row)
            <tr>
                <td class="num">{{ $index+1 }}</td>
                <td>{{ $row->std_code }}</td><td>{{ $row->name }}</td><td>{{ $row->level }}</td>
                <td>{{ $row->couse }}</td><td>{{ $row->depart_name }}</td>
                <td>{{ ['1'=>'ค้างชำระ','2'=>'ได้รับการยกเว้น','3'=>'ชำระแล้ว'][$row->status] ?? 'ไม่ระบุ' }}</td>
                <td class="num">{{ $row->status==='3' ? number_format($row->amount,2) : '—' }}</td>
                <td>{{ $row->slip_no ?: '—' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="summary">
        <div>ทั้งหมด<br><strong>{{ number_format($summary->total ?? 0) }} คน</strong></div>
        <div>ค้างชำระ<br><strong>{{ number_format($summary->pending ?? 0) }} คน</strong></div>
        <div>ได้รับยกเว้น<br><strong>{{ number_format($summary->exempt ?? 0) }} คน</strong></div>
        <div>ชำระแล้ว<br><strong>{{ number_format($summary->paid ?? 0) }} คน</strong></div>
        <div>ยอดรับรวม<br><strong>{{ number_format($summary->received ?? 0,2) }} บาท</strong></div>
    </div>
</body>
</html>
