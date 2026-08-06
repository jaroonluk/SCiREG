<?php
    include ('session.php');
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2,4";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $permis)))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ระบบตรวจสอบการผิดวินัยนักศึกษา</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- Ionicons -->
<link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- daterange picker -->
<link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
<!-- iCheck for checkboxes and radio inputs -->
<link href="css/iCheck/all.css" rel="stylesheet" type="text/css" />
<!-- iCheck green -->
<link href="css/iCheck/flat/green.css" rel="stylesheet" type="text/css" />
<!-- iCheck blue -->
<link href="css/iCheck/flat/blue.css" rel="stylesheet" type="text/css" />
<!-- Bootstrap Color Picker -->
<link href="css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet"/>
<!-- Bootstrap time Picker -->
<link href="css/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
<!-- Theme style -->
<link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
<!-- Auto Suggest -->
<script type="text/javascript" src="js/AutoSuggest.js"></script>
<link rel="stylesheet" href="css/AutoSuggest/autosuggest_inquisitor.css" type="text/css" media="screen" charset="utf-8" />

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
</head>
<body class="skin-blue">
 <?php include_once('header.php'); ?>
<div class="wrapper row-offcanvas row-offcanvas-left"> 
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="left-side sidebar-offcanvas"> 
    <!-- sidebar: style can be found in sidebar.less -->
    <?php include_once('sidebar.php'); ?>
    <?php /*?>
    <section class="sidebar"> 
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image"> <img src="img/avatar3.png" class="img-circle" alt="User Image" /> </div>
        <div class="pull-left info">
         <p><?php if($permis < 1){ echo "ยินดีต้อนรับ";
                                    }else{echo $fname;} ?>
            </p>
             <a href="#"><?php if($permis < 1){ }else{echo "<i class='fa fa-circle text-success'></i>".$permisname ;} ?></a>
          </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search..."/>
          <span class="input-group-btn">
          <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
          </span> </div>
      </form>
      <!-- /.search form --> 
      <!-- sidebar menu: : style can be found in sidebar.less --> 
      <!-- ถ้าเป็นแอดมิน -->
      <?php if($permis==1){ ?>
      <ul class="sidebar-menu">
        <li> <a href="index.php"> <i class="fa fa-home"></i> <span>หน้าแรก</span> </a> </li>
        <li class="treeview"> <a href="#"> <i class="fa fa-asterisk"></i> <span>ระเบียบและข้อบังคับ</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
            <li><a href="discipline.php"><i class="fa fa-angle-double-right"></i>ข้อปฏิบัติตามวินัยนักศึกษา</a></li>
            <li><a href="examination.php"><i class="fa fa-angle-double-right"></i>การปฏิบัติตนในการสอบ</a></li>
            <li><a href="clothing.php"><i class="fa fa-angle-double-right"></i>การแต่งกาย</a></li>
          </ul>
        </li>
    <li class="treeview"> <a href="#"> <i class="fa fa-bar-chart-o"></i> <span>กราฟแสดงสถิติ</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
            <li><a href="dischart.php"><i class="fa fa-angle-double-right"></i>การผิดวินัย</a></li>
            <li><a href="latechart.php"><i class="fa fa-angle-double-right"></i>การเข้าสอบสาย</a></li>
          </ul>
        </li>
        <li> <a href="workdis.php"> <i class="fa fa-search"></i> <span>ตรวจสอบงานวินัยนักศึกษา</span></a> </li>
        <li> <a href="cheatrec.php"> <i class="fa fa-folder-open"></i> <span>บันทึกการทุจริตในการสอบ</span></a> </li>
        <li> <a href="disrec.php"> <i class="fa fa-edit"></i> <span>บันทึกการผิดวินัยนักศึกษา</span></a> </li>
        <li> <a href="history.php"> <i class="fa fa-list"></i> <span>ประวัติการบันทึกการผิดวินัย</span></a> </li>
        <li class="treeview active"> <a href="#"> <i class="fa fa-print"></i> <span>ออกรายงาน</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
            <li><a href="disreport.php"><i class="fa fa-angle-double-right"></i> การผิดวินัย</a></li>
            <li><a href="cheatrep.php"><i class="fa fa-angle-double-right"></i> การทุจริตสอบ</a></li>
            <li class="active"><a href="latereport.php"><i class="fa fa-angle-double-right"></i> การเข้าสอบสาย</a></li>
          </ul>
        </li>
      </ul>
      <?php }else if($permis == 2){ ?>
      <ul class="sidebar-menu">
        <li> <a href="index.php"> <i class="fa fa-home"></i> <span>หน้าแรก</span> </a> </li>
        <li class="treeview"> <a href="#"> <i class="fa fa-asterisk"></i> <span>ระเบียบและข้อบังคับ</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
            <li><a href="discipline.php"><i class="fa fa-angle-double-right"></i>ข้อปฏิบัติตามวินัยนักศึกษา</a></li>
            <li><a href="examination.php"><i class="fa fa-angle-double-right"></i>การปฏิบัติตนในการสอบ</a></li>
            <li><a href="clothing.php"><i class="fa fa-angle-double-right"></i>การแต่งกาย</a></li>
          </ul>
        </li>
         <li class="treeview"> <a href="#"> <i class="fa fa-bar-chart-o"></i> <span>กราฟแสดงสถิติ</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
              <li><a href="dischart.php"><i class="fa fa-angle-double-right"></i>การผิดวินัย</a></li>
            <li><a href="latechart.php"><i class="fa fa-angle-double-right"></i>การเข้าสอบสาย</a></li>
          </ul>
        </li>
        <li> <a href="search.php"> <i class="fa fa-search"></i> <span>ค้นหาข้อมูลการผิดวินัย</span></a> </li>
        <li> <a href="disrec.php"> <i class="fa fa-edit"></i> <span>บันทึกการผิดวินัยนักศึกษา</span></a> </li>
        <li> <a href="history.php"> <i class="fa fa-list"></i> <span>ประวัติการบันทึกการผิดวินัย</span></a> </li>
          <li class="treeview active"> <a href="#"> <i class="fa fa-print"></i> <span>ออกรายงาน</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
            <li class="active"><a href="latereport.php"><i class="fa fa-angle-double-right"></i> การเข้าสอบสาย</a></li>
          </ul>
        </li>
       
      </ul>
      <?php  }else if($permis==4){ ?>
      <ul class="sidebar-menu">
        <li> <a href="index.php"> <i class="fa fa-home"></i> <span>หน้าแรก</span> </a> </li>
        <li class="treeview"> <a href="#"> <i class="fa fa-asterisk"></i> <span>ระเบียบและข้อบังคับ</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
            <li><a href="discipline.php"><i class="fa fa-angle-double-right"></i>ข้อปฏิบัติตามวินัยนักศึกษา</a></li>
            <li><a href="examination.php"><i class="fa fa-angle-double-right"></i>การปฏิบัติตนในการสอบ</a></li>
            <li><a href="clothing.php"><i class="fa fa-angle-double-right"></i>การแต่งกาย</a></li>
          </ul>
        </li>
        <li class="treeview"> <a href="#"> <i class="fa fa-bar-chart-o"></i> <span>กราฟแสดงสถิติ</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
              <li><a href="dischart.php"><i class="fa fa-angle-double-right"></i>การผิดวินัย</a></li>
            <li><a href="latechart.php"><i class="fa fa-angle-double-right"></i>การเข้าสอบสาย</a></li>
          </ul>
        </li>
        <li> <a href="disrec.php"> <i class="fa fa-edit"></i> <span>บันทึกการผิดวินัยนักศึกษา</span></a> </li>
        <li> <a href="history.php"> <i class="fa fa-list"></i> <span>ประวัติการบันทึกการผิดวินัย</span></a> </li>
        <li> <a href="laterec.php"> <i class="fa fa-list-alt"></i> <span>ออกฟอร์มขออนุญาตเข้าสอบช้า</span></a> </li>
        <li class="treeview active"> <a href="#"> <i class="fa fa-print"></i> <span>ออกรายงาน</span> <i class="fa fa-angle-left pull-right"></i> </a>
          <ul class="treeview-menu">
            <li class="active"><a href="latereport.php"><i class="fa fa-angle-double-right"></i> การเข้าสอบสาย</a></li>
          </ul>
        </li>
      </ul>
        <?php } ?>
    </section>
	<?php */?>
    <!-- /.sidebar --> 
  </aside>
  
  <!-- Right side column. Contains the navbar and content of the page -->
  <aside class="right-side">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> <i class="fa fa-print"></i> ออกรายงานการเข้าสอบสาย </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-print"></i> ออกรายงาน</a></li>
      <li class="active">การเข้าสอบสาย</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content">
  <div class="row">
    <div class="col-md-12"> 
      <!-- Default box -->
      <div class="box box-primary">
        <div class="box-header"></div>
        <div class="box-body">
          <form name="lateyear" method="post" action="mpdf/laterep_reason.php" target="_blank">
            <div class="form-group">
              <label style="font-size:120%">ปีการศึกษา : </label>
              <select class="input-lg" name="acadyear">
                <?php $year = mysql_query("SELECT acadyear.ACADYEAR FROM acadyear GROUP BY acadyear.ACADYEAR");
                    while($fetchy = mysql_fetch_array($year)){ ?>
                        <option value="<?php echo $fetchy['ACADYEAR'];?>"><?php echo $fetchy['ACADYEAR'];?></option>
                    <?php } ?>
                                                
              </select>
            </div>
            <!--./form-group-->
            <table width="50%" class="table">
                <thead>
                <tr>
                <th>ภาคการศึกษา : </th><th></th>
                </tr>
                </thead>
              <tr>
                <td width="20%"><input type="checkbox" name="check_list[]" class="icheckbox_flat-blue" value="s1" checked/> <label>&nbsp;ภาคต้น</label></td>
                   <td><input type="checkbox" name="check_list[]" class="icheckbox_flat-blue" value="s2" checked/> <label>&nbsp;ภาคปลาย</label></td>
              </tr>
              </table>
              <table width="50%" class="table">
                  <thead><tr><th>ช่วงเวลาการสอบ :</th><th></th></tr></thead>
              <tr>
                 <td width="20%"><input type="checkbox" name="check_list[]" class="icheckbox_flat-green" value="mid" checked/> <label>&nbsp;กลางภาค</label></td>
                 <td><input type="checkbox" name="check_list[]" class="icheckbox_flat-green" value="final" checked/> <label>&nbsp;ปลายภาค</label></td>           
              </tr>
            </table>
            <div class="box-footer">
              <center>
                <input type="submit" class="btn btn-primary btn-lg" value="ออกรายงาน">
              </center>
            </div>
          </form>
        <!-- /.box-body --> 
      </div>
      <!--./box body--> 
    </div>
    <!--./box--> 
  </div>
  <!--./col--> 
