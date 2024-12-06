<?php
// Database configuration
$host = 'localhost'; // Change as needed
$dbname = 'school_db'; // Change to your database name
$username = 'root'; // Change to your database username
$password = ''; // Change to your database password

// Create a new connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $curriculum_id = $_POST['curriculum_id'];
    $subject_name = $_POST['subject_name'];
    $subject_description = $_POST['subject_description'];

    // Prepare and bind the statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO subjects (curriculum, subject_name, subject_description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $curriculum_id, $subject_name, $subject_description);

    
    // Execute the statement
    if ($stmt->execute()) {
        // Success message
        echo "<script>alert('Subject added successfully!'); window.location.href='subject-maintenance.php';</script>";
    } else {
        // Error message
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
