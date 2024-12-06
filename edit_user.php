<?php
session_start(); // Start the session

// Include your database connection file
require 'db_connection.php';

// Check if the user is logged in by checking if user_id exists in session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Handle form submission for editing a user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = intval($_POST['id']); // Get user ID from the form
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // Validate input
    if (empty($email) || empty($role)) {
        $error_message = "All fields are required.";
    } else {
        // Prepare SQL statement for updating the user
        $stmt = $conn->prepare("UPDATE users SET email = ?, role = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ssi", $email, $role, $userId);
            if ($stmt->execute()) {
                $success_message = "User updated successfully.";
                header("Location: account-maintenance.php"); // Redirect back to the account maintenance page
                exit();
            } else {
                $error_message = "Error updating user: " . $stmt->error;
            }
            $stmt->close(); // Close the statement
        } else {
            $error_message = "Error preparing statement: " . $conn->error;
        }
    }
}

// Close the connection
$conn->close();
?>
