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
    // Retrieve form data
    $grade_level = $_POST['grade_level'];
    $semester = $_POST['semester'];
    $curriculum = $_POST['curriculum'];
    $subject_name = $_POST['subject_name'];
    $description = $_POST['subject_description'];

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
