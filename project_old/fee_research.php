
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />

<?  include("conn.php"); 

function getDpart($id){
       $sql = "SELECT * FROM depart_fee_research  where depart_id=$id   ";
	   $rs=mysql_query($sql);
	   $data = mysql_fetch_array($rs);
	   return $data['depart_name'];
   }
?>

<style type="text/css">
<!--
.style2 {color: #000000; font-weight: bold; }
-->
</style>
<p>&nbsp;</p>
<p align="center"><strong>การชำระเงินค่าธรรมเนียมวิจัย</strong></p>
<p align="center"><strong>สำหรับนักศึกษาระดับบัณฑิตศึกษา คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น</strong></p>
<form id="form1" name="form1" method="get" action="">
  <div align="center"></div>
  
    <div align="center">ปีการศึกษา &nbsp;

      <select name="year" id="year">
         <? for($i=2557 ;$i<=2570;$i++){?>
         <option value="<? echo $i; ?>" <? if($_GET['year']==$i){?> selected="selected" <? }?> ><? echo $i; ?></option>
         <? }?>
          </select>
&nbsp;  ภาคการศึกษา
<select name="term" id="term">
  <option value="1" <? if($_GET['term']==1){?> selected="selected" <? }?>>1</option>
  <option value="2" <? if($_GET['term']==2){?> selected="selected" <? }?>>2</option>
</select>
&nbsp; &nbsp;&nbsp;&nbsp;หน่วยงาน
<select name="dpartid" id="dpartid">
  <option value="0">ทั้งหมด</option>
  <?
     $sql= "SELECT * FROM depart_fee_research   ";
	 $result = mysql_query($sql);
	 
	 while($ds = mysql_fetch_array($result)){
 ?>
  
  <option value="<? echo $ds['depart_id'];?>" <? if($_GET['dpartid']==$ds['depart_id']){?> selected="selected" <? }?> ><? echo $ds['depart_name'];?></option>
  <?  } ?>
</select>

<input type="submit" name="button" id="button" value="แสดง" />
    </div>
</form>
 
  <p>&nbsp;</p>
  <p><a href="report_fee_research.php?dpartid=<? echo $_GET['dpartid'];?>&term=<? echo $_GET['term'] ;?>&year=<? echo $_GET['year'] ;?>">พิมพ์ออกรายงาน</a></p>
<div align="center">
    <table width="1232" border="1" cellspacing="0" cellpadding="0">
      <tr>
        <td width="51" height="27" bgcolor="#CCCCCC"><div align="center" class="style2">ลำดับ</div></td>
        <td width="138" bgcolor="#CCCCCC"><div align="center" class="style2">รหัสนักศึกษา</div></td>
        <td width="141" bgcolor="#CCCCCC"><div align="center" class="style2">ชื่อ-สกุล</div></td>
        <td width="61" bgcolor="#CCCCCC"><div align="center" class="style2">ระดับ</div></td>
        <td width="137" bgcolor="#CCCCCC"><div align="center" class="style2">หลักสูตร</div></td>
        <td width="131" bgcolor="#CCCCCC"><div align="center" class="style2">ภาควิชา</div></td>
        <td width="321" bgcolor="#CCCCCC"><div align="center" class="style2">สถานะการชำระเงิน</div></td>
        <td width="116" bgcolor="#CCCCCC"><div align="center"><span class="style2">พิมพ์ใบแจ้งหนี้ นศ</span>
        <br />
        <a href="re1_me.php?term=<? echo $_GET['term']; ?>&year=<? echo $_GET['year']; ?>">
        <input type="button" name="button2" id="button2" value="พิมพ์ที่เลือก" /></a>
        </div></td>
        <td width="116" bgcolor="#CCCCCC"><div align="center" class="style2">พิมพ์ใบแจ้งหนี้ต้นสังกัด
         <br />
        <a href="re2_me.php?term=<? echo $_GET['term']; ?>&year=<? echo $_GET['year']; ?>">
        <input type="button" name="button3" id="button3" value="พิมพ์ที่เลือก" /></a>
        </div></td>
      </tr>
      
     <?
     
	    if($_GET['dpartid']==0){
		
		$sql = "SELECT * FROM fee_research  where term='$_GET[term]' and year='$_GET[year]'
		           ORDER BY depart_id ASC , level ASC , couse ASC , std_code ASC ";
		
		}else{
		$sql = "SELECT * FROM fee_research  where term='$_GET[term]' and year='$_GET[year]'
		           and depart_id=$dpartid
		           ORDER BY  level ASC ,  couse ASC  , std_code ASC ";
		
		}
		//echo $sql;
		$j=1;
	 $result=mysql_query($sql);
	  while($data = mysql_fetch_array($result)){
	   
	   if($j % 2){ 
	   
	   $gg='#FFFFFF' ;
	   }else{
	   $gg='#E6F7FF' ;
	   
	   }
	  
	 ?> 
     
      <tr  bgcolor="<? echo $gg ;?>">
        <td><div align="center"><? echo $j;?></div></td>
        <td ><? echo $data['std_code'] ;?></td>
        <td ><? echo $data['name'] ;?></td>
        <td><div align="center"><? echo $data['level'] ;?></div></td>
        <td><? echo $data['couse'] ;?></td>
        <td><? echo getDpart($data['depart_id']) ;?></td>
        <td>
         
   <form id="<? echo $data['std_code'] ;?>" name="<? echo $data['std_code'] ;?>" method="post" action="">
 

    
          <select name="status" id="status<? echo $data['std_code'] ;?>" class="status">
            <option value="0">เลือก</option>
            <option value="1" <? if($data['status']=='1'){?> selected="selected" <? }?>>ค้างชำระ</option>
            <option value="2" <? if($data['status']=='2'){?> selected="selected" <? }?>>ได้รับการยกเว้น</option>
            <option value="3"<? if($data['status']=='3'){?> selected="selected" <? }?>>ชำระแล้วจำนวน</option>
          </select>
          
          <span id="div_status<? echo $data['std_code'] ;?>"  
           <? if($data['amount']==0){?> style="display:none;"  <? }?>  >
               <input id="amount<? echo $data['std_code'] ;?>" name="amount" type="text"  value="<? echo $data['amount'] ;?>" size="10" placeholder="จำนวนเงิน"    />
                <input id="slip_no<? echo $data['std_code'] ;?>" name="slip_no" type="text"  value="<? echo $data['slip_no'] ;?>" size="15" placeholder="ใบเสร็จเลขที่"    /> 
          บาท</span>
          
  <input id="stdid<? echo $data['std_code'] ;?>" name="stdid"  type="hidden" value="<? echo $data['std_code'] ;?>" />
  <input id="term<? echo $data['std_code'] ;?>" name="term"  type="hidden" value="<? echo $_GET['term'] ;?>" />
  <input id="year<? echo $data['std_code'] ;?>" name="year"  type="hidden" value="<? echo $_GET['year'] ;?>" />
          <input type="submit" value="บันทึก" class="submit" id="<? echo $data['std_code'] ;?>"/>    
          
           </form>    </td>
        <td><div align="center">
        
         <input  type="checkbox" name="a<?=$data['std_code']?>" id="a<?=$data['std_code']?>" onclick='doClick(this);' value="1"    >
          <a href="re1.php?term=<? echo $_GET['term']; ?>&year=<? echo $_GET['year']; ?>&std_code=<? echo $data['std_code']; ?>">
          พิมพ์
           </a>
        </div></td>
        <td>
        <div align="center">
              <input  type="checkbox" name="b<?=$data['std_code']?>" id="b<?=$data['std_code']?>" onclick='doClick(this);' value="2"    >
           <a href="re2.php?term=<? echo $_GET['term']; ?>&year=<? echo $_GET['year']; ?>&std_code=<? echo $data['std_code']; ?>">
          พิมพ์
           </a>
        </div>               </td>
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
<div align="center"></div>
  </label>



<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript" >

$(function() {

$(".status").change(function(){
  var element = $(this);
  var Id = element.attr("id");
  var status = $("#"+Id).val();
  //alert(status);
   if(status==3 )
	{
       document.getElementById('div_'+Id).style.display='';
    
 	}
	else
	{
	 document.getElementById('div_'+Id).style.display='none';
	} 
});

$(".submit").click(function() {

var element = $(this);
var Id = element.attr("id");

var status = $("#status"+Id).val();
var amount = $("#amount"+Id).val();
var stdid = $("#stdid"+Id).val();
var term = $("#term"+Id).val();
var year = $("#year"+Id).val();
var slip_no = $("#slip_no"+Id).val();
//if(amount==''){amount=0;}
var dataString = 'status='+ status + '&amount=' + amount + '&stdid=' + stdid + '&term=' + term + '&year=' + year + '&slip_no=' + slip_no ;

 
 
 

  
if(status==0 )
{


}
else
{
 //alert(dataString);
/*$.post( "save2.php", $( "#form1" ).serialize() )
  .done(function( data ) {
    alert( "Data Loaded: " + data );
  });*/
  
$.ajax({
type: "POST",
url: "save2.php",
data: dataString,
success: function(html){
 alert(html);
}
});



}
return false;
});
});
</script>

<script>
//AJAX สำหรับจัดการบันทึกลงฐานข้อมูล
function Inint_AJAX() {
  try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch(e) {}
  try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {}
  try { return new XMLHttpRequest(); } catch(e) {}
  alert("XMLHttpRequest not supported");
  return null;
}

//คำสั่งที่ทำเมื่อคลิก checkbox
function doClick(chk) {
 
  var req = Inint_AJAX();
  var val=chk.value;
  //alert(chk.value);
  var id= String(chk.id); 
  //alert(chk.value);
  req.open('GET', 'save3.php?id='+id+'&val='+val, true);
  req.onreadystatechange = function() {
    if (req.readyState==4) {
      if (req.status==200) {
        var data=req.responseText;
        //แสดง error ถ้ามี
        //document.getElementById("status"+id).innerHTML=data;
      }
    }
  };
  req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8"); // set Header
  req.send(null);
}

</script>
<p align="center">&nbsp;</p>
<p align="center">&nbsp;</p>
