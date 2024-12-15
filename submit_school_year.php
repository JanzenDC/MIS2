<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$userId = $_SESSION['user_id']; // Assuming user ID is stored in session upon login

// Fetch user role if needed
$query = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $userRole = $user['role'];
} else {
    $userRole = "No Role"; // Default role if not found
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fromYear = $_POST['from_year'];
    $toYear = $_POST['to_year'];

    // Validate inputs
    if ($fromYear >= $toYear) {
        echo "<script>alert('Error: From year must be less than to year'); window.location.href='card-maintenance.php';</script>";
        exit();
    }

    // Check for duplicate school year entries
    $checkDuplicate = "SELECT * FROM school_years WHERE from_year = ? AND to_year = ?";
    $stmt = $conn->prepare($checkDuplicate);
    $stmt->bind_param("ii", $fromYear, $toYear);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Error: School year already exists'); window.location.href='card-maintenance.php';</script>";
    } else {
        // Insert new school year into the database
        $insertQuery = "INSERT INTO school_years (from_year, to_year) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ii", $fromYear, $toYear);

        if ($stmt->execute()) {
            echo "<script>alert('School year added successfully'); window.location.href='card-maintenance.php';</script>";
        } else {
            echo "<script>alert('Error: " . addslashes($stmt->error) . "'); window.location.href='card-maintenance.php';</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
