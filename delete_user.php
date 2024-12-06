<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Validate input
    if (empty($id)) {
        die("User ID is required.");
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Success message
        echo "<script>alert('User Deleted successfully!'); window.location.href='account-maintenance.php';</script>";
    } else {
        // Error message
        echo "<script>alert('Error: " . $stmt->error . "'); window.history.back();</script>";
    }


    $stmt->close();
}

$conn->close();
?>
