<?php
include('session.php');
?>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$MM_authorizedUsers = "4";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup)
{
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
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("", $MM_authorizedUsers, $_SESSION['MM_Username'], $permis)))) {
    $MM_qsChar = "?";
    $MM_referrer = $_SERVER['PHP_SELF'];
    if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
    if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0)
        $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
    $MM_restrictGoTo = $MM_restrictGoTo . $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
    header("Location: " . $MM_restrictGoTo);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ระบบบันทึกการผิดวินัยนักศึกษา</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <!-- Ionicons -->
    <link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- daterange picker -->
    <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
    <!-- iCheck for checkboxes and radio inputs -->
    <link href="css/iCheck/all.css" rel="stylesheet" type="text/css"/>
    <!-- iCheck green -->
    <link href="css/iCheck/flat/green.css" rel="stylesheet" type="text/css"/>
    <!-- Bootstrap Color Picker -->
    <link href="css/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet"/>
    <!-- Bootstrap time Picker -->
    <link href="css/timepicker/bootstrap-timepicker.min.css" rel="stylesheet"/>
    <!-- Theme style -->
    <link href="css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <!-- Auto Suggest -->
    <link rel="stylesheet" href="css/auto.css"/>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/autoexam.js"></script>

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
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image"><img src="img/avatar3.png" class="img-circle" alt="User Image"/></div>
                <div class="pull-left info">
                    <p><?php if ($permis < 1) {
                            echo "ยินดีต้อนรับ";
                        } else {
                            echo $fname;
                        } ?>
                    </p>
                    <a href="#"><?php if ($permis < 1) {
                        } else if ($permis == 5) {
                            echo "<i class='fa fa-circle text-success'></i> นักศึกษา";
                        } else {
                            echo "<i class='fa fa-circle text-success'></i>" . $permisname;
                        } ?></a>
                </div>
            </div>
            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search..."/>
                    <span class="input-group-btn">
          <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
          </span></div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <?php if ($permis == 1) { ?>

                <!-- ถ้าเป็นหัวหน้าภาค-->
            <?php } else if ($permis == 2) { ?>

                <!-- ถ้าเป็นอาจารย์-->
            <?php } else if ($permis == 3) { ?>

                <!-- ถ้าเป็นเจ้าหน้าที่ทั่วไป-->
            <?php } else if ($permis == 4) { ?>
                <ul class="sidebar-menu">
                    <li><a href="index.php"> <i class="fa fa-home"></i> <span>หน้าแรก</span> </a></li>
                    <li class="treeview"><a href="#"> <i class="fa fa-asterisk"></i> <span>ระเบียบและข้อบังคับ</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                        <ul class="treeview-menu">
                            <li><a href="discipline.php"><i class="fa fa-angle-double-right"></i>ข้อปฏิบัติตามวินัยนักศึกษา</a>
                            </li>
                            <li><a href="examination.php"><i
                                            class="fa fa-angle-double-right"></i>การปฏิบัติตนในการสอบ</a></li>
                            <li><a href="clothing.php"><i class="fa fa-angle-double-right"></i>การแต่งกาย</a></li>
                        </ul>
                    </li>
                    <li class="treeview"><a href="#"> <i class="fa fa-bar-chart-o"></i> <span>กราฟแสดงสถิติ</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                        <ul class="treeview-menu">
                            <li><a href="dischart.php"><i class="fa fa-angle-double-right"></i>การผิดวินัย</a></li>
                            <li><a href="latechart.php"><i class="fa fa-angle-double-right"></i>การเข้าสอบสาย</a></li>
                        </ul>
                    </li>
                    <li><a href="disrec.php"> <i class="fa fa-edit"></i> <span>บันทึกการผิดวินัยนักศึกษา</span></a></li>
                    <li><a href="history.php"> <i class="fa fa-list"></i> <span>ประวัติการบันทึกการผิดวินัย</span></a>
                    </li>
                    <li class="active"><a href="laterec.php"> <i class="fa fa-list-alt"></i> <span>ออกฟอร์มขออนุญาตเข้าสอบช้า</span></a>
                    </li>
                    <li class="treeview"><a href="#"> <i class="fa fa-print"></i> <span>ออกรายงาน</span> <i
                                    class="fa fa-angle-left pull-right"></i> </a>
                        <ul class="treeview-menu">
                            <li><a href="latereport.php"><i class="fa fa-angle-double-right"></i> การเข้าสอบสาย</a></li>
                        </ul>
                    </li>
                </ul>
                <!-- ถ้าเป็นนักศึกษา-->
            <?php } else if ($permis == 5) { ?>
            <?php } else { ?>
            <?php } ?>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>&nbsp; </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-list-alt"></i> ออกฟอร์มขออนุญาตเข้าสอบช้า</a></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <form name="getlate" method="post" action="fpdf/form_late.php" target="_blank">
                <!-- row -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- The time line -->
                        <ul class="timeline">
                            <!-- timeline time label -->
                            <li class="time-label"><span class="bg-blue"> กรอกรหัสนักศึกษา / สแกนบาร์โค้ด </span></li>
                            <!-- /.timeline-label -->
                            <!-- timeline item -->
                            <li>
                                <i class="fa fa-barcode bg-blue"></i>
                                <div class="timeline-item">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <!-- iCheck -->
                                            <div class="box box-primary">
                                                <div class="box-header">
                                                    <h3 class="box-title"><i class="fa fa-barcode">&nbsp;&nbsp;</i>กรุณากรอกข้อมูล
                                                    </h3>
                                                    <small>รหัสนักศึกษา / รหัสประจำตัวประชาชน</small>
                                                </div>
                                                <!-- /.box-header -->

                                                <div class="box-body">
                                                    <div class="input-group input-group-lg">
                                                        <div class="input-group-btn">
                                                            <select class="btn btn-info input" id="idType"
                                                                    name="selectid">
                                                                <option value="2">รหัสประจำตัวประชาชน</option>
                                                                <option value="1">รหัสนักศึกษา</option>
                                                            </select>
                                                        </div>
                                                        <!-- /btn-group -->
                                                        <input type="text" class="form-control" id="personID"
                                                               name="personID"
                                                               placeholder="กรอกรหัสบัตรประจำตัวประชาชน 13 หลัก ไม่มีขีด"
                                                               autocomplete="off" required
                                                               data-inputmask='"mask": "9999999999999"' data-mask //>
                                                    </div>
                                                    <span class="text-red">* กรอกรหัสครบแล้ว <span
                                                                style="font-size:120%;">กรุณารอสักครู่</span> ไม่ต้องกดปุ่ม Enter</span> <span class="text-green">หากข้อมูลไม่ขึ้นให้พิมพ์เลขเพิ่มเพื่อเป็นการ reface ข้อมูล</span>
                                                    <!-- /input-group -->
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                            <!-- /.box -->
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->
                                    <!-- END timeline item -->
                            <li class="time-label">
                                <span class="bg-green"> ตรวจสอบข้อมูลนักศึกษา และเลือกสาเหตุการมาสาย</span>
                            </li>
                            <!-- timeline item -->
                            <li><i class="fa fa-user bg-green"></i>
                                <div class="timeline-item" style="background:#f8f8f8;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Info box -->
                                            <div class="box box-solid box-danger">
                                                <div class="box-header">
                                                    <h3 class="box-title" style="font-weight:bold">ข้อมูลนักศึกษา</h3>
                                                    <div class="box-tools pull-right"></div>
                                                </div>
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label>ชื่อ สกุลนักศึกษา</label>
                                                        <input type="text" class="form-control" id="stdname"
                                                               placeholder="ชื่อ สกุล นักศึกษา" disabled/>
                                                        <br>
                                                        <label>ภาควิชา</label>
                                                        <input type="text" class="form-control" id="department"
                                                               placeholder="ภาควิชา" disabled/>
                                                        <br>
                                                        <label>สาขาวิชา</label>
                                                        <input type="text" class="form-control" id="subdepartment"
                                                               placeholder="สาขาวิชา" disabled/>
                                                        <br>
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->

                                            </div>
                                            <!-- /.box -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-6">
                                            <div class="box box-primary box-solid">
                                                <div class="box-header">
                                                    <h3 class="box-title" style="font-weight:bold">
                                                        กรอกรายละเอียดวิชาที่สอบ</h3>
                                                </div><!--/. box header-->
                                                <div class="box-body">
                                                    <div class="form-group">

                                                        <!--    <input type="text" id="EXAMCODE"  name="EXAMCODE" autocomplete="off" class="form-control"  placeholder=" กรอกรหัสวิชา"  required data-inputmask='"mask": "999999"' data-mask > -->
                                                        <input type="text" autocomplete="off" class="form-control"
                                                               id="EXAMCODE" placeholder="กรอกรหัสวิชา"/>

                                                        <!--  /  name="courseid"-->

                                                        <br>
                                                        <input type="text" id="room" class="form-control"
                                                               placeholder=" ห้องสอบ" disabled>
                                                        <!--  name="roomname" -->


                                                    </div>
                                                </div><!--/. box body-->
                                            </div><!--/. box-->
                                            <div class="box box-success box-solid">
                                                <div class="box-header">
                                                    <h3 class="box-title" style="font-weight:bold">
                                                        เลือกสาเหตุที่มาสอบสาย</h3>
                                                </div><!--/. box header-->
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <?php
                                                        include "connect.php";
                                                        $reason = mysql_query("SELECT * FROM discipline.reason");
                                                        if ($reason === FALSE) {
                                                            die(mysql_error()); // TODO: better error handling
                                                        }
                                                        while ($row = mysql_fetch_array($reason)) {
                                                            if ($row['ReasonID'] == '1') {
                                                                echo "<input type='radio' class='iradio_flat-green' name='reason' id='reason' value='$row[ReasonID]' checked/>";
                                                                echo "<label>&nbsp;$row[ReasonName] <small> $row[ReasonDesc] </small></label><br>";
                                                            } else if ($row['ReasonName'] == 'อื่น ๆ') {
                                                                echo "<div class='input-group'><span class='input-group-addon'><input type='radio' class='iradio_flat-green' name='reason' id='reason' value='$row[ReasonID]' required></span>
                <input type='text' id='desc' name='desc' class='form-control' placeholder='อื่น ๆ โปรดระบุ'></div>";
                                                            } else {
                                                                echo "<input type='radio' class='iradio_flat-green' name='reason' id='reason' value='$row[ReasonID]'/>";
                                                                echo "<label>&nbsp;$row[ReasonName] <small> $row[ReasonDesc] </small></label><br>";
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                            </li>
                            <!-- END timeline item -->
                            <li><i class="fa fa-print bg-teal"></i>
                                <div class="timeline-item">
                                    <div class="box-footer" style="margin:1%;">
                                        <center>
                                            <input class="btn btn-danger btn-lg" type="reset"
                                                   value="&nbsp;ล้างข้อมูล&nbsp;" name="reset"/>
                                            &emsp;
                                            <input class="btn btn-primary btn-lg" type="submit" value="บันทึกข้อมูล"
                                                   name="submit"
                                                   onClick="setTimeout(function () { window.location.reload(); }, 10);window.scrollTo(0,0);"/>
<br><br>
                                            <span class="text-red">* หลังจากกด <u>บันทึกข้อมูล</u> <span
                                                        style="font-size:120%;">หากรายงาน "การเข้าสอบช้า"</span> ไม่แสดงข้อมูลให้กดปุ่ม Reface</span>
                                        </center>
                                    </div>
                                </div>
                            </li>
                            <!-- END timeline item -->
                        </ul>


                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </form>
        </section>
        <!-- /.content -->
    </aside>
    <!-- /.right-side -->
</div>
<!-- ./wrapper -->
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
<script src="js/AdminLTE/app.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="js/AdminLTE/demo.js" type="text/javascript"></script>
<!-- Page script -->

<script>

    var url = 'selectssnexam.php?id='; // //ใส่ url ไฟล์
    var idLength = 13;
    $('#idType').on('change', function () {
        if ($(this).val() == 1) {
            url = 'selectcodexam.php?id='; //ใส่ url ไฟล์
            idLength = 11;
            $('#personID').attr('placeholder', 'XXXXXXXXX-X');
            $('#personID').inputmask("999999999-9");
        } else {
            url = 'selectssnexam.php?id=';//ใส่ url อีกไฟล์ที่หาโดยบัตรประชาชน
            idLength = 13;
            $('#personID').attr('placeholder', '14099XXXXXXXX');
            $('#personID').inputmask("9999999999999");
        }
    });
    $('#personID').on('keyup', function () {
        var length = $('#personID').val().length;
        if (length == idLength) {
            $.ajax({
                url: url + $('#personID').val(),
                type: 'GET',
                dataType: "json",
                success: function (data) {
                    $('#stdname').val(data[0].STUDENTNAME);
                    $('#department').val(data[0].DEPARTMENT);
                    $('#subdepartment').val(data[0].PROGRAM);
//							$('#EXAMCODE').inputmask("999999");
                    // $('#EXAMCODE').val(data[0].COURSE);

                    $('#room').val(data[0].ROOM);
                    //$('#room').inputmask("999999");
                    //  $('#email').val(data[0].EMAIL);
                    // $('#phone').val(data[0].TEL);


                },
                error: function () {
                }
            });
        }
    });

</script>
<script type="text/javascript">
    $(function () {
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
        $('input[type="checkbox"].iradio_flat-green, input[type="radio"].iradio_flat-green').iCheck({
            checkboxClass: 'iradio_flat-green',
            radioClass: 'iradio_flat-green'
        });
        $("[data-mask]").inputmask();

        //Timepicker
        $(".timepicker").timepicker({
            showInputs: false
        });
    });
</script>
</body>
</html>
