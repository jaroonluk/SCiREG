<?php
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="report.xls"');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Report</title>
</head>

<body>
<?php include("conn.php"); ?>
<p>&nbsp;</p>
<p align="center"><strong>รายงาน การชำระเงินค่าธรรมเนียมวิจัย</strong></p>
<p align="center"><strong>สำหรับนักศึกษาระดับบัณฑิตศึกษา คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น</strong></p>
<p>&nbsp;</p>

<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <th width="227" rowspan="2" scope="col"><div align="center">สาขาวิชา</div></th>
    <th width="292" rowspan="2" scope="col"><p align="center">นักศึกษาแยกตาม</p>
        <p align="center">รหัสประจำตัว</p></th>
    <th colspan="4" scope="col"><div align="center">จำนวนนักศึกษา</div></th>
    <th width="183" rowspan="2" scope="col"><p align="center">จำนวนเงินที่</p>
        <p align="center">รับชำระแล้ว</p></th>
  </tr>
  <tr>
    <td width="103"><div align="center">ทั้งหมด</div></td>
    <td width="109"><div align="center">ที่ชำระ</div></td>
    <td width="140"><div align="center">ที่ขอยกเว้น</div></td>
    <td width="104"><div align="center">ที่คงค้าง</div></td>
  </tr>
  <?php  
     
	 if($_GET['dpartid']==0){
     $sql= "SELECT * FROM depart_fee_research   ";
	 }else{
	 $sql= "SELECT * FROM depart_fee_research where  depart_id =$_GET[dpartid]  ";
	 }
	 
	 $result = mysql_query($sql);
	 
	 $a=0;
	 $b=0;
	 $c=0;
	 $d=0;
	 $e=0;
	 
	 
	 
	 while($ds = mysql_fetch_array($result)){
	$dpartid  =    $ds['depart_id'];
	 
	  //get min id 
	   	$sql = "SELECT MIN(std_code) as stdidmin ,MAX(std_code) as stdidmax    FROM fee_research  where term='$_GET[term]' and year='$_GET[year]' and depart_id=$dpartid    ";
		
		
		
		 $result1=mysql_query($sql);
		 $data = mysql_fetch_array($result1);
		$stdidmin = substr($data['stdidmin'], 0, 2);
		$stdidmax = substr($data['stdidmax'], 0, 2);
		$totalrow= $stdidmax-$stdidmin ;
		
		
		
		for( $i=$stdidmin; $i<$stdidmax;$i++){
		
		
		//get total std
		  	$sql = "SELECT count(std_code) as stdidcount      FROM fee_research  where term='$_GET[term]' and year='$_GET[year]' and depart_id=$dpartid and  std_code like '$i%'  ";
		 $result2=mysql_query($sql);
		 $data2 = mysql_fetch_array($result2);
		 
		 
		 	$sql = "SELECT count(std_code) as stdidcount      FROM fee_research  where term='$_GET[term]' and year='$_GET[year]' and depart_id=$dpartid  and  std_code like '$i%' and  status = '3'  ";
		 $result3=mysql_query($sql);
		 $data3 = mysql_fetch_array($result3);
		 
		 	$sql = "SELECT count(std_code) as stdidcount      FROM fee_research  where term='$_GET[term]' and year='$_GET[year]' and depart_id=$dpartid  and  std_code like '$i%'  and  status = '2'  ";
		 $result4=mysql_query($sql);
		 $data4 = mysql_fetch_array($result4);
		 
		 $sql = "SELECT count(std_code) as stdidcount      FROM fee_research  where term='$_GET[term]' and year='$_GET[year]' and depart_id=$dpartid and  std_code like '$i%' and  status = '1'  ";
		 $result5=mysql_query($sql);
		 $data5 = mysql_fetch_array($result5);
		 
		  	$sql = "SELECT sum(amount) as stdidamount     FROM fee_research  where term='$_GET[term]' and year='$_GET[year]' and depart_id=$dpartid  and  std_code like '$i%' and  status = '3'  ";
		 $result6=mysql_query($sql);
		 $data6 = mysql_fetch_array($result6);
		
 ?>
  <?php if( $i==$stdidmin) { ?>
  <tr>
    <td rowspan="<?php echo   $totalrow;?>"><?php echo   $ds['depart_name'];?></td>
    <td><?php echo   "รหัส ".$i; ?></td>
    <td><div align="center"><?php echo   $data2['stdidcount']; $a=$a+ $data2['stdidcount']; ?></div></td>
    <td><div align="center"><?php echo   $data3['stdidcount']; $b=$b+ $data3['stdidcount']; ?></div></td>
    <td><div align="center"><?php echo   $data4['stdidcount']; $c=$c+ $data4['stdidcount']; ?></div></td>
    <td><div align="center"><?php echo   $data5['stdidcount']; $d=$d+ $data5['stdidcount']; ?></div></td>
    <td><div align="center"><?php echo   $data6['stdidamount']; $e=$e+ $data6['stdidamount']; ?></div></td>
  </tr>
  <?php }else {?>
  <tr>
    <td ><?php echo   "รหัส ".$i; ?></td>
    <td><div align="center"><?php echo   $data2['stdidcount']; $a=$a+ $data2['stdidcount']; ?></div></td>
    <td><div align="center"><?php echo   $data3['stdidcount']; $b=$b+ $data3['stdidcount']; ?></div></td>
    <td><div align="center"><?php echo   $data4['stdidcount']; $c=$c+ $data4['stdidcount']; ?></div></td>
    <td><div align="center"><?php echo   $data5['stdidcount']; $d=$d+ $data5['stdidcount']; ?></div></td>
    <td><div align="center"><?php echo   $data6['stdidamount']; $e=$e+ $data6['stdidamount']; ?></div></td>
  </tr>
  <?php } } ?>
  <?php } ?>
  <tr>
    <td colspan="2"><div align="center"><strong>รวม</strong></div></td>
    <td><div align="center"><strong><?php echo $a;?></strong></div></td>
    <td><div align="center"><strong><?php echo $b;?></strong></div></td>
    <td><div align="center"><strong><?php echo $c;?></strong></div></td>
    <td><div align="center"><strong><?php echo $d;?></strong></div></td>
    <td><div align="center"><strong><?php echo $e;?></strong></div></td>
  </tr>
</table>
</body>
</html>
