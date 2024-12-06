<?php
session_start(); // Start the session

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in by checking if user_id exists in session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$userId = $_SESSION['user_id']; // Assuming user ID is stored in session upon login

// Fetch user role
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

// Fetch subjects from the database (use `shs_subjects` table)
$subjects = [];
$stmt = $conn->prepare("SELECT id, subject_name FROM shs_subjects"); // Changed to 'shs_subjects'
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $subjects[$row['id']] = $row['subject_name']; // Store subject id and name
}
$stmt->close(); // Close the statement for fetching subjects

// Fetch academic records and learner's name
$lrn = isset($_GET['lrn']) ? $_GET['lrn'] : '';
$first_name = '';
$last_name = '';
$dob = ''; // Initialize DOB
$gender = ''; // Initialize gender
$grade_level = ''; // Initialize grade level
$grades = [];
$generalAverage = null; // Initialize general average
$adviser = ''; // Initialize adviser
$school_year = ''; // Initialize school year
$section = ''; // Initialize section

if ($lrn) {
    // Fetch the learner's first name, last name, dob, gender, and grade level
    $stmt = $conn->prepare("SELECT first_name, last_name, dob, gender, grade_level FROM learners WHERE lrn = ?");
    $stmt->bind_param("s", $lrn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $dob = $row['dob']; // Fetch DOB
        $gender = $row['gender']; // Fetch gender
        $grade_level = $row['grade_level'];
    }
    $stmt->close(); // Close the statement for fetching learner details

    // Fetch academic records (grades) for the learner from the `shs_grades` table
    $stmt = $conn->prepare("SELECT subject_id, first_grading, second_grading, third_grading, fourth_grading, final_grade, status, general_average, adviser, school_year, section FROM shs_grades WHERE lrn = ?");
    $stmt->bind_param("s", $lrn);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $grades[$row['subject_id']] = $row; // Store each subject's grading data using subject_id
        $generalAverage = $row['general_average']; // Fetch the general average
        
        // Fetch adviser, school year, and section for display
        $adviser = $row['adviser'];
        $school_year = $row['school_year'];
        $section = $row['section'];
    }
    $stmt->close(); // Close the statement for fetching grades
}

$conn->close(); // Close the database connection
?>



<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> View Records</title><link rel="icon" href="../img/favicon2.png">
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

        /* Main container styling */
.content {
    font-family: Arial, sans-serif;
   
}

/* Box styling */
.box {
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
}

/* Header styling */
.box-header h3 {
    margin: 0;
    font-weight: bold;
    font-size: 18px;
    text-align: center;
}

/* Table layout styling */
.grade-table table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

/* Table header styling */
.grade-table th {
    background-color:#d9b783;
    color: #000;
    font-weight: bold;
    text-align: center;
    border: 1px solid #000;
    padding: 8px;
}

.grade-table th[colspan="4"] {
    background-color: #d9b783;
}

/* Table cell styling */
.grade-table td {
    border: 1px solid #000;
    padding: 8px;
    text-align: center;
}

/* Input fields in table - invisible box */
.grade-table .grade-input {
    width: 100%;
    padding: 4px;
    box-sizing: border-box;
    text-align: center;
    border: none; /* Removes the border */
    background-color: transparent; /* Makes the background transparent */
    outline: none; /* Removes the focus outline */
}


/* Footer row for General Average */
.grade-table tfoot td {
    font-weight: bold;
    background-color: #f9f9f9;
}

/* Learning Modality and Grading Scale table styling */
.grade-table table + table {
    margin-top: 10px;
}

.grade-table table + table th,
.grade-table table + table td {
    border: 1px solid #000;
    text-align: center;
    padding: 8px;
}

.grade-table table + table th {
    background-color:#d9b783;
}

/* Button styling */
.btn-primary {
    background-color: #337ab7;
    border-color: #2e6da4;
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    display: block;
    margin: 20px auto;
}

.btn-primary:hover {
    background-color: #286090;
    border-color: #204d74;
}

/* Text alignment adjustments */
.text-right {
    text-align: right;
}

  /* Aligns the "General Average" label to the bottom right */
.text-right {
    text-align: right;
}
  
  </style>
</head>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <span class="logo-mini"><b>MIS</b></span>
            <span class="logo-lg"><b>Student </b> Grading</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
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
        </nav>
    </header>

    <aside class="main-sidebar">
  <section class="sidebar">
    <!-- Logo Section -->
    <div class="sidebar-logo" style="text-align: center; padding: 10px;">
      <img id="sidebar-logo" src="dist/img/macayo_logo.png" alt="DepEd Logo" style="max-width: 100px; margin-left: 50px; transition: all 0.9s ease;">
    </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li id="dashboard"><a href="ict_maintenance.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
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


<div class="content-wrapper">
<section class="content-header">
    <div style="padding: 20px; width: 950px;">
        <!-- First Row: Last Name and First Name -->
        <div style="display: flex; margin-bottom: 10px;">
            <div style="flex: 1; margin-right: -100px;">
                <strong>LAST NAME:</strong>
                <span style="display: inline-block; width: 200px;"><?php echo htmlspecialchars($last_name); ?></span>
            </div>
            <div style="flex: 1;">
                <strong>FIRST NAME:</strong>
                <span style="display: inline-block; width: 200px;"><?php echo htmlspecialchars($first_name); ?></span>
            </div>
        </div>

        <!-- Second Row: LRN, Birthdate, and Sex -->
        <div style="display: flex;">
            <div style="flex: 1; margin-right: -100px;">
                <strong>LEARNER REFERENCE NUMBER (LRN):</strong>
                <span style="display: inline-block; width: 200px;"><?php echo htmlspecialchars($lrn); ?></span>
            </div>
            <div style="flex: 1; display: flex; flex-direction: column;">
                <div style="margin-bottom: 5px;">
                    <strong>BIRTHDATE:</strong>
                    <span style="display: inline-block; width: 100px;"><?php echo htmlspecialchars($dob); ?></span>
                </div>
                <div>
                    <strong>SEX:</strong>
                    <span style="display: inline-block; width: 60px;"><?php echo htmlspecialchars($gender); ?></span>
                </div>
            </div>
        </div>

        <!-- Third Row: Grade Level -->
        <div style="display: flex; margin-top: -20px;">
            <div style="flex: 1;">
                <strong>GRADE LEVEL:</strong>
                <span style="display: inline-block; width: 200px;"><?php echo htmlspecialchars($grade_level); ?></span>
            </div>
        </div>
    </div>
