<?php
// Database configuration
$host = 'localhost'; // Change as needed
$dbname = 'school_db'; // Change to your database name
$username = 'root'; // Change to your database username
$password = ''; // Change to your database password

// Create a new connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'school_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the posted data
    $id = $_POST['id'];

    // Delete query
    $sql = "DELETE FROM shs_subjects WHERE id = ?";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Subject deleted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the maintenance page
    header('Location: subject-maintenance1.php');
    exit();
}
?>