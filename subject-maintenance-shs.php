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
?>

<?php
// Database configuration
$host = 'localhost'; // Change as needed
$dbname = 'school_db'; // Change to your database name
$username = 'root'; // Change to your database username
$password = ''; // Change to your database password

// Create a new connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize subjects array
$subjects = [];
$shs_subjects = []; // Array for Senior High School subjects

// Fetching all subjects from junior high
$result = $conn->query("SELECT * FROM subjects");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Fetching all subjects from senior high
$result_shs = $conn->query("SELECT * FROM shs_subjects");

if ($result_shs->num_rows > 0) {
    while ($row = $result_shs->fetch_assoc()) {
        $shs_subjects[] = $row;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Subject Maintenance SHS</title>
    <link rel="icon" href="../img/favicon2.png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
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
      <span class="logo-lg"><b>Subject</b> Maintenance</span>
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
                </ul>
            </div>
        </nav>
    </header>

    <aside class="main-sidebar">
        <section class="sidebar">
            <div class="sidebar-logo" style="text-align: center; padding: 10px;">
            <img id="sidebar-logo" src="dist/img/macayo_logo.png" alt="DepEd Logo" style="max-width: 100px; margin-left: 50px; transition: all 0.9s ease;">
            </div>
            <ul class="sidebar-menu" data-widget="tree">
                <li id="dashboard"><a href="./"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-folder"></i> <span>Student Status</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
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
                <li id="about"><a href="card-maintenance.php"><i class="fa fa-info-circle"></i> <span>Card Maintenance</span></a></li>

            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                SENIOR HIGH SCHOOL
                <small>Adding Subject</small>
            </h1>

        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-4">
                    <style>
                        #truemsg {
                            transition: opacity 0.5s ease-out; /* Adjust the duration as needed */
                        }
                    </style>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">New Subject</h3>
                        </div>
                        <form role="form" method="POST" action="add_shs_subject.php">
                            <div class="box-body">
                            <div class="form-group">
    <label for="gradeLevel">Grade Level</label>
    <select name="grade_level" class="form-control select2" id="gradeLevel" required>
        <option value="" disabled selected>Select Grade Level</option>
        <option value="11">Grade 11</option>
        <option value="12">Grade 12</option>
    </select>
</div>

<div class="form-group">
    <label for="semester">Semester</label>
    <select name="semester" class="form-control select2" id="semester" required>
        <option value="" disabled selected>Select Semester</option>
        <option value="1">First Sem</option>
        <option value="2">Second Sem</option>
    </select>
</div>
                                <div class="form-group">
                                    <label for="curriculumID">Curriculum</label>
                                    <select name="curriculum_id" class="form-control select2" id="curriculumID" required>
                                        <option value="" disabled selected>Select Curriculum</option>
                                        <option value="k12">K-12</option>
                                        <option value="DepEd Matatag">DepEd Matatag</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subjectName">Subject Name</label>
                                    <input type="text" class="form-control" name="subject_name" id="subjectName" placeholder="Enter subject name" required>
                                </div>
                                <div class="form-group">
                                    <label for="subjectDescription">Subject Description</label>
                                    <textarea class="form-control" name="subject_description" id="subjectDescription" placeholder="Enter subject description" required></textarea>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Add Subject</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Subjects List</h3>
                        </div>
                        <div class="box-body">
                            <table id="subjectList" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                    <th>Grade Level</th>
                                    <th>Semester</th>
                                        <th>Curriculum</th>
                                        <th>Subject Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($shs_subjects as $subject): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($subject['grade_level']); ?></td>
                                            <td><?php echo htmlspecialchars($subject['semester']); ?></td>
                                            <td><?php echo htmlspecialchars($subject['curriculum']); ?></td>
                                            <td><?php echo htmlspecialchars($subject['subject_name']); ?></td>
                                            <td><?php echo htmlspecialchars($subject['subject_description']); ?></td>
                                            <td>
                                                <a href="edit_shs_subject.php?id=<?php echo $subject['id']; ?>" class="btn btn-warning btn-xs">Edit</a>
                                                <a href="delete_shs_subject.php?id=<?php echo $subject['id']; ?>" class="btn btn-danger btn-xs">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
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
            <b>Version</b> 2.3.11
        </div>
        <strong>Copyright &copy; 2024 <a href="#">Your School</a>.</strong> All rights reserved.
    </footer>

    <div class="control-sidebar-bg"></div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
    $(function () {
        $('.select2').select2();
        $('#subjectList').DataTable();
    });
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
