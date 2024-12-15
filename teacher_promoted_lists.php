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

session_start();
$userId = $_SESSION['user_id']; 
$assigned_to = "";
$gradeNumber = null; // Variable to store extracted numeric value

// Fetch user role and assigned grades
$query = "SELECT assigned_to, role FROM users WHERE id = '$userId'";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $assigned_to = trim($user['assigned_to']);
    $userRole = $user['role']; 

    // Extract numeric grade from assigned_to
    if (preg_match('/\d+/', $assigned_to, $matches)) {
        $gradeNumber = $matches[0]; // Store numeric part in a variable
    }

} else {
    $userRole = "No Role"; 
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

// Fetch existing learners
$learners = [];
$result = $conn->query("
SELECT * FROM promoted_student_tbl pr
LEFT JOIN learners le ON pr.learnersID = le.lrn WHERE le.grade_level = '$gradeNumber'
");
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
  <title> Form 137</title><link rel="icon" href="../img/favicon2.png">
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
      <span class="logo-lg"><b>GRADE 8</b> Students</span>
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
            <a href="logout.php" class="btn btn-default btn-flat logout" onclick="confirmLogout()">
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
    <li id="about"><a href="teacher_promoted_lists.php"><i class="fa fa-info-circle"></i> <span>Promoted Management</span></a></li>
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

  <div class="content-wrapper">
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Student</a></li>
        <li class="active"></li>
      </ol>
    </section>
<!-- Trigger Button -->
<!-- Trigger Button -->
<br>
<div class="text-center">
  <h2>Promoted/Non Promoted Lists</h2>
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


<section class="content">
            <div class="row">
                <div class="col-xs-12 ">
                    <div class="alert alert-success alert-dismissible" style="display: none;" id="truemsg">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> Success!</h4>
                        New Subject Successfully added
                    </div>

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
            <th class="text-center">LRN</th>
            <th class="text-center">Full Name</th>
            <th class="text-center">Date of Birth</th>
            <th class="text-center">Gender</th>
            <th class="text-center">Promoted to</th>
            <th class="text-center">Approve Status Teacher</th>
            <th class="text-center">Approve Status ICT</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($learners as $index => $learner): ?>
            <tr>
                <td class="text-center"><?php echo ($index + 1); ?></td>
                <td class="text-center"><?php echo $learner['lrn']; ?></td>
                <td class="text-center"><?php echo $learner['first_name'] . ' ' . $learner['last_name']; ?></td>
                <td class="text-center"><?php echo $learner['dob']; ?></td>
                <td class="text-center"><?php echo $learner['gender']; ?></td>
                <td class="text-center"><?php echo $learner['promotedTo']; ?></td>

                <!-- Approve Status Teacher -->
                <td class="text-center">
                    <?php if ($learner['approveStatus'] == 1): ?>
                        <span style="color: green; font-weight: bold;">Approved</span>
                    <?php elseif ($learner['approveStatus'] == 0): ?>
                        <span style="color: orange; font-weight: bold;">Pending</span>
                    <?php elseif ($learner['approveStatus'] == 2): ?>
                        <span style="color: red; font-weight: bold;">Disapproved</span>
                    <?php endif; ?>
                </td>

                <!-- Approve Status ICT -->
                <td class="text-center">
                    <?php if ($learner['promotedStatus'] == 1): ?>
                        <span style="color: green; font-weight: bold;">Approved</span>
                    <?php elseif ($learner['promotedStatus'] == 0): ?>
                        <span style="color: orange; font-weight: bold;">Pending</span>
                    <?php elseif ($learner['promotedStatus'] == 2): ?>
                        <span style="color: red; font-weight: bold;">Disapproved</span>
                    <?php endif; ?>
                </td>

                
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>



                                <tbody>
                                    <!-- Populate with subjects from database -->
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
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
    $('#example1').DataTable({
        dom: 'Bfrtip', // Define the buttons container
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Student Data',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7] // Specify the columns to include (index-based)
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'Student Data',
                orientation: 'landscape', // Optional: landscape or portrait
                pageSize: 'A4', // Paper size
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7] // Specify the columns to include (index-based)
                }
            },
            {
                extend: 'print',
                title: 'Student Data',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7] // Specify the columns to include (index-based)
                }
            }
        ],
        paging: true,
        searching: true,
        order: [[0, 'asc']], // Default sorting by the first column
        responsive: true
    });

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
        window.location.href = "logout.php"; // Redirect to the logout page if confirmed
    }
}

// Existing JavaScript code for sidebar toggle and other features
$('.sidebar-toggle').on('click', function () {
    $('body').toggleClass('sidebar-collapse'); // Toggle the collapse class
});
</script>
</body>

</html>
