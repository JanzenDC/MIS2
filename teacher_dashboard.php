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
    $query = "SELECT * FROM learners WHERE grade_level IN ($placeholders) AND status = 'Approved'";
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
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
                                <?php if (strpos($assigned_to, 'Grade7') !== false): ?>
                                    <li id="grade7">
                                        <a href="teacher_record_grade7.php"><i class="fa fa-user"></i> Grade 7</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade8') !== false): ?>
                                    <li id="grade8">
                                        <a href="teacher_record_grade8.php"><i class="fa fa-user"></i> Grade 8</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade9') !== false): ?>
                                    <li id="grade9">
                                        <a href="teacher_record_grade9.php"><i class="fa fa-user"></i> Grade 9</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade10') !== false): ?>
                                    <li id="grade10">
                                        <a href="teacher_record_grade10.php"><i class="fa fa-user"></i> Grade 10</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade11') !== false): ?>
                                    <li id="grade11">
                                        <a href="teacher_record_grade11.php"><i class="fa fa-user"></i> Grade 11</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade12') !== false): ?>
                                    <li id="grade12">
                                        <a href="teacher_record_grade12.php"><i class="fa fa-user"></i> Grade 12</a>
                                    </li>
                                <?php endif; ?>
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
                                <?php if (strpos($assigned_to, 'Grade7') !== false): ?>
                                    <li id="form-137-grade7">
                                        <a href="teacher_form-137_7.php"><i class="fa fa-user"></i> Grade 7</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade8') !== false): ?>
                                    <li id="form-137-grade8">
                                        <a href="teacher_form-137_8.php"><i class="fa fa-user"></i> Grade 8</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade9') !== false): ?>
                                    <li id="form-137-grade9">
                                        <a href="teacher_form-137_9.php"><i class="fa fa-user"></i> Grade 9</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade10') !== false): ?>
                                    <li id="form-137-grade10">
                                        <a href="teacher_form-137_10.php"><i class="fa fa-user"></i> Grade 10</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade11') !== false): ?>
                                    <li id="form-137-grade11">
                                        <a href="teacher_form-137_11.php"><i class="fa fa-user"></i> Grade 11</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (strpos($assigned_to, 'Grade12') !== false): ?>
                                    <li id="form-137-grade12">
                                        <a href="teacher_form-137_12.php"><i class="fa fa-user"></i> Grade 12</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>

    <div class="content-wrapper" style="font-family: Arial, sans-serif; display: flex; align-items: center; justify-content: center; margin: 0; background-color: #f0f4f8;">
    <section class="content" style="width: 100%; max-width: 1200px; margin-left: 400px; padding: 30px; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
        <h2 style="text-align: center; color: #2c3e50; font-size: 24px; margin-bottom: 25px; font-weight: bold;">Students Assigned to You</h2>
        
        <div class="card" style="margin-bottom: 25px; background-color: #ecf0f1; border: none; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <h3 style="color: #34495e; font-size: 20px; font-weight: 500;">Total Students: <?= count($students) ?></h3>
        </div>

        <?php if (!empty($students)): ?>
            <table id="studentTable"     style="width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                <thead style="background-color: #3498db; color: #ffffff;">
                    <tr>
                        <th style="padding: 12px; text-align: left;">LRN</th>
                        <th style="padding: 12px; text-align: left;">First Name</th>
                        <th style="padding: 12px; text-align: left;">Middle Name</th>
                        <th style="padding: 12px; text-align: left;">Last Name</th>
                        <th style="padding: 12px; text-align: left;">Extension Name</th>
                        <th style="padding: 12px; text-align: left;">Date of Birth</th>
                        <th style="padding: 12px; text-align: left;">Age</th>
                        <th style="padding: 12px; text-align: left;">Gender</th>
                        <th style="padding: 12px; text-align: left;">Address</th>
                        <th style="padding: 12px; text-align: left;">Grade Level</th>
                        <th style="padding: 12px; text-align: left;">Guardian</th>
                        <th style="padding: 12px; text-align: left;">Guardian Relationship</th>
                        <th style="padding: 12px; text-align: left;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr style="background-color: <?= $loopIndex % 2 === 0 ? '#ecf0f1' : '#ffffff'; ?>; color: #34495e;">
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['lrn']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['first_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['middle_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['last_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['name_extension']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['dob']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['age']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['gender']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['address']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['grade_level']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['guardian_name']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['guardian_relationship']) ?></td>
                            <td style="padding: 10px; border: 1px solid #ddd;"><?= htmlspecialchars($student['status']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; color: #7f8c8d; font-size: 16px; margin-top: 20px;">No students assigned to you.</p>
        <?php endif; ?>
    </section>
</div>




<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Include DataTables and Buttons extensions -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>


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
$(document).ready(function () {
    $('#studentTable').DataTable({
        dom: 'Bfrtip', // Define the buttons container
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Student Data'
            },
            {
                extend: 'pdfHtml5',
                title: 'Student Data',
                orientation: 'landscape', // Optional: landscape or portrait
                pageSize: 'A4' // Paper size
            },
            {
                extend: 'print',
                title: 'Student Data'
            }
        ],
        paging: true,
        searching: true,
        order: [[0, 'asc']], // Default sorting by the first column
        responsive: true
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
