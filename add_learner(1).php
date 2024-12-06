<?php
// Database configuration
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

// Initialize variables for uploaded file paths
$sf10FilePath = '';
$imageFilePath = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $lrn = $_POST['lrn'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $studentType = $_POST['studentType'];
    $schoolAttended = $_POST['schoolAttended'];
    $otherSchool = $_POST['otherSchool'];
    $gradelevel = $_POST['gradelevel'];
    $guardianName = $_POST['guardianName'];
    $guardian = $_POST['guardian'];
    $otherGuardian = $_POST['otherGuardian'];
    $curriculum = $_POST['curriculum'];

    // Debugging output
    error_log("Student Type: " . $studentType);
    echo "Student Type: " . htmlspecialchars($studentType);

    // Define the upload directories
    $sf10UploadDir = "uploads/sf10/";
    $imageUploadDir = "uploads/images/";

    // Create directories if they don't exist
    if (!is_dir($sf10UploadDir)) {
        mkdir($sf10UploadDir, 0755, true);
    }
    if (!is_dir($imageUploadDir)) {
        mkdir($imageUploadDir, 0755, true);
    }

    // File upload logic for SF10
    if (isset($_FILES['sf10File']) && $_FILES['sf10File']['error'] == UPLOAD_ERR_OK) {
        $sf10FileName = $_FILES['sf10File']['name'];
        $sf10FileTmpPath = $_FILES['sf10File']['tmp_name'];
        $sf10FilePath = $sf10UploadDir . basename($sf10FileName);
        
        if (!move_uploaded_file($sf10FileTmpPath, $sf10FilePath)) {
            echo "Error uploading SF10 file.";
        }
    }

    // File upload logic for 1x1 Picture
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['imageFile']['name'];
        $imageFileTmpPath = $_FILES['imageFile']['tmp_name'];
        $imageFilePath = $imageUploadDir . basename($imageFileName);

        if (!move_uploaded_file($imageFileTmpPath, $imageFilePath)) {
            echo "Error uploading image.";
        }
    }

    // Insert the learner data into the database
  // Insert the learner data into the database
$sql = "INSERT INTO learners (lrn, first_name, last_name, dob, gender, student_type, school_attended, other_school, grade_level, guardian_name, guardian_relationship, other_guardian, curriculum, sf10_file, image_file) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind parameters with correct types
// Assuming 'lrn' is a string
$stmt->bind_param("sssssssssssssss", $lrn, $fname, $lname, $dob, $gender, $studentType, $schoolAttended, $otherSchool, $gradelevel, $guardianName, $guardian, $otherGuardian, $curriculum, $sf10FilePath, $imageFilePath);

if ($stmt->execute()) {
    header("Location: enroll-student.php?success=1"); // Redirect with success message
    exit;
} else {
    echo "Error: " . $stmt->error; // Output error message
}



    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>
