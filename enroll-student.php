<?php
session_start();

if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '");</script>';
    unset($_SESSION['message']); // Clear the message after displaying
}

if (isset($_SESSION['error'])) {
    echo '<script>alert("' . $_SESSION['error'] . '");</script>';
    unset($_SESSION['error']); // Clear the error after displaying
}

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

    // Set the default status
    $status = 'pending'; // Default status for new students
    
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
    $stmt = $conn->prepare("INSERT INTO learners (lrn, fname, lname, dob, gender, studentType, schoolAttended, gradelevel, guardianName, guardian, curriculum, sf10FilePath, imageFilePath, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssssssss", $lrn, $fname, $lname, $dob, $gender, $studentType, $schoolAttended, $gradelevel, $guardianName, $guardian, $curriculum, $sf10FilePath, $imageFilePath, $status);
    
    if ($stmt->execute()) {
        echo '<script>alert("Learner added successfully!");</script>';
    } else {
        echo '<script>alert("Error adding learner: ' . $stmt->error . '");</script>';
    }

    $stmt->close();
}

// Fetch existing learners
$learners = [];
$result = $conn->query("SELECT * FROM learners");
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
  <title> Dashboard</title><link rel="icon" href="../img/favicon2.png">
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
    .form-box {
      margin-bottom: 20px;
      width: 500%;  
    }
   
    .sidebar-logo img {
  display: block;
  transition: all 0.9s ease; /* Smooth transition */
}

/* When the sidebar is collapsed */
.sidebar-collapse .sidebar-logo img {
  display: none; /* Hide the logo when collapsed */
}
.content-wrapper::before {
  content: '';
  position: absolute;
  top: 50%; /* Move to the center vertically */
  left: 58%; /* Move to the center horizontally */
  width: 500px; /* Set a fixed width for the logo */
  height: 500px; /* Set a fixed height for the logo */
  background: url('dist/img/deped_logo.png') no-repeat; /* Remove the center positioning from background */
  background-size: contain; /* Adjust to maintain aspect ratio */
  transform: translate(-50%, -50%); /* Center the element */
  opacity: 0.1; /* Make it subtle */
  z-index: 0; /* Set to a lower z-index */
  pointer-events: none; /* Ensure the watermark doesn’t interfere with interactions */
}

.text-center {
        text-align: center; /* Center text horizontally */
    }
  </style>
</head>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="./" class="logo">
      <span class="logo-mini"><b>MIS</b></span>
      <span class="logo-lg"><b>Enroll</b> Student</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs">Hey! I'm Teacher Ronald</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="dist/img/avatar5.png" class="img-circle" alt="User Image">
                <p><small>Student</small></p>
              </li>
              <li class="user-footer">
                <div class="pull-right">
                  <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
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
        <li id="enroll-student"><a href="./enroll-student.php"><i class="fa fa-file-text"></i> Enroll</a></li>
        <li id="student-sf10-files"><a href="./student-sf10-files.php"><i class="fa fa-file-text"></i> SF10</a></li>
  
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
                    <li id="academic-records"><a href="./academic-records.php"><i class="fa fa-file-text"></i> Academic Records</a></li>
                    <li id="form-137"><a href="./form-137.php"><i class="fa fa-file-text"></i> Form 137</a></li>
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
                    <i class="fa fa-cogs"></i> <span>Subject</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li id="subject-maintenance"><a href="./subject-maintenance.php"><i class="fa fa-book"></i> Junior High School</a></li>
                    <li id="subject-maintenance1"><a href="./subject-maintenance1.php"><i class="fa fa-graduation-cap"></i> Senior High School</a></li>

                </ul>
            </li>
                    <li id="curriculum-maintenance"><a href="./curriculum-maintenance.php"><i class="fa fa-clipboard"></i> Curriculum</a></li>
                    <li id="user-maintenance"><a href="./user-maintenance.php"><i class="fa fa-user"></i> User Maintenance</a></li>
                </ul>
            </li>
            <li id="about"><a href="./about.php"><i class="fa fa-info-circle"></i> <span>About</span></a></li>
        </ul>
        
    </section>
</aside>

  <div class="content-wrapper">
    <section class="content-header">
      <h1>MACAYO INTEGRATED SCHOOL <small> Enroll Student</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Student</a></li>
        <li class="active">Enroll Student</li>
      </ol>
    </section>
<!-- Trigger Button -->
<br>
<br>


