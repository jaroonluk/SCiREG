<?php
    include "connect.php";
    date_default_timezone_set('asia/bangkok');
    $ReasonID = $_POST['reason'];
    $STUDENTID = $_POST['personID'];
    $COURSENAME = $_POST['courseid'];
    $DESC = $_POST['desc'];
    $Coursecode = substr($COURSENAME, 0, 6);
    $optionselect = $_POST['selectid']; //เลือกรหัสบัตรนศ. หรือ บัตรปชช.
    $email = $_POST['stdemail'];
    $tel = $_POST['stdtel'];
    //echo $Coursecode;
   // echo $optionselect;


    if ($optionselect == 1) {
        $sql_1 = "SELECT
                studentmaster.STUDENTID,
                studentmaster.STUDENTCODE,
                studentmaster.STUDENTNAME,
                studentmaster.STUDENTSURNAME,
                prefix.PREFIXNAME,
                program.PROGRAMNAME
            FROM
                studentmaster,
                prefix,
                program
            WHERE
                studentmaster.STUDENTCODE = '$STUDENTID'
            AND studentmaster.PREFIXID = prefix.PREFIXID
            AND program.PROGRAMID = studentmaster.PROGRAMID 
            order by  studentmaster.STUDENTCODE  desc  limit 1
            ";
        $result_1 = mysql_query($sql_1);
        $row_1 = mysql_fetch_array($result_1);
        $stdid = $row_1['STUDENTID'];
        $stdcode = $row_1['STUDENTCODE'];
        $stdname = $row_1['STUDENTNAME'];
        $stdlname = $row_1['STUDENTSURNAME'];
        $stdprefix = $row_1['PREFIXNAME'];
        $program = $row_1['PROGRAMNAME'];
    } elseif ($optionselect ==2) {
        $sql_1 = "SELECT
                    studentbio.CITIZENID,
                    studentmaster.STUDENTID,
                    studentmaster.STUDENTCODE,
                    studentmaster.STUDENTNAME,
                    studentmaster.STUDENTSURNAME,
                    prefix.PREFIXNAME,
                    program.PROGRAMNAME
                FROM
                    studentbio,
                    studentmaster,
                    prefix,
                    program
                WHERE
                    studentbio.CITIZENID = '$STUDENTID'
                AND studentbio.STUDENTID = studentmaster.STUDENTID
                AND studentmaster.PREFIXID = prefix.PREFIXID
                AND program.PROGRAMID = studentmaster.PROGRAMID
                 order by  studentbio.STUDENTID  desc  limit 1
                ";

        $result_1 = mysql_query($sql_1);
        $row_1 = mysql_fetch_array($result_1);
        $stdid = $row_1['STUDENTID'];
        $stdcode = $row_1['STUDENTCODE'];
        $stdname = $row_1['STUDENTNAME'];
        $stdlname = $row_1['STUDENTSURNAME'];
        $stdprefix = $row_1['PREFIXNAME'];
        $program = $row_1['PROGRAMNAME'];
    }



