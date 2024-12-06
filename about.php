<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to count the number of ids in learners table
$sql = "SELECT COUNT(id) AS total_learners FROM learners";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_learners = $row['total_learners'];
} else {
    $total_learners = 0;
}

$sql = "SELECT COUNT(id) AS total_users FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_users = $row['total_users'];
} else {
    $total_users = 0;
}

$sql = "SELECT COUNT(id) AS total_shs_subjects FROM shs_subjects";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_shs_subjects = $row['total_shs_subjects'];
} else {
    $total_shs_subjects = 0;
}

$sql = "SELECT COUNT(id) AS total_subjects FROM subjects";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_subjects = $row['total_subjects'];
} else {
    $total_subjects = 0;
}

$conn->close();
?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> About</title><link rel="icon" href="../img/favicon2.png">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">

  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<style>

.content-wrapper {
  position: relative;
  z-index: 1; /* Ensures that the content is on top of the watermark */
}

.content-wrapper::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url('dist/img/deped_logo.png') no-repeat center center;
  background-size: 500px 500px; /* Adjust watermark size */
  opacity: 0.1; /* Make it subtle */
  z-index: -1; /* Push the watermark behind the content */
  pointer-events: none; /* Ensure the watermark doesn’t interfere with interactions */
}
/* Default Logo Style */
.sidebar-logo img {
  display: block;
  transition: all 0.9s ease; /* Smooth transition */
}

/* When the sidebar is collapsed */
.sidebar-collapse .sidebar-logo img {
  display: none; /* Hide the logo when collapsed */
}

</style>
<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

  <!-- Main Header -->
   <header class="main-header">

    <!-- Logo -->
    <a href="./" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>MIS</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Student</b> Grading</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
      
          <!-- /.messages-menu -->

          <!-- Notifications Menu -->
         
          <!-- Tasks Menu -->
         
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">ADMIN</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="dist/img/avatar5.png" class="img-circle" alt="User Image">

                <p>
                                    <small>ADMIN</small>
                </p>
              </li>
              <!-- Menu Body -->
             
              <!-- Menu Footer-->
              <li class="user-footer">
                
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Logout</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
         
        </ul>
      </div>
    </nav>
  </header>  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
  <section class="sidebar">
    <!-- Logo Section -->
    <div class="sidebar-logo" style="text-align: center; padding: 10px;">
      <img id="sidebar-logo" src="dist/img/macayo_logo.png" alt="DepEd Logo" style="max-width: 100px; margin-left: 50px; transition: all 0.9s ease;">
    </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li id="dashboard"><a href="./"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="treeview">
    <a href="#">
        <i class="fa fa-folder"></i> <span>Student Status</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
        
    </a>
    <ul class="treeview-menu">
    <li class="treeview">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Junior HS Student</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                <li id="student-maintenance-7"><a href="./grade7_student.php"><i class="fa fa-user"></i> Grade 7</a></li>
<li id="student-maintenance-8"><a href="./grade8_student.php"><i class="fa fa-user"></i> Grade 8</a></li>
<li id="student-maintenance-9"><a href="./grade9_student.php"><i class="fa fa-user"></i> Grade 9</a></li>
<li id="student-maintenance-10"><a href="./grade10_student.php"><i class="fa fa-user"></i> Grade 10</a></li>

                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Senior HS Student</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                <li id="student-maintenance-11"><a href="./grade11_student.php"><i class="fa fa-user"></i> Grade 11</a></li>
                <li id="student-maintenance-12"><a href="./grade12_student.php"><i class="fa fa-user"></i> Grade 12</a></li>

                </ul>
            </li>
      </ul>
</li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>Maintenance</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                <li class="treeview">
                <a href="#">
                    <i class="fa fa-clipboard"></i> <span>Subject</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="subject-maintenance"><a href="subject-maintenance.php"><i class="fa fa-book"></i> Junior High School</a></li>
                    <li id="subject-maintenance1"><a href="subject-maintenance1.php"><i class="fa fa-graduation-cap"></i> Senior High School</a></li>

                </ul>
            </li>
                    <li id="user-maintenance"><a href="account-maintenance.php"><i class="fa fa-user"></i> Account Maintenance</a></li>
                </ul>
            </li>
            <li id="about"><a href="about.php"><i class="fa fa-info-circle"></i> <span>About</span></a></li>
        </ul>
        
    </section>
</aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        MACAYO INTEGRATED SCHOOL
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> About</a></li>
      </ol>
    </section>

    <!-- Main content -->


    <section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading text-center">
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="dist/img/macayo_logo.png" alt="Macayo Integrated School" class="img-responsive img-thumbnail center-block">
                        </div>
                        <div class="col-md-8">
                            <p class="lead">Macayo Integrated School is a thriving educational institution, dedicated to providing high-quality education to its learners. With a strong commitment to academic excellence and student well-being, we aim to nurture the talents and skills of each learner.</p>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><b>Total Learners:</b> 340</p>
                                    <p><b>Total Faculty Members:</b> 21</p>
                                </div>
                                <div class="col-md-6">
                                    <p><b>Total Subjects (JHS + SHS):</b> 50</p>
                                    <p><b>Total Users (System):</b> 25</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-center">
                    <small>Empowering future generations through quality education.</small>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
    <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
     
    </div>
    <!-- Default to the left -->
    <strong>No Copyright Infringement &copy;.</strong> All rights reserved.
  </footer>
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>
  // Event listener for sidebar toggle
  $('.sidebar-toggle').on('click', function () {
    $('body').toggleClass('sidebar-collapse'); // Toggle the collapse class
  });
</script>

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- Select2 -->


<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>

<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page script -->



<script>   $('.select2').select2()
  $('#datepicker').datepicker({
      autoclose: true
    });


        
            var r = document.getElementById("stat"); 
            r.className += "active"; 
           
    </script>
<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. -->
</body>
</html>