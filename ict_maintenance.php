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

<?php
// Include your database connection file
include 'db_connection.php'; // Ensure you have your database connection established

session_start(); // Start the session to access session variables
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session upon login

// Fetch user role only
$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $userRole = $user['role']; // Fetch the user's role
} else {
    $userRole = "No Role"; // Default role if not found
}

$query = "SELECT id, description FROM events ORDER BY id";
$result = mysqli_query($conn, $query);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['deleteEvent'])) {
      $eventId = (int) $_POST['eventId'];
      if ($eventId > 0) {
          $query = "DELETE FROM events WHERE id = $eventId";
          if (mysqli_query($conn, $query)) {
              $_SESSION['alert'] = '<div class="alert alert-success">Event successfully deleted!</div>';
          } else {
              $_SESSION['alert'] = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
          }
      } else {
          $_SESSION['alert'] = '<div class="alert alert-warning">Invalid event ID.</div>';
      }
      header('Location: ' . $_SERVER['PHP_SELF']);
      exit;
  }

  if (isset($_POST['editEvent'])) {
      $eventId = (int) $_POST['eventId'];
      $newDescription = mysqli_real_escape_string($conn, $_POST['eventDescription']);
      if ($eventId > 0 && !empty($newDescription)) {
          $query = "UPDATE events SET description = '$newDescription' WHERE id = $eventId";
          if (mysqli_query($conn, $query)) {
              $_SESSION['alert'] = '<div class="alert alert-success">Event successfully updated!</div>';
          } else {
              $_SESSION['alert'] = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
          }
          header('Location: ' . $_SERVER['PHP_SELF']);
          exit;
      } else {
          $_SESSION['alert'] = '<div class="alert alert-warning">Please provide a valid event description.</div>';
      }
  }

  if (isset($_POST['eventDescription'])) {
      if (!empty($_POST['eventDescription'])) {
          $eventDescription = mysqli_real_escape_string($conn, $_POST['eventDescription']);
          $query = "INSERT INTO events (description) VALUES ('$eventDescription')";

          if (mysqli_query($conn, $query)) {
              $_SESSION['alert'] = '<div class="alert alert-success">Event successfully added!</div>';
              header('Location: ' . $_SERVER['PHP_SELF']);
              exit;
          } else {
              $_SESSION['alert'] = '<div class="alert alert-danger">Error: ' . mysqli_error($conn) . '</div>';
          }
      } else {
          $_SESSION['alert'] = '<div class="alert alert-warning">Please provide an event description.</div>';
      }
  }
}