//
//    echo $sql_1;
//    exit;
    
    //อาจต้องปรับปรุงโค้ด select ตามโปรแกรมการจัดตารางสอบ
      /*  $sql_2 = "SELECT
                    discipline.tblexam_detail.SEMESTER,
                    discipline.tblexam_detail.EXAMCODE,
                    discipline.tblexam_detail.ACADYEAR,
                    discipline.tblexam.EXAMDATE,
                    discipline.tblexam.EXAMTIMEFROM,
                    discipline.tblroom.Room_Name
                FROM
                    discipline.tblexam_detail ,
                    discipline.tblroom ,
                    discipline.tblexam ,
                    reg.class ,
                    reg.course
                WHERE
                    tblexam_detail.Room_ID = tblroom.Room_ID AND
                    tblexam_detail.EXAMID = tblexam.EXAMID AND
                    tblexam_detail.CLASSID = class.CLASSID AND
                    tblexam_detail.COURSEID = course.COURSEID AND
                    course.COURSEID = class.COURSEID
                    AND STUDENTCODE = '$stdcode'
                    AND course.COURSECODE = '$Coursecode'";
                    */

      if($stdcode ==''){
          exit;
      }

                         $sql_2 = "SELECT
                    discipline.tblexam_detail.SEMESTER,
                    discipline.tblexam_detail.EXAMCODE,
                    discipline.tblexam_detail.ACADYEAR,
                    discipline.tblexam.EXAMDATE,
                    discipline.tblexam.EXAMTIMEFROM,
                    discipline.tblroom.Room_Name
                FROM
                    discipline.tblexam_detail ,
                    discipline.tblroom ,
                    discipline.tblexam ,
                    reg.class ,
                    reg.course
                WHERE
                    course.COURSEID = class.COURSEID
                    AND STUDENTCODE = '$stdcode'
                    AND course.COURSECODE = '$Coursecode'";

        $result_2 = mysql_query($sql_2);
        $row_2 = mysql_fetch_array($result_2);
        $CLASSID = $row_2['CLASSID'];
        $COURSEID = $row_2['COURSEID'];
        $ROOMID = $row_2['ROOMID'];
        $semester =$row_2['SEMESTER']; // ภาคต้น / ภาคปลาย
        $examcode = $row_2['EXAMCODE']; // F=Final,M=Midterm
        $acadyear = $row_2['ACADYEAR']; // ปีการศึกษา
        $examdate = $row_2['EXAMDATE']; //วันที่สอบ
        $examtime = $row_2['EXAMTIMEFROM']; //เวลาที่สอบ
        $ROOMCODE = $row_2['Room_Name']; // ห้องสอบ




    $sql_3 = "SELECT * FROM discipline.reason where REASONID = '$ReasonID'";
    $result_3 = mysql_query($sql_3);
    $row_3 = mysql_fetch_array($result_3);
    $reasonname = $row_3['ReasonName'];




    function DateThai($strDate)
    {
        $strYear = date("Y", strtotime($strDate))+543;
        $strMonth= date("n", strtotime($strDate));
        $strDay= date("j", strtotime($strDate));
        $strMonthCut = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
        $strMonthThai=$strMonthCut[$strMonth];
        return "วันที่  $strDay  เดือน  $strMonthThai  พ.ศ. $strYear";
    }
    $date = date('Y-m-d');





    $insertform = "INSERT INTO discipline.formlate(STUDENTID,ReasonID,CLASSID,COURSEID,ROOMID,DESCI,LATETIME)
    VALUES('$stdid','$ReasonID','$CLASSID','$COURSEID','$ROOMID','$DESC',NOW())";
    mysql_query($insertform) or die(mysql_error());
//Start generate fpdf
    require('fpdf.php');

    $pdf=new FPDF('P', 'cm', 'A4');
    $pdf->SetMargins(2.54, 2.54, 2.54);
    $pdf->AddPage();
    //กำหนดฟอนต์
    $pdf->AddFont('THSarabun', '', 'THSarabun.php');
    $pdf->AddFont('THSarabun', 'B', 'THSarabun Bold.php');
    $pdf->AddFont('THSarabun', 'I', 'THSarabun Italic.php');
    $pdf->AddFont('THSarabun', 'BI', 'THSarabun BoldItalic.php');


$pdf->SetFont('THSarabun', 'B', 18);
$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', 'การเข้าสอบช้า (เกิน 15 นาที แต่ไม่เกิน 30 นาที)'), 0, 1, 'C');

