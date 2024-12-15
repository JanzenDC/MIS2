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
// Database connection (replace with your own connection parameters)
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "school_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $lrn = $_POST['lrn'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $studentType = $_POST['studentType'];
    $schoolAttended = $_POST['schoolAttended'];
    $gradelevel = $_POST['gradelevel'];
    $guardianName = $_POST['guardianName'];
    $guardian = $_POST['guardian'];
    $curriculum = $_POST['curriculum'];
    
    // Handle file uploads
    $sf10File = $_FILES['sf10File'];
    $imageFile = $_FILES['imageFile'];
    
    // Define the upload directory and file names
    $uploadDir = 'uploads/';
    $sf10FilePath = $uploadDir . basename($sf10File['name']);
    $imageFilePath = $uploadDir . basename($imageFile['name']);

    // Move uploaded files to the specified directory
    move_uploaded_file($sf10File['tmp_name'], $sf10FilePath);
    move_uploaded_file($imageFile['tmp_name'], $imageFilePath);

    // Insert the learner's data into the database
    $stmt = $conn->prepare("INSERT INTO learners (lrn, fname, lname, dob, gender, studentType, schoolAttended, gradelevel, guardianName, guardian, curriculum, sf10FilePath, imageFilePath) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssss", $lrn, $fname, $lname, $dob, $gender, $studentType, $schoolAttended, $gradelevel, $guardianName, $guardian, $curriculum, $sf10FilePath, $imageFilePath);
    
    // Removed alert messages
    if ($stmt->execute()) {
        // Optionally, you could log this action or handle it another way without user feedback.
    } else {
        // Optionally handle errors silently or log them.
    }

    $stmt->close();
}

// Fetch existing learners
$learners = [];
$result = $conn->query("SELECT * FROM learners WHERE grade_level = '12'");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $learners[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> Grade 12 Students</title><link rel="icon" href="../img/favicon2.png">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
  <script src="bower_components/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="bower_components/select2/dist/js/select2.full.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script src="dist/js/demo.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
  <script src="bower_components/datatables.net/js/jquery.dataTables.min.js">
  </script>
  <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>


<!-- Include Bootstrap CSS -->

<!-- Include Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<!-- Include Bootstrap JS -->

<!-- Include Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

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
        MACAYO INTEGRATED SCHOOL
        <small>Grade 12 Students</small>
      </h1>

    </section>
<!-- Trigger Button -->
<br>
<br>

    

<!-- Modal -->




<!-- Optional: include Bootstrap's JS & jQuery if not already included in your project -->


<!-- Script for image preview -->
<script>
  document.querySelector('input[name="imageFile"]').addEventListener('change', function (e) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('previewImage').src = e.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
  });
</script>


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
        <th class="text-center">#</th>
        <th class="text-center">1x1 Picture</th>
        <th class="text-center">LRN</th>
        <th class="text-center">Full Name</th>
        <th class="text-center">Date of Birth</th>
        <th class="text-center">Gender</th>
        <th class="text-center">Type of Learner</th>
        <th class="text-center">Elementary School Attended</th>
        <th class="text-center">Grade Level</th>
        <th class="text-center">Curriculum</th>
        <th class="text-center">SF10</th>
        <th class="text-center">Action</th>
    </tr>
