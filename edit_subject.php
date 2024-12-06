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

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE subjects SET subject_name = ?, subject_description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $subject_name, $subject_description, $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to the subject maintenance page with a success message
        header("Location: subject-maintenance.php?msg=Subject updated successfully!");
    } else {
        // Redirect back with an error message
        header("Location: subject-maintenance.php?msg=Error updating subject!");
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
?>