//เช็คว่าสอบกลางภาคหรือปลายภาค
/** เอาคอมเมนต์ออกหลังจาก select ข้อมูลการสอบครบ
if($examcode=='F'){
    $pdf->Cell( 0  , 1 , iconv( 'UTF-8','cp874' , ' (  /  )  กลางภาค   (    )  ปลายภาค' ) , 0 , 1 , 'C' );
}else if($examcode == 'M'){
    $pdf->Cell( 0  , 1 , iconv( 'UTF-8','cp874' , ' (  /  )  กลางภาค   (    )  ปลายภาค' ) , 0 , 1 , 'C' );
}
//เช็คว่าสอบเทอมต้นหรือเทอมปลาย
if($semester==1){
    $pdf->Cell( 0  , 1 , iconv( 'UTF-8','cp874' , 'ภาคการศึกษา   (  /  )  ต้น   (     )  ปลาย     ปีการศึกษา '. $acadyear ) , 0 , 1 , 'C' );
}else if($semester==2){
    $pdf->Cell( 0  , 1 , iconv( 'UTF-8','cp874' , 'ภาคการศึกษา   (   /  )  ต้น   (    )  ปลาย     ปีการศึกษา '. $acadyear ) , 0 , 1 , 'C' );
}
*/
$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', ' (   /  )  กลางภาค   (    )  ปลายภาค'), 0, 1, 'C');
    $pdf->Cell(0, 1, iconv('UTF-8', 'cp874', 'ภาคการศึกษา   (      )  ต้น   (   /   )  ปลาย     ปีการศึกษา 2568'/*. $acadyear */), 0, 1, 'C');
$pdf->SetFont('THSarabun', '', 16);
$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', ''), 0, 1);
$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', DateThai($date)), 0, 1, 'R');
$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', 'เวลา ' . date('H:i') . '  เลขที่นั่งสอบ............'), 0, 1, 'R');

$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', 'เรียน   กรรมการคุมสอบห้อง.....................'), 0, 1, 'L');
$pdf->MultiCell(0, 1, iconv('UTF-8', 'cp874', '       ด้วยนักศึกษาคณะวิทยาศาสตร์ คือ  ' . $stdprefix . $stdname .' '. $stdlname . '  รหัสประจำตัว ' . $stdcode . '  สาขาวิชา ' . $program . ' มาเข้าสอบช้าเกิน 15 นาที หลังจากเวลาที่เริ่มสอบแล้ว เนื่องจาก ' . $reasonname . $DESC));

$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', '         ประธานกรรมการสอบ ได้พิจารณาแล้ว'), 0, 1, 'L');
$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', '         (       )  นักศึกษามาเข้าสอบช้าด้วยเหตุสุดวิสัย จึงกำหนดให้นักศึกษาเข้าสอบวิชาดังกล่าว'), 0, 1, 'L');
$pdf->Cell(0, 1, iconv('UTF-8', 'cp874', '         (       )  ไม่อนุญาต เนื่องจาก ................................................................................................'), 0, 1, 'L');
$pdf->Cell(0, 3, iconv('UTF-8', 'cp874', '         จึงเรียนมาเพื่อโปรดทราบ และพิจารณาดำเนินการต่อไป'), 0, 1, 'L');
$pdf->Cell(0, 2, iconv('UTF-8', 'cp874', ''), 0, 1);
$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', '...................................................................'), 0, 1, 'C');
$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', '( รองศาสตราจารย์พิมพ์วดี พรพงศ์รุ่งเรือง )'), 0, 1, 'C');
//$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', '( ผู้ช่วยศาสตราจารย์คทาทัธ ตั้งกาญจนวง )'), 0, 1, 'C');
//$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', '(ผู้ช่วยศาสตราจารย์ชิณณวรรธน์ ตั้งกาญจนวงศ์'), 0, 1, 'C');
//$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', 'ตำแหน่ง รองคณบดีฝ่ายพัฒนานักศึกษาและศิษย์เก่าสัมพันธ์'), 0, 1, 'C');
$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', 'ตำแหน่ง รองคณบดีฝ่ายวิชาการ'), 0, 1, 'C');
//$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', 'ตำแหน่ง รองคณบดีฝ่ายพัฒนานักศึกษา และศิษย์เก่าสัมพันธ์'), 0, 1, 'C');
$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', 'ปฏิบัติการแทนคณบดีคณะวิทยาศาสตร์'), 0, 1, 'C');
$pdf->Cell(25, 1, iconv('UTF-8', 'cp874', 'วันที่ .............. เดือน ................ พ.ศ................'), 0, 1, 'C');

$pdf->Output();