$alertMessage = isset($_SESSION['alert']) ? $_SESSION['alert'] : '';
unset($_SESSION['alert']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> ICT-Faculty Dashboard</title><link rel="icon" href="../img/favicon2.png">
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
.sidebar-logo img {
  display: block;
  transition: all 0.9s ease; /* Smooth transition */
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

.navbar-nav > li > a.btn {
        transition: none; /* Remove transition effect */
    }

    .navbar-nav > li > a.btn:hover {
        background-color: transparent; /* Remove background color on hover */
        color: inherit; /* Keep the text color the same on hover */
    }

    /* New styles to remove hover effect for logout button */
    .navbar-nav > li > a.btn.logout {
        background-color: transparent; /* Default background color */
        color: inherit; /* Default text color */
    }

    .navbar-nav > li > a.btn.logout:hover {
        background-color: transparent; /* Keep background transparent on hover */
        color: inherit; /* Keep text color same on hover */
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
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="hidden-xs">Hey! <?php echo htmlspecialchars($userRole); ?></span>
            </a>
        </li>
        <li>
            <a href="#" class="btn btn-default btn-flat logout" onclick="confirmLogout()">
                <i class="fa fa-sign-out"></i> Logout
            </a>
        </li>
    </ul>
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
            <li id="dashboard"><a href="ict_maintenance.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li id="about"><a href="academic_promoted_lists.php"><i class="fa fa-info-circle"></i> <span>Promoted Management</span></a></li>
            <li class="treeview">
    <a href="#">
        <i class="fa fa-folder"></i> <span>Pending List</span>
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
                <li id="student-maintenance-7"><a href="./ict_grade7_pending_list.php"><i class="fa fa-user"></i> Grade 7</a></li>
<li id="student-maintenance-8"><a href="./ict_grade8_pending_list.php"><i class="fa fa-user"></i> Grade 8</a></li>
<li id="student-maintenance-9"><a href="./ict_grade9_pending_list.php"><i class="fa fa-user"></i> Grade 9</a></li>
<li id="student-maintenance-10"><a href="ict_grade10_pending_list.php"><i class="fa fa-user"></i> Grade 10</a></li>

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
                <li id="student-maintenance-11"><a href="ict_grade11_pending_list.php"><i class="fa fa-user"></i> Grade 11</a></li>
                <li id="student-maintenance-12"><a href="ict_grade12_pending_list.php"><i class="fa fa-user"></i> Grade 12</a></li>

                </ul>
            </li>
      </ul>
</li>
<li class="treeview">
    <a href="#">
        <i class="fa fa-folder"></i> <span>Approved List</span>
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
                <li id="student-maintenance-7"><a href="./ict_grade7_approved_list.php"><i class="fa fa-user"></i> Grade 7</a></li>
<li id="student-maintenance-8"><a href="./ict_grade8_approved_list.php"><i class="fa fa-user"></i> Grade 8</a></li>
<li id="student-maintenance-9"><a href="./ict_grade9_approved_list.php"><i class="fa fa-user"></i> Grade 9</a></li>
<li id="student-maintenance-10"><a href="./ict_grade10_approved_list.php"><i class="fa fa-user"></i> Grade 10</a></li>

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
                <li id="student-maintenance-11"><a href="./ict_grade11_approved_list.php"><i class="fa fa-user"></i> Grade 11</a></li>
                <li id="student-maintenance-12"><a href="./ict_grade12_approved_list.php"><i class="fa fa-user"></i> Grade 12</a></li>

                </ul>
            </li>
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
        <li class="treeview">
            <a href="#">
                <i class="fa fa-file-text"></i> <span>Academic Records</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <!-- Junior HS Student Dropdown -->
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span>Junior HS Student</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li id="academic-grade7"><a href="academic_record_grade7.php"><i class="fa fa-user"></i> Grade 7</a></li>
                        <li id="academic-grade8"><a href="academic_record_grade8.php"><i class="fa fa-user"></i> Grade 8</a></li>
                        <li id="academic-grade9"><a href="academic_record_grade9.php"><i class="fa fa-user"></i> Grade 9</a></li>
                        <li id="academic-grade10"><a href="academic_record_grade10.php"><i class="fa fa-user"></i> Grade 10</a></li>
                    </ul>
                </li>

                <!-- Senior HS Student Dropdown -->
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span>Senior HS Student</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li id="academic-grade11"><a href="academic_record_grade11.php"><i class="fa fa-user"></i> Grade 11</a></li>
                        <li id="academic-grade12"><a href="academic_record_grade12.php"><i class="fa fa-user"></i> Grade 12</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-file-text"></i> <span>Form 137</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <!-- Junior HS Student Dropdown -->
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span>Junior HS Student</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li id="academic-grade7"><a href="form-137.php"><i class="fa fa-user"></i> Grade 7</a></li>
                        <li id="academic-grade8"><a href="form-137_8.php"><i class="fa fa-user"></i> Grade 8</a></li>
                        <li id="academic-grade9"><a href="form-137_9.php"><i class="fa fa-user"></i> Grade 9</a></li>
                        <li id="academic-grade10"><a href="form-137_10.php"><i class="fa fa-user"></i> Grade 10</a></li>
                    </ul>
                </li>

                <!-- Senior HS Student Dropdown -->
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span>Senior HS Student</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li id="academic-grade11"><a href="form-137_11.php"><i class="fa fa-user"></i> Grade 11</a></li>
                        <li id="academic-grade12"><a href="form-137_12.php"><i class="fa fa-user"></i> Grade 12</a></li>
                    </ul>
                </li>
            </ul>    </ul>
</li>


    </section>
</aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        MACAYO INTEGRATED SCHOOL
        <small>Overview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
      </ol>
    </section>

    <!-- Main content -->


    <section class="content">

    
       
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">

            <h3><?php echo $total_learners; ?></h3>
              
              <p>Total Students</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="#" class="small-box-footer"> <i class="fa fa-users"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
               <h3><?php echo $total_users; ?></h3>              

              <p>Total Users</p>
            </div>
            <div class="icon">
              <i class="fa fa-black-tie"></i>
            </div>
            <a href="#" class="small-box-footer"><i class="fa fa-black-tie"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $total_shs_subjects + $total_subjects; ?></h3>
              <p>Total Subjects</p>
            </div>
            <div class="icon">
              <i class="fa fa-book"></i>
            </div>
            <a href="#" class="small-box-footer"><i class="fa fa-book"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>21</h3>

              <p>Faculty Members</p>
            </div>
            <div class="icon">
              <i class="fa fa-female"></i>
            </div>
            <a href="#" class="small-box-footer"> <i class="fa fa-female"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
 
<!-- Announcements Section -->
<div class="row">
    <div class="col-lg-12">
        <div class="box box-info" style="border: 1px solid #3b5998; padding: 20px; border-radius: 8px;">
            <div class="box-header with-border">
                <h3 class="box-title" style="font-weight: bold; font-size: 20px; color: #3b5998;">Important Events</h3>
            </div>
            <div class="box-body">
                
                <!-- Event Form Section -->
                <?php if ($alertMessage): ?>
                    <div class="alert alert-info" style="background-color: #e9f7fe; color: #3b5998; border-radius: 8px; padding: 10px;">
                        <?php echo $alertMessage; ?>
                    </div>
                <?php endif; ?>

                <!-- Add Event Form -->
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="box box-info" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
                            <div class="box-body">
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="eventDescription" style="font-weight: bold; color: #3b5998;">Event Description:</label>
                                        <textarea class="form-control" id="eventDescription" name="eventDescription" rows="3" required style="border-radius: 8px; padding: 12px; border: 1px solid #ddd; transition: border-color 0.3s ease;"></textarea>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn" style="background-color: #3b5998; color: white; border-radius: 50px; padding: 8px 20px; font-size: 14px; font-weight: 600; text-transform: uppercase; transition: background-color 0.3s ease;" id="addEventBtn">Add Event</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event List Section -->
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="list-group" style="padding-left: 0;">
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <div class="list-group-item" style="background-color: #f7f7f7; border-radius: 12px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); transition: box-shadow 0.3s ease;">
                                        <?php
                                        $eventId = $row['id'];
                                        $eventDescription = htmlspecialchars($row['description']);
                                        ?>
                                        <?php if (isset($_GET['editEvent']) && $_GET['editEvent'] == $eventId): ?>
                                            <!-- Edit Form for Event -->
                                            <form method="POST">
                                                <input type="hidden" name="eventId" value="<?php echo $eventId; ?>" />
                                                <textarea name="eventDescription" class="form-control" required style="border-radius: 8px; padding: 12px;"><?php echo $eventDescription; ?></textarea>
                                                <button type="submit" name="editEvent" class="btn" style="background-color: #28a745; color: white; border-radius: 50px; padding: 8px 20px; font-size: 14px; font-weight: 600; text-transform: uppercase; margin-top: 10px;">Save Changes</button>
                                                <a href="?" class="btn" style="background-color: #6c757d; color: white; border-radius: 50px; padding: 8px 20px; font-size: 14px; font-weight: 600; text-transform: uppercase; margin-top: 10px;">Cancel</a>
                                            </form>
                                        <?php else: ?>
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <div style="font-weight: bold; font-size: 16px;"><?php echo 'Event ' . $eventId . ': ' . $eventDescription; ?></div>
                                                <div>
                                                    <a href="?editEvent=<?php echo $eventId; ?>" class="btn" style="background-color: #17a2b8; color: white; border-radius: 50px; padding: 6px 12px; font-size: 14px;">Edit</a>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="eventId" value="<?php echo $eventId; ?>" />
                                                        <button type="submit" name="deleteEvent" class="btn" style="background-color: #dc3545; color: white; border-radius: 50px; padding: 6px 12px; font-size: 14px;" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- No Events Available -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box box-warning" style="border-radius: 8px; border: 1px solid #ffc107; padding: 20px;">
                                <div class="box-header with-border">
                                    <h3 class="box-title" style="font-weight: bold; color: #ffc107;">No Events Available</h3>
                                </div>
                                <div class="box-body">
                                    <p>No important events have been scheduled.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<p class="text-right"><em>For the latest updates and announcements, follow us on our official Facebook page:</em></p>
    <div class="text-right">

        <a href="  https://www.facebook.com/MacayoIntegratedSchool" target="_blank" class="btn btn-primary">500376 Macayo Integrated School</a>
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
<script>
function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "login_page.php"; // Redirect to the logout page if confirmed
    }
}

// Existing JavaScript code for sidebar toggle and other features
$('.sidebar-toggle').on('click', function () {
    $('body').toggleClass('sidebar-collapse'); // Toggle the collapse class
});
</script>
</body>
</html>