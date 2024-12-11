<?php
// Include your database connection file
include 'db_connection.php'; // Ensure you have your database connection established

session_start(); // Start the session to access session variables
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session upon login

// Fetch user role only
$query = "SELECT assigned_to, role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $assigned_to = $user['assigned_to']; // fetch Grade7 - Grade12
    $userRole = $user['role']; // Fetch the user's role
} else {
    $assigned_to = "";
    $userRole = "No Role"; // Default role if not found
}
$grades = [
    'Grade7' => 'teacher_record_grade7.php',
    'Grade8' => 'teacher_record_grade8.php',
    'Grade9' => 'teacher_record_grade9.php',
    'Grade10' => 'teacher_record_grade10.php',
    'Grade11' => 'teacher_record_grade11.php',
    'Grade12' => 'teacher_record_grade12.php',
];
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
    $stmt = $conn->prepare("INSERT INTO learners (lrn, fname, lname, dob, gender, studentType, schoolAttended, gradelevel, guardianName, guardian, curriculum, sf10FilePath, imageFilePath, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssss", $lrn, $fname, $lname, $dob, $gender, $studentType, $schoolAttended, $gradelevel, $guardianName, $guardian, $curriculum, $sf10FilePath, $imageFilePath, $status);
    
    if ($stmt->execute()) {
        echo '<script>alert("Learner added successfully!");</script>';
    } else {
        echo '<script>alert("Error adding learner: ' . $stmt->error . '");</script>';
    }

    $stmt->close();
}
$students = [];
$assigned_grades = [];

// Split the assigned_to string by commas
$grades = explode(',', $assigned_to);

// Loop through each grade and extract the numeric part
foreach ($grades as $grade) {
    // Use regex to extract the number from the string
    if (preg_match('/\d+/', $grade, $matches)) {
        $assigned_grades[] = (int)$matches[0]; // Convert to integer and add to the array
    }
}// Assuming assigned_to is a comma-separated string

if (!empty($assigned_grades)) {
    $placeholders = implode(',', array_fill(0, count($assigned_grades), '?'));
    $query = "SELECT * FROM learners WHERE grade_level IN ($placeholders)";
    $stmt = $conn->prepare($query);
    
    $types = str_repeat('i', count($assigned_grades));
    $stmt->bind_param($types, ...$assigned_grades);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    $stmt->close();
}
// Fetch existing learners
$learners = [];
$result = $conn->query("SELECT * FROM learners WHERE grade_level = '7' AND status = 'Approved'");
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
  <title> Academic Records</title><link rel="icon" href="../img/favicon2.png">
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
      <span class="logo-lg"><b>Teacher</b> Dashboard</span>
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
            <li id="dashboard"><a href="teacher_dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
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
                                <?php foreach ($grades as $grade => $link): ?>
                                    <?php if (strpos($assigned_to, $grade) !== false): ?>
                                        <li id="<?= strtolower(str_replace(' ', '-', $grade)) ?>">
                                            <a href="<?= $link ?>"><i class="fa fa-user"></i> <?= $grade ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
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
                                <?php foreach ($grades as $grade => $link): ?>
                                    <?php if (strpos($assigned_to, $grade) !== false): ?>
                                        <li id="form-137-<?= strtolower(str_replace(' ', '-', $grade)) ?>">
                                            <a href="teacher_form-137_<?= strtolower(substr($grade, -1)) ?>.php"><i class="fa fa-user"></i> <?= $grade ?></a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper" style="font-family: Arial, sans-serif; margin: 20px;">
        <section class="content" style="margin-top: 30px; max-width: 800px; margin: auto; padding: 20px; background-color: #f9f9f9; border-radius: 8px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
            <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Students Assigned to You</h2>
            <div class="card" style="margin-bottom: 20px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; padding: 15px;">
                <div class="card-header" style="text-align: center;">
                    <h3 style="color: #555;">Total Students: <?= count($students) ?></h3>
                </div>
            </div>

            <?php if (!empty($students)): ?>
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px;">
                    <thead style="background-color: #f4f4f4; color: #333;">
                        <tr>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">LRN</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">First Name</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Last Name</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">DOB</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Gender</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Grade Level</th>
                            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['lrn']) ?></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['first_name']) ?></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['last_name']) ?></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['dob']) ?></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['gender']) ?></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['grade_level']) ?></td>
                                <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #888; font-size: 16px; margin-top: 20px;">No students assigned to you.</p>
            <?php endif; ?>
        </section>
    </div>


    

<script>
  document.querySelector('input[name="imageFile"]').addEventListener('change', function (e) {
    const reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('previewImage').src = e.target.result;
    };
    reader.readAsDataURL(e.target.files[0]);
  });
</script>




    
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

<!-- Scripts -->

<script>
  $(function () {
    $('.select2').select2();
    $('#datepicker').datepicker({
      autoclose: true
    });
  });
</script>



<script>
  $(function () {
    $('#example1').DataTable();
    $('.select2').select2();
  });
</script>

<script>
    function confirmAccept(id) {
        if (confirm('Are you sure you want to accept this student?')) {
            // Redirect to accept_student.php with the learner ID
            window.location.href = 'accept_student.php?id=' + id;
        }
    }

    function confirmReject(id) {
        if (confirm('Are you sure you want to reject this student?')) {
            // Redirect to reject_student.php with the learner ID
            window.location.href = 'reject_student.php?id=' + id;
        }
    }
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