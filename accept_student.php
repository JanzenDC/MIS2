<?php
session_start();

// Database connection (replace with your own connection parameters)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if an ID is provided
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Update query to change student status
    $stmt = $conn->prepare("UPDATE learners SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
}

$conn->close();
?>