<div style="display: flex; justify-content: flex-end;">
  <button type="button" class="btn btn-primary" style="margin-right: 30px;" data-toggle="modal" data-target="#studentModal">
    Add Student
  </button>
</div>

<!-- Modal -->
<div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="studentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="studentModalLabel">Add Student</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body">
          <form role="form" method="POST" action="add_learner.php" enctype="multipart/form-data"> <!-- Change here -->
            <div class="box-body">
              <div class="row">
                <!-- Left side: form fields -->
                <div class="col-md-8">
                  <div class="form-group">
                    <label for="studentID">LRN no.</label>
                    <input name="lrn" type="text" class="form-control" id="studentID" placeholder="Enter LRN No." required>
                  </div>
                  <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input name="fname" type="text" class="form-control" id="firstName" placeholder="Enter Learner First Name" required>
                  </div>
                  <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input name="lname" type="text" class="form-control" id="lastName" placeholder="Enter Learner Last Name" required>
                  </div>
                  <div class="form-group">
    <label>Date of Birth</label>
    <div class="input-group date" id="dob-datepicker">
        <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
        </div>
        <input type="text" class="form-control" name="date_of_birth" required placeholder="yyyy/mm/dd" readonly>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize the datepicker
        $('#dob-datepicker').datepicker({
            format: 'yyyy/mm/dd', // Set the desired date format
            autoclose: true,
            todayHighlight: true
        });

        // Allow typing in the input field with format validation
        $('input[name="date_of_birth"]').on('blur', function() {
            var inputDate = $(this).val();
            var datePattern = /^\d{4}\/\d{1,2}\/\d{1,2}$/; // Regex for yyyy/mm/dd format
            if (datePattern.test(inputDate)) {
                // If valid format, parse and set the datepicker
                $('#dob-datepicker').datepicker('setDate', inputDate);
            } else {
                // Clear the input if format is invalid
                $(this).val('');
                alert("Please enter the date in the format yyyy/mm/dd");
            }
        });
    });
</script>
                  <div class="form-group">
    <label for="gender">Gender</label>
    <div class="radio mb-3">
      <label class="mr-4" style="margin-right: 10px;">
        <input type="radio" name="gender" value="Male" required> Male
      </label>
      <label style="margin-left: 10px;">
        <input type="radio" name="gender" value="Female"> Female
      </label>
    </div>
  </div>


                  <div class="form-group">
                    <label for="studentType">Type of Learner</label>
                    <div class="radio mb-3">
                    <label class="mr-4" style="margin-right: 10px;">
                        <input type="radio" name="studentType" value="Old" required> Old
                      </label>
                      <label style="margin-left: 10px;">
                        <input type="radio" name="studentType" value="New Transferee"> New Transferee
                      </label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="schoolAttended">Elementary School Attended</label>
                    <select class="form-control" name="schoolAttended" id="schoolAttended" required onchange="toggleOtherSchoolInput()">
                      <option value="">Select Elementary School</option>
                      <option value="Aliaga Elementary School">Aliaga Elementary School</option>
                      <option value="Macayo Integrated School">Macayo Integrated School</option>
                      <option value="San Vicente Elementary School">San Vicente Elementary School</option>
                      <option value="San Isidro Elementary School">San Isidro Elementary School</option>
                      <option value="San Pablo Elementary School">San Pablo Elementary School</option>
                      <option value="Balingcanaway Elementary School">Balingcanaway Elementary School</option>
                      <option value="San Jose Elementary School">San Jose Elementary School</option>
                      <option value="San Carlos City Central School">San Carlos City Central School</option>
                      <option value="Other">Other</option>
                    </select>
                    <input type="text" name="otherSchool" id="otherSchool" class="form-control mt-2" placeholder="Please specify other school" style="display: none;">
                  </div>

                  <script>
                    function toggleOtherSchoolInput() {
                      var selectElement = document.getElementById("schoolAttended");
                      var otherSchoolInput = document.getElementById("otherSchool");
                      if (selectElement.value === "Other") {
                        otherSchoolInput.style.display = "block";
                      } else {
                        otherSchoolInput.style.display = "none";
                        otherSchoolInput.value = ""; // Clear input when not selected
                      }
                    }
                  </script>

                    <div class="form-group">
                        <label>Grade Level to Enroll</label>
                        <select class="form-control select2" name="gradelevel" id="gradelevel" style="width: 100%;" required>
                            <option value="">Select Grade Level</option>
                            <option value="7">Grade 7</option>
                            <option value="8">Grade 8</option>
                            <option value="9">Grade 9</option>
                            <option value="10">Grade 10</option>
                            <option value="11">Grade 11</option>
                            <option value="12">Grade 12</option>
                        </select>
                    </div>


                  <div class="form-group">
    <label for="guardianName">Guardian Name</label>
    <input name="guardianName" type="text" class="form-control" id="guardianName" placeholder="Enter Guardian's Full Name" required>
  </div>
                  <div class="form-group">
                    <label>Guardian</label>
                    <select class="form-control select2" name="guardian" style="width: 100%;" id="guardianSelect" required onchange="toggleOtherGuardianInput()">
                      <option value="">Select Guardian Relationship</option>
                      <option value="Parent">Parent</option>
                      <option value="Step Parent">Step Parent</option>
                      <option value="Grandparent">Grandparent</option>
                      <option value="Aunt">Aunt</option>
                      <option value="Uncle">Uncle</option>
                      <option value="Sibling">Sibling</option>
                      <option value="Other">Other</option>
                    </select>
                    <input type="text" name="otherGuardian" id="otherGuardian" class="form-control mt-2" placeholder="Please specify other guardian" style="display: none;">
                  </div>

                  <script>
                    function toggleOtherGuardianInput() {
                      var selectElement = document.getElementById("guardianSelect");
                      var otherGuardianInput = document.getElementById("otherGuardian");
                      if (selectElement.value === "Other") {
                        otherGuardianInput.style.display = "block";
                      } else {
                        otherGuardianInput.style.display = "none";
                        otherGuardianInput.value = ""; // Clear input when not selected
                      }
                    }
                  </script>