</thead>
<tbody>
        <?php foreach ($learners as $index => $learner): ?>
            <tr>
                <td class="text-center"><?php echo ($index + 1); ?></td>
                <td class="text-center">
                    <?php if (!empty($learner['image_file'])): ?>
                        <img src="<?php echo $learner['image_file']; ?>" alt="1x1 Picture" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php else: ?>
                        No image found
                    <?php endif; ?>
                </td>
                <td class="text-center"><?php echo $learner['lrn']; ?></td>
                <td class="text-center"><?php echo $learner['first_name'] . ' ' . $learner['last_name']; ?></td>
                <td class="text-center"><?php echo $learner['dob']; ?></td>
                <td class="text-center"><?php echo $learner['gender']; ?></td>
                <td class="text-center"><?php echo $learner['student_type']; ?></td>
                <td class="text-center"><?php echo $learner['school_attended']; ?></td>
                <td class="text-center"><?php echo $learner['grade_level']; ?></td>
                <td class="text-center"><?php echo $learner['curriculum']; ?></td>
                <td class="text-center">
                    <?php if (!empty($learner['sf10_file'])): ?>
                        <a href="<?php echo $learner['sf10_file']; ?>" target="_blank">View</a>
                    <?php else: ?>
                        No SF10 file found
                    <?php endif; ?>
                </td>
               <td class="text-center">
               <button class="btn btn-info btn-sm view-btn" data-toggle="modal" data-target="#viewModal"
                    data-id="<?php echo $learner['id']; ?>"
                    data-lrn="<?php echo $learner['lrn']; ?>"
                    data-fname="<?php echo $learner['first_name']; ?>"
                    data-lname="<?php echo $learner['last_name']; ?>"
                    data-dob="<?php echo $learner['dob']; ?>"
                    data-gender="<?php echo $learner['gender']; ?>"
                    data-gradelevel="<?php echo $learner['grade_level']; ?>"
                    data-curriculum="<?php echo $learner['curriculum']; ?>"
                    data-schoolattended="<?php echo !empty($learner['other_school']) ? $learner['other_school'] : $learner['school_attended']; ?>"
                    data-studenttype="<?php echo $learner['student_type']; ?>" 
                    data-guardianname="<?php echo $learner['guardian_name']; ?>" 
                    data-guardianrelationship="<?php echo !empty($learner['other_guardian']) ? $learner['other_guardian'] : $learner['guardian_relationship']; ?>" 
                    data-image="<?php echo $learner['image_file']; ?>">
                    <i class="fas fa-eye"></i>
                </button>



   
    
    <button class="btn btn-danger btn-sm delete-btn" 
        data-id="<?php echo $learner['id']; ?>" 
        onclick="confirmDelete(<?php echo $learner['id']; ?>)">
        <i class="fas fa-trash"></i>
    </button>

                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  
   <!-- Modal for Viewing Student Details -->
   <div id="viewModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
            <div class="modal-content" style="border-radius: 15px; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);">
                <!-- Modal Header -->
                <div class="modal-header" style="background-color: #007bff; color: white; border-top-left-radius: 15px; border-top-right-radius: 15px; text-align: center;">
                    <h2 class="modal-title" id="viewModalLabel">Student Information</h2>
                </div>

                <!-- Modal Body -->
                <div class="modal-body" style="padding: 30px;">
                    <div class="text-center mb-4">
                        <img id="studentImage" src="" alt="1x1 Picture" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 3px solid #007bff;">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">LRN:</label>
                            <span id="studentLrn" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Full Name:</label>
                            <span id="studentName" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date of Birth:</label>
                            <span id="studentDob" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Age:</label>
                            <span id="age" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Gender:</label>
                            <span id="studentGender" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Address:</label>
                            <span id="address" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Contact Number:</label>
                            <span id="cont_num" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Religion:</label>
                            <span id="religion" class="d-block text-muted"></span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Grade Level:</label>
                            <span id="studentGradeLevel" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Curriculum:</label>
                            <span id="studentCurriculum" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Elementary School Attended:</label>
                            <span id="studentschoolAttended" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Type of Learner:</label>
                            <span id="studentType" class="d-block text-muted"></span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Guardian Name:</label>
                            <span id="studentGuardianName" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Guardian Relationship:</label>
                            <span id="studentGuardianRelationship" class="d-block text-muted"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <span id="status" class="d-block text-muted"></span>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer" style="background-color: #f1f1f1; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 5px;">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="bower_components/datatables.net/js/jquery.dataTables.min.js">
    </script>
    <script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <footer class="main-footer">
      <div class="pull-right hidden-xs">
        <b>Version</b> 1.0
      </div>
      <strong>No Copyright Infringement &copy;.</strong> All rights reserved.
    </footer>
  </div>

<script>
   $(document).on('click', '.view-btn', function() {
    var id = $(this).data('id');
    var lrn = $(this).data('lrn');
    var fname = $(this).data('fname');
    var lname = $(this).data('lname');
    var dob = $(this).data('dob');
    var gender = $(this).data('gender');
    var gradeLevel = $(this).data('gradelevel');
    var curriculum = $(this).data('curriculum');
    var schoolAttended = $(this).data('schoolattended');
    var studentType = $(this).data('studenttype');
    var guardianName = $(this).data('guardianname');
    var guardianRelationship = $(this).data('guardianrelationship'); // Make sure the case is correct
    var image = $(this).data('image');

    // Set modal content
    $('#studentLrn').text(lrn);
    $('#studentName').text(fname + ' ' + lname);
    $('#studentDob').text(dob);
    $('#studentGender').text(gender);
    $('#studentGradeLevel').text(gradeLevel);
    $('#studentCurriculum').text(curriculum);
    $('#studentschoolAttended').text(schoolAttended);
    $('#studentType').text(studentType);
    $('#studentGuardianName').text(guardianName);
    $('#studentGuardianRelationship').text(guardianRelationship); // Ensure this is set correctly
    $('#studentImage').attr('src', image ? image : 'dist/img/default.png');
});

  </script>

<script>
  $(function () {
    $('.select2').select2();
    $('#datepicker').datepicker({
      autoclose: true
    });
  });
</script>
<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this student?')) {
            // Redirect to delete_student.php with the learner ID
            window.location.href = 'delete_student.php?id=' + id;
        }
    }
</script>
<script>
    $(document).ready(function() {
        <?php if (isset($_SESSION['message'])): ?>
            $('#successModal').modal('show');
        <?php endif; ?>
    });
  </script>


<script>
  $(function () {
    $('#example1').DataTable();
    $('.select2').select2();
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
