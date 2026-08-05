<style>
#wait {
  position:absolute;
  left:50%;
  top:50%;
  color:navy;
  text-align:center;
  width: 200px;
}
</style>
<script language="javascript" >
function loaddoc(id) {
  //แสดง icon คอยการ load ก่อนเลยถ้ามีการเรียกใช้ AJAX
  document.getElementById("content").innerHTML='<div id="wait"><br />กำลังโหลดข้อมูล...</div>';
  req.onreadystatechange = function () { 
    if (req.readyState==4) {
      if (req.status==200) {
        var data=req.responseText; //รับค่ากลับมา
        document.getElementById("content").innerHTML=data; //แสดงผล แทนรูปรอโหลด
      } 
    } 
  };
  req.open("GET", "index.php?id="+id, true); 
  req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // set Header
  req.send(null); //ส่งค่า
}
</script>

<p>&nbsp;</p>
<p>&nbsp;</p>
<form name="form1" method="post" action="getdata.php" onsubmit="javascript:loaddoc('this')">
  <label>ดึงข้อมูล นักศึกษา ป โท - เอก  เทอม
  <select name="term" id="term">
    <option value="1" <? if($_POST['term']==1){?> selected="selected" <? }?>>1</option>
    <option value="2" <? if($_POST['term']==2){?> selected="selected" <? }?>>2</option>
  </select>
  </label> 
  ปีการศึกษา 
  <label>
  <select name="year" id="year">
      <? for($i=2555 ;$i<=2570;$i++){?>
     <option value="<? echo $i; ?>" <? if($_POST['year']==$i){?> selected="selected" <? }?> ><? echo $i; ?></option>
     <? }?>
    </select>
  </label>
  <label>
  <input type="submit" name="button" id="button" value="ดึงข้อมูล">
  </label>
</form>
<p>&nbsp;</p>
<div id=content></div>

