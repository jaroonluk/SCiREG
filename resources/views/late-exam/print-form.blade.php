<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>แบบฟอร์มการเข้าสอบช้า</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 portrait; margin: 18mm 20mm; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'TH Sarabun New', 'Sarabun', sans-serif;
            color: #111;
            background: #e8e4da;
            font-size: 16pt;
            line-height: 1.45;
        }
        .toolbar {
            position: sticky; top: 0; z-index: 10;
            display: flex; gap: .5rem; flex-wrap: wrap; align-items: center;
            padding: .75rem 1rem;
            background: rgba(255,252,245,.95);
            border-bottom: 1px solid rgba(201,146,26,.25);
        }
        .toolbar button, .toolbar a {
            appearance: none; border: 1px solid rgba(201,146,26,.35);
            background: #fff; color: #2a2214; border-radius: 999px;
            padding: .45rem 1rem; font: 600 .9rem 'Sarabun', sans-serif;
            cursor: pointer; text-decoration: none;
        }
        .toolbar .primary {
            background: linear-gradient(145deg, #e6b422, #c9921a);
            border-color: transparent; color: #fff;
        }
        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 1rem auto;
            background: #fff;
            padding: 22mm 20mm;
            box-shadow: 0 12px 40px -24px rgba(0,0,0,.35);
        }
        .sheet + .sheet { page-break-before: always; }
        .title {
            text-align: center; font-weight: 700; font-size: 18pt; margin-bottom: .35rem;
        }
        .center { text-align: center; margin: .15rem 0; }
        .right { text-align: right; margin: .2rem 0; }
        .body { margin-top: 1rem; text-align: justify; text-indent: 2.5em; }
        .line { margin: .35rem 0; }
        .indent { padding-left: 2em; margin: .25rem 0; }
        .sign {
            margin-top: 2.2rem; text-align: center; line-height: 1.35;
        }
        .sign .blank { margin-top: 1.6rem; }
        .mark { font-family: inherit; }
        @media print {
            body { background: #fff; }
            .toolbar { display: none; }
            .sheet {
                width: auto; min-height: auto; margin: 0; padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="primary" type="button" onclick="window.print()">พิมพ์</button>
        <a href="{{ route('late-exam.print') }}">กลับรายการพิมพ์</a>
        <a href="{{ route('late-exam.signers') }}">กำหนดผู้ลงนาม</a>
    </div>

    @foreach($records as $row)
        @php
            $when = $row->LATETIME ?? now();
            $isMid = strtoupper((string) $row->EXAM_TYPE) === 'M';
            $isFinal = strtoupper((string) $row->EXAM_TYPE) === 'F';
            $sem1 = (int) $row->SEMESTER === 1;
            $sem2 = (int) $row->SEMESTER === 2;
            $reasonText = trim(($row->REASON_NAME ?? '').' '.($row->DESCI ?? ''));
            $yearBe = $row->ACADYEAR ?: ((int) $when->format('Y') + 543);
        @endphp
        <article class="sheet">
            <div class="title">การเข้าสอบช้า (เกิน 15 นาที แต่ไม่เกิน 30 นาที)</div>
            <div class="center mark">
                ( {!! $isMid ? '/' : '&nbsp;&nbsp;' !!} )&nbsp;&nbsp;กลางภาค
                &nbsp;&nbsp;&nbsp;
                ( {!! $isFinal ? '/' : '&nbsp;&nbsp;' !!} )&nbsp;&nbsp;ปลายภาค
            </div>
            <div class="center mark">
                ภาคการศึกษา
                &nbsp;( {!! $sem1 ? '/' : '&nbsp;&nbsp;' !!} )&nbsp;ต้น
                &nbsp;&nbsp;( {!! $sem2 ? '/' : '&nbsp;&nbsp;' !!} )&nbsp;ปลาย
                &nbsp;&nbsp;&nbsp;ปีการศึกษา {{ $yearBe }}
            </div>

            <div class="right" style="margin-top:1rem">
                {{ \App\Services\LateExamService::dateThai($when) }}
            </div>
            <div class="right">
                เวลา {{ $when->format('H:i') }}
                &nbsp;&nbsp;เลขที่นั่งสอบ............
            </div>

            <div class="line" style="margin-top:1rem">
                เรียน&nbsp;&nbsp;กรรมการคุมสอบห้อง {{ $row->ROOM_NAME ?: '.....................' }}
            </div>

            <p class="body">
                ด้วยนักศึกษาคณะวิทยาศาสตร์ คือ
                {{ $row->STUDENT_NAME ?: '................................' }}
                รหัสประจำตัว {{ $row->STUDENTCODE ?: '............' }}
                สาขาวิชา {{ $row->DEPARTMENT_NAME ?: '................' }}
                @if($row->COURSE_CODE)
                    วิชา {{ $row->COURSE_CODE }}{{ $row->COURSE_NAME ? ' '.$row->COURSE_NAME : '' }}
                @endif
                มาเข้าสอบช้าเกิน 15 นาที หลังจากเวลาที่เริ่มสอบแล้ว เนื่องจาก
                {{ $reasonText !== '' ? $reasonText : '................................' }}
            </p>

            <div class="indent" style="margin-top:1rem">ประธานกรรมการสอบ ได้พิจารณาแล้ว</div>
            <div class="indent">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )&nbsp;&nbsp;นักศึกษามาเข้าสอบช้าด้วยเหตุสุดวิสัย จึงกำหนดให้นักศึกษาเข้าสอบวิชาดังกล่าว</div>
            <div class="indent">( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )&nbsp;&nbsp;ไม่อนุญาต เนื่องจาก ................................................................................................</div>
            <div class="indent" style="margin-top:.8rem">จึงเรียนมาเพื่อโปรดทราบ และพิจารณาดำเนินการต่อไป</div>

            <div class="sign">
                <div class="blank">...................................................................</div>
                @if($signer)
                    <div>{{ $signer['signature_name'] }}</div>
                    <div>{{ $signer['position'] ?: '' }}</div>
                    <div>{{ $signer['role_label'] }}</div>
                @else
                    <div>( ........................................................ )</div>
                    <div>ตำแหน่ง ........................................................</div>
                    <div>ปฏิบัติการแทนคณบดีคณะวิทยาศาสตร์</div>
                @endif
                <div style="margin-top:.6rem">วันที่ .............. เดือน ................ พ.ศ................</div>
            </div>
        </article>
    @endforeach
</body>
</html>
