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

// Fetching all subjects to display in the table
$result = $conn->query("SELECT * FROM subjects ORDER BY grade_holder DESC");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
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
    <title>Subject Maintenance</title>
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
</head>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="./" class="logo">
      <span class="logo-mini"><b>MIS</b></span>
      <span class="logo-lg"><b>GRADE 12</b> Students</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
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
                <li id="student-maintenance-7"><a href="grade7_student.php"><i class="fa fa-user"></i> Grade 7</a></li>
<li id="student-maintenance-8"><a href="grade8_student.php"><i class="fa fa-user"></i> Grade 8</a></li>
<li id="student-maintenance-9"><a href="grade9_student.php"><i class="fa fa-user"></i> Grade 9</a></li>
<li id="student-maintenance-10"><a href="grade10_student.php"><i class="fa fa-user"></i> Grade 10</a></li>

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
                <li id="student-maintenance-11"><a href="grade11_student.php"><i class="fa fa-user"></i> Grade 11</a></li>
                <li id="student-maintenance-12"><a href="grade12_student.php"><i class="fa fa-user"></i> Grade 12</a></li>

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
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> 
                    <span>School Forms</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-file-text"></i> 
                            <span>Academic Records</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-cogs"></i> 
                                    <span>Junior HS Student</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li id="academic-grade7">
                                        <a href="admin_record_grade7.php">
                                            <i class="fa fa-user"></i> Grade 7
                                        </a>
                                    </li>
                                    <li id="academic-grade8">
                                        <a href="admin_record_grade8.php">
                                            <i class="fa fa-user"></i> Grade 8
                                        </a>
                                    </li>
                                    <li id="academic-grade9">
                                        <a href="admin_record_grade9.php">
                                            <i class="fa fa-user"></i> Grade 9
                                        </a>
                                    </li>
                                    <li id="academic-grade10">
                                        <a href="admin_record_grade10.php">
                                            <i class="fa fa-user"></i> Grade 10
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-cogs"></i> 
                                    <span>Senior HS Student</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li id="academic-grade11">
                                        <a href="admin_record_grade11.php">
                                            <i class="fa fa-user"></i> Grade 11
                                        </a>
                                    </li>
                                    <li id="academic-grade12">
                                        <a href="admin_record_grade12.php">
                                             <i class="fa fa-user"></i> Grade 12
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-file-text"></i> 
                            <span>Form 137</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-cogs"></i> 
                                    <span>Junior HS Student</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li id="form137-grade7">
                                        <a href="admin_form-137.php">
                                            <i class="fa fa-user"></i> Grade 7
                                        </a>
                                    </li>
                                    <li id="form137-grade8">
                                        <a href="admin_form-137_8.php">
                                            <i class="fa fa-user"></i> Grade 8
                                        </a>
                                    </li>
                                    <li id="form137-grade9">
                                        <a href="admin_form-137_9.php">
                                            <i class="fa fa-user"></i> Grade 9
                                        </a>
                                    </li>
                                    <li id="form137-grade10">
                                        <a href="admin_form-137_10.php">
                                            <i class="fa fa-user"></i> Grade 10
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-cogs"></i> 
                                    <span>Senior HS Student</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li id="form137-grade11">
                                        <a href="admin_form-137_11.php">
                                            <i class="fa fa-user"></i> Grade 11
                                        </a>
                                    </li>
                                    <li id="form137-grade12">
                                        <a href="admin_form-137_12.php">
                                            <i class="fa fa-user"></i> Grade 12
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li id="about"><a href="about.php"><i class="fa fa-info-circle"></i> <span>About</span></a></li>
            <li id="about"><a href="card-maintenance.php"><i class="fa fa-info-circle"></i> <span>Card Maintenance</span></a></li>
            <li id="about"><a href="admin_promoted_lists.php"><i class="fa fa-info-circle"></i> <span>Promoted Management</span></a></li>
        </ul>
        
    </section>
</aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                JUNIOR HIGH SCHOOL
                <small>Adding Subject</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Subject</a></li>
                <li class="active">Add Subject</li>
            </ol>
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
                        <form role="form" method="POST" action="add_subject.php">
                            <div class="box-body">
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
                                    <input type="text" class="form-control" id="subjectName" name="subject_name" placeholder="Enter Subject" required>
                                </div>
                                <div class="form-group">
                                    <label for="subjectCode">Subject Description</label>
                                    <input type="text" class="form-control" id="subjectCode" name="subject_description" placeholder="Enter Code" required>
                                </div>
                                <div class="form-group">
                                    <label for="gradeLevel">Grade Level</label>
                                    <select name="grade_level" class="form-control select2" id="gradeLevel" required>
                                        <option value="" disabled selected>Select Grade Level</option>
                                        <option value="7">Grade 7</option>
                                        <option value="8">Grade 8</option>
                                        <option value="9">Grade 9</option>
                                        <option value="10">Grade 10</option>
                                        <option value="11">Grade 11</option>
                                        <option value="12">Grade 12</option>
                                    </select>
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
                        <div class="box-header">
                            <h3 class="box-title">List of Subjects</h3>
                        </div>
                        <div class="box-body">
                            <table id="subjectTable" class="table table-bordered table-hover">
                            <thead>
    <tr>
        <th>Curriculum</th>
        <th>Subject Name</th>
        <th>Subject Description</th>
        <th>Grade Level</th>
        <th style="width: 150px;">Action</th> <!-- Adjust the width as needed -->
    </tr>
</thead>
                                <tbody>
                                    <?php foreach ($subjects as $subject): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($subject['curriculum']) ?></td>
                                        <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                        <td><?= htmlspecialchars($subject['subject_description']) ?></td>
                                        <td><?= htmlspecialchars($subject['grade_holder']) ?></td>
                                        <td>
                                            <button class="btn btn-warning btn-sm edit-btn" data-id="<?= $subject['id'] ?>" data-name="<?= htmlspecialchars($subject['subject_name']) ?>" data-gradeholder="<?= htmlspecialchars($subject['grade_holder']) ?>" data-description="<?= htmlspecialchars($subject['subject_description']) ?>">Edit</button>
                                            <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $subject['id'] ?>" data-name="<?= htmlspecialchars($subject['subject_name']) ?>">Delete</button>
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
            <b>Version</b> 2.4.0
        </div>
        <strong>No Copyright Infringement &copy;.</strong> All rights reserved.
        </footer>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST" action="edit_subject.php">
                <div class="modal-body">
                    <input type="hidden" id="editSubjectId" name="id">
                    <div class="form-group">
                        <label for="editSubjectName">Subject Name</label>
                        <input type="text" class="form-control" id="editSubjectName" name="subject_name" required>
                    </div>
                    <div class="form-group">
                        <label for="editSubjectDescription">Subject Description</label>
                        <input type="text" class="form-control" id="editSubjectDescription" name="subject_description" required>
                    </div>
                    <div class="form-group">
                                    <label for="gradeLevel">Grade Level</label>
                                    <select name="editgrade_level" class="form-control select2" id="editgrade_level" required>
                                        <option value="" disabled selected>Select Grade Level</option>
                                        <option value="7">Grade 7</option>
                                        <option value="8">Grade 8</option>
                                        <option value="9">Grade 9</option>
                                        <option value="10">Grade 10</option>
                                        <option value="11">Grade 11</option>
                                        <option value="12">Grade 12</option>
                                    </select>
                                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteSubjectName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="delete_subject.php">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel  </button>

                    <input type="hidden" id="deleteSubjectId" name="id">
                    <button type="submit" class="btn btn-danger">Delete</button>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2();

    // Initialize DataTable
    $('#subjectTable').DataTable();

    // Edit button click
    $('.edit-btn').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const description = $(this).data('description');
        const grade_holder = $(this).data('gradeholder');
        $('#editSubjectId').val(id);
        $('#editSubjectName').val(name);
        $('#editSubjectDescription').val(description);
        $('#editgrade_level').val(grade_holder);
        $('#editModal').modal('show');
    });

    // Delete button click
    $('.delete-btn').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');

        $('#deleteSubjectId').val(id);
        $('#deleteSubjectName').text(name);

        $('#deleteModal').modal('show');
    });
});
</script>
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