</div>
<!--./row-->
<div class="row">
  <div class="col-md-5"> </div>
  <!--./col--> 
</div>
<!--./row-->

<div class="box-footer"> </div>
<!-- /.box-footer-->
</div>
<!-- /.box -->
</div>
<!-- /.col -->

</div>
</section>
<!-- /.content -->
</aside>
<!-- /.right-side -->
</div>
<!-- ./wrapper --> 

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" type="text/javascript"></script> 
<!-- InputMask --> 
<script src="js/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script> 
<script src="js/plugins/input-mask/jquery.inputmask.date.extensions.js" type="text/javascript"></script> 
<script src="js/plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script> 
<!-- date-range-picker --> 
<script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script> 
<!-- bootstrap color picker --> 
<script src="js/plugins/colorpicker/bootstrap-colorpicker.min.js" type="text/javascript"></script> 
<!-- bootstrap time picker --> 
<script src="js/plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script> 
<!-- AdminLTE App --> 
<script src="js/AdminLTE/appforradio.js" type="text/javascript"></script> 
<!-- AdminLTE for demo purposes --> 
<script src="js/AdminLTE/demo.js" type="text/javascript"></script> 
<!-- DATA TABES SCRIPT --> 
<script src="js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script> 
<script src="js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script> 

<!-- Page script -->
 <script type="text/javascript">
            $(function() {
                //iCheck for checkbox and radio inputs
                $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                    checkboxClass: 'icheckbox_minimal',
                    radioClass: 'iradio_minimal'
                });
                //Red color scheme for iCheck
                $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                    checkboxClass: 'icheckbox_minimal-red',
                    radioClass: 'iradio_minimal-red'
                });
                //Flat red color scheme for iCheck
                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                    checkboxClass: 'icheckbox_flat-red',
                    radioClass: 'iradio_flat-red'
                });
                $('input[type="checkbox"].icheckbox_flat-green, input[type="radio"].iradio_flat-green').iCheck({
                    checkboxClass: 'icheckbox_flat-green',
                    radioClass: 'iradio_flat-green'
                });
                $('input[type="checkbox"].icheckbox_flat-blue, input[type="radio"].iradio_flat-blue').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                });

               
            });
</script>
</body>
</html>