<?php
// Database connection
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the ID of the student to reject
    $studentId = $_POST['id'];

    // Delete the student from the database
    $stmt = $conn->prepare("DELETE FROM learners WHERE id = ?");
    $stmt->bind_param("i", $studentId);

    if ($stmt->execute()) {
        // Return success message
        echo json_encode(['success' => true]);
    } else {
        // Return error message
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
