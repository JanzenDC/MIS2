x<?php
session_start();
require 'db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Check for empty fields
    if (empty($email) || empty($password)) {
        echo "Please enter both email and password.";
        exit();
    }

    // Directly use the email in the query (NOT recommended due to SQL injection risk)
    $email = $conn->real_escape_string($email); // This helps mitigate some risks, but it's still vulnerable

    // Query to check if the user exists
    $query = "SELECT id, password, role FROM users WHERE email = '$email'"; // SQL query without prepared statements
    $result = $conn->query($query);

    // Check if the user exists
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Store user information in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect based on user role
            switch ($user['role']) {
                case 'admin':
                    header("Location: index.php");
                    break;
                case 'ict_faculty':
                    header("Location: ict_maintenance.php");
                    break;
                case 'teacher':
                    header("Location: teacher_dashboard.php"); // Redirect to teacher dashboard
                    break;
                default:
                    echo "Unknown user role.";
                    exit();
            }
            exit();
        } else {
            echo "Invalid password."; // Return error for invalid password
            error_log("Invalid password attempt for email: $email");
        }
    } else {
        echo "No user found with this email."; // Return error for no user found
        error_log("Login attempt with non-existing email: $email");
    }

    $result->free(); // Free result memory
}

$conn->close(); // Close the database connection
?>
