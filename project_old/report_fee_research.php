<?php header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="report.xls"');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Report</title>
</head>

<body>

<p>&nbsp;</p>
<p align="center"><strong>การชำระเงินค่าะรรมเนียมวิจัย</strong></p>
<p align="center"><strong>สำหรับนักศึกษาระดับบัณฑิตศึกษา คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น</strong></p>
<p><br>
</p>
<table width="1004" border="1" cellspacing="0" cellpadding="0">
      <tr>
        <td width="51" height="27" bgcolor="#CCCCCC"><div align="center" class="style2">ลำดับ</div></td>
        <td width="139" bgcolor="#CCCCCC"><div align="center" class="style2">รหัสนักศึกษา</div></td>
        <td width="140" bgcolor="#CCCCCC"><div align="center" class="style2">ชื่อ-สกุล</div></td>
        <td width="61" bgcolor="#CCCCCC"><div align="center" class="style2">ระดับ</div></td>
        <td width="205" bgcolor="#CCCCCC"><div align="center" class="style2">หลักสูตร</div></td>
        <td width="181" bgcolor="#CCCCCC"><div align="center" class="style2">ภาควิชา</div></td>
        <td width="211" bgcolor="#CCCCCC"><div align="center" class="style2">สถานะภาพการชำระเงิน</div></td>
         <td width="211" bgcolor="#CCCCCC"><div align="center" class="style2">เลขที่ใบเสร็จ</div></td>
      
      </tr>
      
     <?
	 
	  
     include("conn.php"); 
	 function getDpart($id){
       $sql = "SELECT * FROM depart_fee_research  where depart_id=$id   ";
	   $rs=mysql_query($sql);
	   $data = mysql_fetch_array($rs);
	   return $data['depart_name'];
   }
	 
	    if($_GET['dpartid']==0){
		
		$sql = "SELECT * FROM fee_research  where term='$_GET[term]' and year='$_GET[year]'
		           ORDER BY depart_id ASC , level ASC , couse ASC , std_code ASC ";
		
		}else{
		$sql = "SELECT * FROM fee_research  where term='$_GET[term]' and year='$_GET[year]'
		           and depart_id=$_GET[dpartid]
		           ORDER BY   level ASC , couse ASC   , std_code ASC ";
		
		}
		//echo $sql;
		$j=1;
		$yy[1] ="ค้างชำระ";
		$yy[2] ="ได้รับการยกเว้น";
		$yy[3] ="ชำระแล้วจำนวน";
		
	 $result=mysql_query($sql);
	  while($data = mysql_fetch_array($result)){
	 ?> 
     
      <tr>
        <td><div align="center"><? echo $j;?></div></td>
        <td><? echo $data['std_code'] ;?></td>
        <td><? echo $data['name'] ;?></td>
        <td><div align="center"><? echo $data['level'] ;?></div></td>
        <td><? echo $data['couse'] ;?></td>
        <td><? echo getDpart($data['depart_id']) ;?></td>
        <td>
         
          <div align="center"><? echo $yy[$data['status']];?>  
            
              
           <? if($data['amount']!=0){?>  <? echo $data['amount'] ;?>  บาท <? }?>  
            
          </div></td>
       <td><? echo $data['slip_no'] ;?></td>
      </tr>
  
      <?  $j++; }?>
</table>
    </div>
  <label>
  <?
  if($_GET['dpartid']==0){
  //count นักศึกษาที่ค้างชำระเงิน
    $result=mysql_query("SELECT count(*) as total from fee_research  where term='$_GET[term]' and year='$_GET[year]' and status='1' ");
  $data=mysql_fetch_assoc($result);
   $aa1=   $data['total'];
   
      
   //count นักศึกษาที่ได้รับการยกเว้น
    $result=mysql_query("SELECT count(*) as total from fee_research  where term='$_GET[term]' and year='$_GET[year]' and status='2' ");
  $data=mysql_fetch_assoc($result);
   $aa2=   $data['total'];
   
   //count นักศึกษาที่ค้างชำระเงิน
    $result=mysql_query("SELECT count(*) as total from fee_research  where term='$_GET[term]' and year='$_GET[year]' and status='3' ");
  $data=mysql_fetch_assoc($result);
   $aa3=   $data['total'];
   
   
   $result=mysql_query("SELECT sum(amount) as total from fee_research  where term='$_GET[term]' and year='$_GET[year]' and status='3'  ");
  $data=mysql_fetch_assoc($result);
   $aa4=   $data['total'];
   
   }else{
   
    //count นักศึกษาที่ค้างชำระเงิน
    $result=mysql_query("SELECT count(*) as total from fee_research  where term='$_GET[term]' and year='$_GET[year]' and status='1' and depart_id=$dpartid ");
  $data=mysql_fetch_assoc($result);
   $aa1=   $data['total'];
   
      
   //count นักศึกษาที่ได้รับการยกเว้น
    $result=mysql_query("SELECT count(*) as total from fee_research  where term='$_GET[term]' and year='$_GET[year]' and status='2'  and depart_id=$dpartid ");
  $data=mysql_fetch_assoc($result);
   $aa2=   $data['total'];
   
   //count นักศึกษาที่ค้างชำระเงิน
    $result=mysql_query("SELECT count(*) as total from fee_research  where term='$_GET[term]' and year='$_GET[year]' and status='3' and depart_id=$dpartid ");
  $data=mysql_fetch_assoc($result);
   $aa3=   $data['total'];
   
    $result=mysql_query("SELECT sum(amount) as total from fee_research  where term='$_GET[term]' and year='$_GET[year]' and status='3' and depart_id=$dpartid ");
  $data=mysql_fetch_assoc($result);
   $aa4=   $data['total'];
   
   
   
   }
  ?>
  
  <p><strong>สรุป:</strong> จำนวนนักศึกษาทั้งหมด &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $j-1; ?>&nbsp;คน</p>
  <p>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;นักศึกษาที่ค้างชำระเงิน&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $aa1;?>&nbsp;คน </p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;นักศึกษาที่ได้รับการยกเว้น &nbsp;&nbsp;&nbsp;&nbsp;<? echo $aa2;?>&nbsp;คน</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; นักศึกษาชำระเงินแล้ว&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo $aa3;?>&nbsp;คน</p>
  <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จำนวนเงินที่ได้รับทั้งหมด&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo number_format( $aa4 , 2 );?>&nbsp;บาท</p>
<p>&nbsp;</p>

</body>
</html>
