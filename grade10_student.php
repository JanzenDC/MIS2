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
  $mname = $_POST['mname'];
  $lname = $_POST['lname'];
  $dob = $_POST['dob'];
  $address = $_POST['address'];
  $cont_num = $_POST['cont_num'];
  $religion = $_POST['religion'];
  $age = $_POST['age'];
  $gender = $_POST['gender'];
  $studentType = $_POST['studentType'];
  $schoolAttended = $_POST['schoolAttended'];
  $gradelevel = $_POST['gradelevel'];
  $guardianName = $_POST['guardianName'];
  $guardian = $_POST['guardian'];
  $curriculum = $_POST['curriculum'];
  $status = $_POST['status'];
  $exname = $_POST['exname'];
    
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
    $stmt = $conn->prepare("INSERT INTO learners (lrn, fname, lname, dob, address, cont_num, religion, age, gender, studentType, schoolAttended, gradelevel, guardianName, guardianRelationship, guardian, curriculum, sf10FilePath, imageFilePath, status, mname, exname) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssss", $lrn, $fname, $lname, $dob, $gender, $address, $cont_num, $religion, $age, $studentType, $schoolAttended, $gradelevel, $guardianName, $guardianRelationship, $guardian, $curriculum, $sf10FilePath, $imageFilePath, $mname, $status, $exname);
    
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
$result = $conn->query("SELECT * FROM learners WHERE grade_level = '10'");
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
  <title> Grade 10 Students</title><link rel="icon" href="../img/favicon2.png">
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
      <span class="logo-lg"><b>GRADE 10</b> Students</span>
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
            <li id="about"><a href="about.php"><i class="fa fa-info-circle"></i> <span>About</span></a></li>
        </ul>
        
    </section>
</aside>

  <div class="content-wrapper">
  <section class="content-header">
      <h1>
        MACAYO INTEGRATED SCHOOL
        <small>Grade 10 Students</small>
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
    data-mname="<?php echo $learner['middle_name']; ?>"
    data-lname="<?php echo $learner['last_name']; ?>"
    data-exname="<?php echo $learner['name_extension']; ?>"
    data-dob="<?php echo $learner['dob']; ?>"
    data-age="<?php echo $learner['age']; ?>"
    data-address="<?php echo $learner['address']; ?>"
    data-cont_num="<?php echo $learner['cont_num']; ?>"
    data-religion="<?php echo $learner['religion']; ?>"
    data-gender="<?php echo $learner['gender']; ?>"
    data-gradelevel="<?php echo $learner['grade_level']; ?>"
    data-curriculum="<?php echo $learner['curriculum']; ?>"
    data-schoolattended="<?php echo !empty($learner['other_school']) ? $learner['other_school'] : $learner['school_attended']; ?>"
    data-studenttype="<?php echo $learner['student_type']; ?>" 
    data-guardianname="<?php echo $learner['guardian_name']; ?>" 
    data-guardianrelationship="<?php echo !empty($learner['other_guardian']) ? $learner['other_guardian'] : $learner['guardian_relationship']; ?>" 
    data-status="<?php echo $learner['status']; ?>"
    data-image="<?php echo $learner['image_file']; ?>">
    <i class="fas fa-eye"></i>
</button>



   
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewModalLabel">Student Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center">
          <img id="studentImage" src="" alt="1x1 Picture" style="width: 100px; height: 100px; object-fit: cover; margin-bottom: 20px;">
        </div>
        <p><strong>LRN:</strong> <span id="studentLrn"></span></p>
        <p><strong>Full Name:</strong> <span id="studentName"></span></p>
        <p><strong>Date of Birth:</strong> <span id="studentDob"></span></p>
        <p><strong>Age:</strong> <span id="age"></span></p>
        <p><strong>Gender:</strong> <span id="studentGender"></span></p>
        <p><strong>Address:</strong> <span id="address"></span></p>
        <p><strong>Contact Number:</strong> <span id="cont_num"></span></p>
        <p><strong>Religion:</strong> <span id="religion"></span></p>
        <p><strong>Grade Level:</strong> <span id="studentGradeLevel"></span></p>
        <p><strong>Curriculum:</strong> <span id="studentCurriculum"></span></p>
        <p><strong>Elementary School Attended:</strong> <span id="studentschoolAttended"></span></p>
        <p><strong>Type of Learner:</strong> <span id="studentType"></span></p>
        <p><strong>Guardian Name:</strong> <span id="studentGuardianName"></span></p>
        <p><strong>Guardian Relationship:</strong> <span id="studentGuardianRelationship"></span></p>
        <p><strong>Status:</strong> <span id="status"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    var mname = $(this).data('mname');
    var lname = $(this).data('lname');
    var exname = $(this).data('exname');
    var dob = $(this).data('dob');
    var age = $(this).data('age');
    var address = $(this).data('address');
    var cont_num = $(this).data('cont_num');
    var religion = $(this).data('religion');
    var gender = $(this).data('gender');
    var gradeLevel = $(this).data('gradelevel');
    var curriculum = $(this).data('curriculum');
    var schoolAttended = $(this).data('schoolattended');
    var studentType = $(this).data('studenttype');
    var guardianName = $(this).data('guardianname');
    var guardianRelationship = $(this).data('guardianrelationship'); // Make sure the case is correct
    var status = $(this).data('status');
    var image = $(this).data('image');

    // Set modal content
    $('#studentLrn').text(lrn);
    $('#studentName').text(fname + ' ' + mname+ ' ' + lname + ' ' + exname);
    $('#studentDob').text(dob);
    $('#age').text(age);
    $('#studentGender').text(gender);
    $('#address').text(address);
    $('#cont_num').text(cont_num);
    $('#religion').text(religion);
    $('#studentGradeLevel').text(gradeLevel);
    $('#studentCurriculum').text(curriculum);
    $('#studentschoolAttended').text(schoolAttended);
    $('#studentType').text(studentType);
    $('#studentGuardianName').text(guardianName);
    $('#studentGuardianRelationship').text(guardianRelationship); // Ensure this is set correctly
    $('#status').text(status);
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
