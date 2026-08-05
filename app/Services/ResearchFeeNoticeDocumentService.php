<?php

namespace App\Services;

use Carbon\CarbonInterface;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use ZipArchive;

class ResearchFeeNoticeDocumentService
{
    public function __construct(
        private readonly DocumentSignerService $signerService
    ) {}

    /**
     * @return array{path:string,filename:string}
     */
    public function makeSponsorNotice(object $student, CarbonInterface $date): array
    {
        $template = $this->sponsorTemplatePath();
        $path = $this->tempDocxPath('sponsor');
        copy($template, $path);

        $zip = new ZipArchive;
        if ($zip->open($path) !== true) {
            throw new \RuntimeException('Unable to open sponsor notice template.');
        }

        $xml = $zip->getFromName('word/document.xml');
        if ($xml === false) {
            $zip->close();
            throw new \RuntimeException('Sponsor notice template is missing document.xml.');
        }

        $level = $student->level === 'เอก' ? 'เอก' : 'โท';
        $branch = $this->branchName($student);
        $name = trim((string) $student->name);
        $thaiDate = $this->thaiDate($date);
        $signer = $this->signerService->activeSigner();

        $xml = str_replace('มยุรี สมปุย', $this->xmlText($name), $xml);
        $xml = str_replace('5 สิงหาคม 2569', $this->xmlText($thaiDate), $xml);
        $xml = str_replace(
            ' สาขาวิชา คณะวิทยาศาสตร์ เป็นผู้ได้รับทุนการศึกษาจาก',
            ' สาขาวิชา '.$this->xmlText($branch).' คณะวิทยาศาสตร์ เป็นผู้ได้รับทุนการศึกษาจาก',
            $xml
        );
        $xml = str_replace('<w:t>เอก</w:t>', '<w:t>'.$this->xmlText($level).'</w:t>', $xml);

        if ($signer) {
            $xml = str_replace('พิมพ์วดี พรพงศ์รุ่งเรือง', $this->xmlText($signer['full_name']), $xml);
            $xml = str_replace('<w:t>รอง</w:t>', '<w:t></w:t>', $xml);
            $xml = str_replace('<w:t>ศาสตราจารย์</w:t>', '<w:t></w:t>', $xml);
            $xml = str_replace('รองคณบดีฝ่ายวิชาการ', $this->xmlText($signer['position'] ?: '—'), $xml);
            $xml = str_replace(
                'ปฏิบัติการแทนคณบดีคณะวิทยาศาสตร์',
                $this->xmlText($signer['role_label']),
                $xml
            );
        }

        $zip->addFromString('word/document.xml', $xml);
        $zip->close();

        return [
            'path' => $path,
            'filename' => 'notice-sponsor-'.$student->std_code.'.docx',
        ];
    }

    /**
     * @return array{path:string,filename:string}
     */
    public function makeStudentNotice(object $student, CarbonInterface $date): array
    {
        $level = $student->level === 'เอก' ? 'เอก' : 'โท';
        $fee = $level === 'เอก' ? 80000 : 50000;
        $feeText = $level === 'เอก' ? 'แปดหมื่นบาทถ้วน' : 'ห้าหมื่นบาทถ้วน';
        $semester = (string) $student->term === '1' ? 'ต้น' : 'ปลาย';
        $departLabel = trim((string) ($student->depart_name ?: 'คณะวิทยาศาสตร์'));
        $signer = $this->signerService->activeSigner();

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('TH Sarabun New');
        $phpWord->setDefaultFontSize(16);

        $section = $phpWord->addSection([
            'marginTop' => 700,
            'marginRight' => 1200,
            'marginBottom' => 900,
            'marginLeft' => 1200,
        ]);

        $logo = public_path('kku-emblem.png');
        if (is_file($logo)) {
            $section->addImage($logo, [
                'width' => 64,
                'height' => 113,
                'alignment' => Jc::CENTER,
            ]);
        }

        $section->addText('คณะวิทยาศาสตร์', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
        $section->addText('มหาวิทยาลัยขอนแก่น', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER, 'spaceAfter' => 0]);
        $section->addText('จังหวัดขอนแก่น 40002', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER, 'spaceAfter' => 200]);

        $meta = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $meta->addRow();
        $meta->addCell(5000)->addText('ที่ อว 660301.1/', ['size' => 16]);
        $meta->addCell(5000)->addText($this->thaiDate($date), ['size' => 16], ['alignment' => Jc::END]);

        $section->addTextBreak(1);

        $fields = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        foreach ([
            ['เรื่อง', 'เรียกเก็บค่าธรรมเนียมการวิจัยตามหลักสูตรระดับบัณฑิตศึกษา'],
            ['เรียน', trim((string) $student->name)],
            ['สังกัด', $departLabel.' มหาวิทยาลัยขอนแก่น'],
        ] as [$label, $value]) {
            $row = $fields->addRow();
            $row->addCell(1400)->addText($label, ['bold' => true, 'size' => 16]);
            $row->addCell(8600)->addText($value, ['size' => 16]);
        }

        $section->addTextBreak(1);

