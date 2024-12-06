<?php
session_start(); // Start the session

// Include your database connection file
require 'db_connection.php';

// Check if the user is logged in by checking if user_id exists in session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

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

$stmt->close(); // Close the statement

// Handle form submission for adding a new user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input values
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    // Validate input
    if (empty($email) || empty($password) || empty($role)) {
        $error_message = "All fields are required.";
    } else {
        // Check if the email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result(); // Store the result for checking if it exists

        if ($stmt->num_rows > 0) {
            // Email already exists
            $error_message = "Email already in use. Please choose another one.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement
            $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $success_message = "User added successfully.";
            } else {
                $error_message = "Error adding user: " . $conn->error;
            }
        }

        $stmt->close(); // Close the statement
    }
}

// Retrieve existing users from the database
$result = $conn->query("SELECT * FROM users");
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
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
    <title>Account Maintenance</title>
    <link rel="icon" href="../img/favicon2.png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
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
    <a href="#" class="logo">
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
                <li id="about"><a href="about.php"><i class="fa fa-info-circle"></i> <span>About</span></a></li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Account Maintenance
                <small>Adding User</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Account</a></li>
                <li class="active">Add User</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">New User</h3>
                        </div>
                        <form role="form" method="POST" action="">
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role" required>
                                        <option value="admin">Admin</option>
                                        <option value="ict_faculty">ICT Faculty</option>
                                        <option value="teacher">Teacher</option>
                                    </select>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-xs-8">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">List of Users</h3>
                        </div>
                        <div class="box-body">
                            <table id="userTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['id']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['role']) ?></td>
                                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                                        <td>
    <button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#editModal" onclick="loadEditUser(<?= $user['id'] ?>)">Edit</button>
    <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" onclick="setDeleteUserId(<?= $user['id'] ?>)">Delete</button>
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


    <!-- Edit User Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="edit_user.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="id" value="">
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" placeholder="Enter Email" required>
                    </div>
                    <div class="form-group">
                        <label for="editRole">Role</label>
                        <select class="form-control" id="editRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="ict_faculty">ICT Faculty</option>
                            <option value="teacher">Teacher</option>
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

<!-- Delete User Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="delete_user.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="deleteUserId" name="id" value="">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.4.0
        </div>
        <strong>&copy; 2024 <a href="#">Your School</a>.</strong> All rights reserved.
    </footer>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>




<script>
function loadEditUser(userId) {
    console.log("Loading user with ID:", userId); // Add this line
    $.ajax({
        url: 'get_user.php',
        type: 'GET',
        data: { id: userId },
        success: function(data) {
            console.log("Data received:", data); // Log the received data
            const user = JSON.parse(data);
            $('#editUserId').val(user.id);
            $('#editEmail').val(user.email);
            $('#editRole').val(user.role);
        },
        error: function() {
            alert('Error fetching user data.');
        }
    });
}

function setDeleteUserId(userId) {
    $('#deleteUserId').val(userId);
}
</script>

<script>
    $(document).ready(function () {
        // Initialize DataTable
        $('#userTable').DataTable({
            "searching": true,
            "ordering": true,
            "paging": true
        });
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
