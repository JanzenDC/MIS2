<?php
// Database connection (adjust with your database credentials)
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'school_db';

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['lrn'])) {
    $lrn = $_GET['lrn'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM learners WHERE lrn = ?");
    $stmt->bind_param("s", $lrn);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    echo json_encode(['exists' => $count > 0]);
}

$conn->close();
?>
