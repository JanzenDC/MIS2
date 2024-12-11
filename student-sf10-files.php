<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Dashboard</title><link rel="icon" href="../img/favicon2.png">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  <style>
    .form-box {
      margin-bottom: 20px;
      width: 500%;  
    }
   
    .sidebar-logo img {
  display: block;
  transition: all 0.9s ease; /* Smooth transition */
}

/* When the sidebar is collapsed */
.sidebar-collapse .sidebar-logo img {
  display: none; /* Hide the logo when collapsed */
}
.content-wrapper::before {
  content: '';
  position: absolute;
  top: 50%; /* Move to the center vertically */
  left: 58%; /* Move to the center horizontally */
  width: 500px; /* Set a fixed width for the logo */
  height: 500px; /* Set a fixed height for the logo */
  background: url('dist/img/deped_logo.png') no-repeat; /* Remove the center positioning from background */
  background-size: contain; /* Adjust to maintain aspect ratio */
  transform: translate(-50%, -50%); /* Center the element */
  opacity: 0.1; /* Make it subtle */
  z-index: 0; /* Set to a lower z-index */
  pointer-events: none; /* Ensure the watermark doesn’t interfere with interactions */
}

.text-center {
        text-align: center; /* Center text horizontally */
    }
  </style>
</head>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="./" class="logo">
      <span class="logo-mini"><b>MIS</b></span>
      <span class="logo-lg"><b>Enroll</b> Student</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs">Hey! I'm Teacher Ronald</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="dist/img/avatar5.png" class="img-circle" alt="User Image">
                <p><small>Student</small></p>
              </li>
              <li class="user-footer">
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <aside class="main-sidebar">
    <section class="sidebar">
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
                    <li id="enroll-student"><a href="./enroll-student.php"><i class="fa fa-file-text"></i> Enroll Student</a></li>
                    <li id="student-sf10-files"><a href="./student-sf10-files.php"><i class="fa fa-file-text"></i> Student SF10 Files</a></li>
                    <li id="student-details"><a href="./student-details.php"><i class="fa fa-file-text"></i> Student Details</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>School Forms</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="academic-records"><a href="./academic-records.php"><i class="fa fa-file-text"></i> Academic Records</a></li>
                    <li id="form-137"><a href="./form-137.php"><i class="fa fa-file-text"></i> Form 137</a></li>
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
                    <li id="subject-maintenance"><a href="./subject-maintenance.php"><i class="fa fa-book"></i> Subject</a></li>
                    <li id="grade-maintenance"><a href="./grade-maintenance.php"><i class="fa fa-graduation-cap"></i> Grade</a></li>
                    <li id="curriculum-maintenance"><a href="./curriculum-maintenance.php"><i class="fa fa-clipboard"></i> Curriculum</a></li>
                    <li id="user-maintenance"><a href="./user-maintenance.php"><i class="fa fa-user"></i> User Maintenance</a></li>
                </ul>
            </li>
            <li id="about"><a href="./about.php"><i class="fa fa-info-circle"></i> <span>About</span></a></li>
        </ul>
    </section>
</aside>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>Enroll <small> Student</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Student</a></li>
        <li class="active">Enroll Student</li>
      </ol>
    </section>

    <div class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="alert alert-success alert-dismissible" style="display: none;" id="truemsg">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            New Subject Successfully added
          </div>

          <div class="col-xs-12">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Upload Learner Excel File</h3>
              </div>
              <div class="box-body">
                <form action="upload_excel.php" method="post" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="excelFile">Choose Excel File to Upload</label>
                    <input type="file" class="form-control" name="excelFile" accept=".xls,.xlsx" required>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="submit" value="submit" class="btn btn-primary">Upload Excel File</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">All Students</h3>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>LRN No.</th>
                    <th>Full Name</th>
                    <th>Grade Level</th>
                    <th>Type of Student</th>
                    <th>Curriculum</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Populate with subjects from database -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>No Copyright Infringement &copy;.</strong> All rights reserved.
  </footer>
</div>

<!-- Scripts -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="dist/js/demo.js"></script>
<script>
  $(function () {
    $('.select2').select2();
    $('#datepicker').datepicker({
      autoclose: true
    });
  });
</script>
</body>
</html>