</section>
    
    <section class="content">
    <div class="box box-primary">
        <div class="box-header with-border">
            <center><h3 class="box-title">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</h3></center>
        </div>
        

        <div class="table table-bordered grade-table">
            <form action="process_grades_shs.php" method="post">
                <input type="hidden" name="lrn" value="<?php echo htmlspecialchars($lrn); ?>">
                <!-- Adviser, School Year, and Section Row -->
<div style="display: flex; margin-bottom: 10px; margin-left: 35px;">
    <div style="margin-right: 10px;">
        <strong>ADVISER:</strong>
        <input type="text" name="adviser" value="<?php echo isset($adviser) ? htmlspecialchars($adviser) : ''; ?>" 
               style="width: 200px; border: none; border-bottom: 1px solid #000; outline: none;" required>
    </div>
    <div style="margin-right: 10px;">
        <strong>SCHOOL YEAR:</strong>
        <input type="text" name="school_year" value="<?php echo isset($school_year) ? htmlspecialchars($school_year) : ''; ?>" 
               style="width: 200px; border: none; border-bottom: 1px solid #000; outline: none;" required>
    </div>
    <div>
        <strong>SECTION:</strong>
        <input type="text" name="section" value="<?php echo isset($section) ? htmlspecialchars($section) : ''; ?>" 
               style="width: 200px; border: none; border-bottom: 1px solid #000; outline: none;" required>
    </div>
</div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">Learning Areas</th>
                            <th colspan="4">Quarter</th>
                            <th rowspan="2">Final Rating</th>
                            <th rowspan="2">Remarks</th>
                        </tr>
                        <tr>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subjects as $subjectId => $subjectName): 
                            $subjectGrades = isset($grades[$subjectId]) ? $grades[$subjectId] : [
                                'first_grading' => null, 
                                'second_grading' => null, 
                                'third_grading' => null, 
                                'fourth_grading' => null, 
                                'final_grade' => null, 
                                'status' => null
                            ];
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($subjectName); ?></td>
                                <td>
    <input type="number" name="grades[<?php echo htmlspecialchars($subjectId); ?>][first]" class="grade-input" min="0" max="100" 
    value="<?php echo isset($subjectGrades['first_grading']) ? $subjectGrades['first_grading'] : ''; ?>" 
    oninput="computeFinalGrade(this)">
</td>
<td>
    <input type="number" name="grades[<?php echo htmlspecialchars($subjectId); ?>][second]" class="grade-input" min="0" max="100"
    value="<?php echo isset($subjectGrades['second_grading']) ? $subjectGrades['second_grading'] : ''; ?>" 
    oninput="computeFinalGrade(this)">
</td>
<td>
    <input type="number" name="grades[<?php echo htmlspecialchars($subjectId); ?>][third]" class="grade-input" min="0" max="100"
    value="<?php echo isset($subjectGrades['third_grading']) ? $subjectGrades['third_grading'] : ''; ?>" 
    oninput="computeFinalGrade(this)">
</td>
<td>
    <input type="number" name="grades[<?php echo htmlspecialchars($subjectId); ?>][fourth]" class="grade-input" min="0" max="100"
    value="<?php echo isset($subjectGrades['fourth_grading']) ? $subjectGrades['fourth_grading'] : ''; ?>" 
    oninput="computeFinalGrade(this)">
</td>

                                <td>
                                    <?php echo isset($subjectGrades['final_grade']) ? $subjectGrades['final_grade'] : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($subjectGrades['status']) ? htmlspecialchars($subjectGrades['status']) : ''; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
    <tr>
        <td colspan="4"></td>
        <td class="text-right"><strong>General Average:</strong></td>
        <td><?php echo isset($generalAverage) ? htmlspecialchars($generalAverage) : ''; ?></td>
    </tr>
</tfoot>

                </table>
                <table class="table table-bordered">
                    <tr>
                        <th>Description</th>
                        <th>Grading Scale</th>
                        <th>Remarks</th>
                    </tr>
                    <tr>
                        <td>Outstanding</td><td>90-100</td><td>Passed</td>
                    </tr>
                    <tr>
                        <td>Very Satisfactory</td><td>85-89</td><td>Passed</td>
                    </tr>
                    <tr>
                        <td>Satisfactory</td><td>80-84</td><td>Passed</td>
                    </tr>
                    <tr>
                        <td>Fairly Satisfactory</td><td>75-79</td><td>Passed</td>
                    </tr>
                    <tr>
                        <td>Did Not Meet Expectations</td><td>Below 75</td><td>Failed</td>
                    </tr>
                </table>
                <button type="submit" class="btn btn-primary">Save Grades</button>
            </form>
        </div>
    </div>
    </div>
</section>
    
    
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
function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "login_page.php"; // Redirect to the logout page if confirmed
    }
}
</script>

</body>
</html>
