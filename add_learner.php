<?php
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "school_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sf10FilePath = '';
$imageFilePath = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lrn = $_POST['lrn'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $middleName = $_POST['middle_name'] ?? ''; 
    $nameExtension = $_POST['name_extension'] ?? ''; 
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $studentType = $_POST['studentType'];
    $schoolAttended = $_POST['schoolAttended'];
    $otherSchool = $_POST['otherSchool'];
    $gradelevel = $_POST['gradelevel'];
    $guardianName = $_POST['guardianName'];
    $otherGuardian = $_POST['otherGuardian'];
    $curriculum = $_POST['curriculum'];
    $guardianRelationship = $_POST['guardian_relationship'];
    $address = $_POST['address'];
    $cont_num = $_POST['contnum'];
    $religion = $_POST['religion'];
    $age = $_POST['age'];

    $sf10UploadDir = "uploads/sf10/";
    $imageUploadDir = "uploads/images/";

    if (!is_dir($sf10UploadDir)) {
        mkdir($sf10UploadDir, 0755, true);
    }
    if (!is_dir($imageUploadDir)) {
        mkdir($imageUploadDir, 0755, true);
    }

    $duplicateCheckQuery = "SELECT COUNT(*) FROM learners WHERE lrn = '$lrn'";
    $result = $conn->query($duplicateCheckQuery);
    $row = $result->fetch_row();
    if ($row[0] > 0) {
        $_SESSION['error'] = "This LRN is already in use. Please use a unique LRN.";
        header("Location: admission_form.php");
        exit();
    }

    if (isset($_FILES['sf10File']) && $_FILES['sf10File']['error'] == UPLOAD_ERR_OK) {
        $sf10FileName = $_FILES['sf10File']['name'];
        $sf10FileTmpPath = $_FILES['sf10File']['tmp_name'];
        $sf10FilePath = $sf10UploadDir . basename($sf10FileName);
        
        if (!move_uploaded_file($sf10FileTmpPath, $sf10FilePath)) {
            $_SESSION['error'] = "Error uploading SF10 file.";
            header("Location: admission_form.php");
            exit();
        }
    }

    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] == UPLOAD_ERR_OK) {
        $imageFileName = $_FILES['imageFile']['name'];
        $imageFileTmpPath = $_FILES['imageFile']['tmp_name'];
        $imageFilePath = $imageUploadDir . basename($imageFileName);

        if (!move_uploaded_file($imageFileTmpPath, $imageFilePath)) {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: admission_form.php");
            exit();
        }
    }

    $status = 'pending';

    $sql = "INSERT INTO learners (lrn, first_name, last_name, middle_name, name_extension, dob, address, cont_num, religion, age, gender, student_type, school_attended, other_school, grade_level, guardian_name, guardian_relationship, other_guardian, curriculum, sf10_file, image_file, status) VALUES ('$lrn', '$fname', '$lname', '$middleName', '$nameExtension', '$dob', '$address', '$cont_num', '$religion', '$age', '$gender', '$studentType', '$schoolAttended', '$otherSchool', '$gradelevel', '$guardianName', '$guardianRelationship', '$otherGuardian', '$curriculum', '$sf10FilePath', '$imageFilePath', '$status')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Learner added successfully!";
        header("Location: admission_form.php");
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: admission_form.php");
        exit();
    }
}

$conn->close();
?>
