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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $subject_name = $_POST['subject_name'];
    $subject_description = $_POST['subject_description'];
    $grade_level = $_POST['editgrade_level'];

    // Escape user input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);
    $subject_name = mysqli_real_escape_string($conn, $subject_name);
    $subject_description = mysqli_real_escape_string($conn, $subject_description);
    $grade_level = mysqli_real_escape_string($conn, $grade_level);

    // Raw SQL query
    $sql = "UPDATE subjects 
            SET subject_name = '$subject_name', 
                subject_description = '$subject_description', 
                grade_holder = '$grade_level' 
            WHERE id = '$id'";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the subject maintenance page with a success message
        header("Location: subject-maintenance.php?msg=Subject updated successfully!");
    } else {
        // Redirect back with an error message
        header("Location: subject-maintenance.php?msg=Error updating subject: " . $conn->error);
    }
}


$conn->close();
?>
