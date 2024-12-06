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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debugging line to check what is received in POST
    var_dump($_POST);

    // Retrieve form data with null checks
    $grade_level = $_POST['grade_level'] ?? null;
    $semester = $_POST['semester'] ?? null;
    $curriculum = $_POST['curriculum_id'] ?? null; // Update this line
    $subject_name = $_POST['subject_name'] ?? null;
    $description = $_POST['subject_description'] ?? null;

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO shs_subjects (grade_level, semester, curriculum, subject_name, subject_description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $grade_level, $semester, $curriculum, $subject_name, $description);

    // Execute the statement
    if ($stmt->execute()) {
        // Success message
        echo "<script>alert('Subject added successfully!'); window.location.href='subject-maintenance1.php';</script>";
    } else {
        // Error message
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