<div class="form-group">
    <label>Curriculum</label>
    <input type="text" class="form-control" name="curriculum" id="curriculum" style="width: 100%;" value="" readonly required>
</div>

                      <script>
    document.getElementById('gradelevel').addEventListener('change', function() {
        var gradeLevel = this.value;
        var curriculumSelect = document.getElementById('curriculum');
        
        // Reset the curriculum select
        curriculumSelect.selectedIndex = 0; // Reset to "Select Curriculum"
        
        // Set the curriculum based on the selected grade level
        if (gradeLevel === '7') {
            curriculumSelect.value = 'DepEd Matatag';
        } else if (gradeLevel >= '8') {
            curriculumSelect.value = 'K-12';
        }
        else if (gradeLevel >= '9') {
            curriculumSelect.value = 'K-12';
        }
        else if (gradeLevel >= '10') {
            curriculumSelect.value = 'K-12';
        }
        else if (gradeLevel >= '11') {
            curriculumSelect.value = 'K-12';
        }
        else if (gradeLevel >= '12') {
            curriculumSelect.value = 'K-12';
        }
        
        
    });
</script>

                  <div class="form-group">
                    <label for="sf10File">Upload SF10 PDF</label>
                    <input type="file" class="form-control" name="sf10File" accept="application/pdf" required>
                  </div>
                </div>

                <!-- Right side: Upload 1x1 Picture -->
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="imageFile">Upload 1x1 Picture</label>
                    <input type="file" class="form-control" name="imageFile" accept="image/png, image/jpeg" required>
                  </div>
                  <div class="image-preview">
                    <img id="previewImage" style="max-width: 100%; height: auto; border: 1px solid #ddd;" alt="Preview Image" />
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
<!-- Add Student Button -->
<button type="submit" name="submit" value="submit" class="btn btn-primary" id="add-student-btn">Add Student</button>

<!-- Confirmation Script -->
<script>
    document.getElementById('add-student-btn').addEventListener('click', function(event) {
        // Show confirmation dialog
        var confirmAdd = confirm("Are you sure you want to add this student?");
        
        // If the user clicks "Cancel", prevent form submission
        if (!confirmAdd) {
            event.preventDefault(); // Prevent the form from submitting
        }
    });
</script>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>




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
    <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $learner['id']; ?>" data-fname="<?php echo $learner['first_name']; ?>" data-lname="<?php echo $learner['last_name']; ?>" data-dob="<?php echo $learner['dob']; ?>">
        <i class="fas fa-edit"></i> 
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

                                <tbody>
                                    <!-- Populate with subjects from database -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    
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
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <script>
        // Display a success message
        window.onload = function() {
            alert("Learner added successfully!");

            // Remove the 'success' query parameter from the URL after the alert
            const url = new URL(window.location.href);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url);
        };
    </script>
<?php endif; ?>

<script>
  $(function () {
    $('#example1').DataTable();
    $('.select2').select2();
  });
</script>
</body>


</body>

</html>