        $body1 = 'ตามประกาศคณะวิทยาศาสตร์ ฉบับที่ 74/2562 เรื่อง การเก็บค่าธรรมเนียมวิจัยตามหลักสูตรระดับบัณฑิตศึกษา '
            .'นักศึกษาระดับปริญญา'.$level.'ต้องชำระค่าธรรมเนียมการวิจัยตามหลักสูตร ภาคการศึกษาละ '
            .number_format($fee).' บาท จากการตรวจสอบพบว่าท่านยังค้างชำระค่าธรรมเนียมดังกล่าว '
            .'ในภาคการศึกษาที่ '.$student->term.' (ภาค'.$semester.') ปีการศึกษา '.$student->year
            .' เป็นจำนวนเงิน '.number_format($fee).' บาท ('.$feeText.')';

        $body2 = 'จึงขอให้ท่านติดต่อขอชำระค่าธรรมเนียมการวิจัยฯ ที่หน่วยการเงินและบัญชี คณะวิทยาศาสตร์ '
            .'เพื่อให้การจัดเก็บค่าธรรมเนียมเป็นไปด้วยความถูกต้อง หากไม่ดำเนินการ คณะฯ จะถือว่าท่านมีหนี้สินค้างชำระ '
            .'ค่าธรรมเนียมการวิจัยตามหลักสูตรระดับบัณฑิตศึกษา';

        $section->addText($body1, ['size' => 16], [
            'alignment' => Jc::BOTH,
            'indentation' => ['firstLine' => 720],
            'spaceAfter' => 120,
        ]);
        $section->addText($body2, ['size' => 16], [
            'alignment' => Jc::BOTH,
            'indentation' => ['firstLine' => 720],
            'spaceAfter' => 120,
        ]);
        $section->addText('จึงเรียนมาเพื่อโปรดทราบ', ['size' => 16], [
            'indentation' => ['firstLine' => 720],
            'spaceAfter' => 200,
        ]);

        $section->addText('ขอแสดงความนับถือ', ['size' => 16], ['alignment' => Jc::CENTER, 'indentation' => ['left' => 3500], 'spaceAfter' => 400]);

        if ($signer) {
            $section->addText($signer['signature_name'], ['size' => 16], ['alignment' => Jc::CENTER, 'indentation' => ['left' => 3500], 'spaceAfter' => 0]);
            $section->addText($signer['position'] ?: '—', ['size' => 16], ['alignment' => Jc::CENTER, 'indentation' => ['left' => 3500], 'spaceAfter' => 0]);
            $section->addText($signer['role_label'], ['size' => 16], ['alignment' => Jc::CENTER, 'indentation' => ['left' => 3500], 'spaceAfter' => 300]);
        } else {
            $section->addText('(ยังไม่ได้กำหนดผู้ลงนาม)', ['size' => 16], ['alignment' => Jc::CENTER, 'indentation' => ['left' => 3500], 'spaceAfter' => 0]);
            $section->addText('กรุณากำหนดที่เมนูผู้บริหารลงนาม', ['size' => 14], ['alignment' => Jc::CENTER, 'indentation' => ['left' => 3500], 'spaceAfter' => 300]);
        }

        $section->addText('งานบริการการศึกษา คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น', ['size' => 14], ['spaceAfter' => 0]);
        $section->addText('โทรศัพท์ 084-6001149', ['size' => 14], ['spaceAfter' => 0]);

        $path = $this->tempDocxPath('student');
        IOFactory::createWriter($phpWord, 'Word2007')->save($path);

        return [
            'path' => $path,
            'filename' => 'notice-student-'.$student->std_code.'.docx',
        ];
    }

    private function sponsorTemplatePath(): string
    {
        $bundled = resource_path('templates/research-fee/sponsor-notice.docx');
        if (is_file($bundled) && filesize($bundled) > 0) {
            return $bundled;
        }

        $cached = storage_path('app/templates/sponsor-notice.docx');
        if (is_file($cached) && filesize($cached) > 0) {
            return $cached;
        }

        $dir = dirname($cached);
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        foreach (glob(base_path('project_old/*.docx')) ?: [] as $file) {
            if (str_starts_with(basename($file), '~$')) {
                continue;
            }
            copy($file, $cached);

            return $cached;
        }

        throw new \RuntimeException('Sponsor notice template file was not found.');
    }

    private function branchName(object $student): string
    {
        $branch = trim((string) ($student->depart_name ?: $student->couse ?: ''));
        $branch = preg_replace('/^สาขาวิชา\s*/u', '', $branch) ?: '—';

        return $branch;
    }

    private function thaiDate(CarbonInterface $date): string
    {
        $months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม',
        ];

        return $date->day.' '.$months[(int) $date->month].' '.($date->year + 543);
    }

    private function xmlText(string $value): string
    {
        return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function tempDocxPath(string $prefix): string
    {
        $dir = storage_path('app/temp');
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (! is_writable($dir)) {
            $dir = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR);
        }

        return $dir.DIRECTORY_SEPARATOR.$prefix.'-'.uniqid('', true).'.docx';
    }
}
