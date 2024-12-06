<?php
// Database connection (replace with your own connection parameters)
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "school_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
session_start(); // Start the session at the top of your script

// Database connection code here...

if (isset($_GET['id'])) {
    // Get the learner ID from the query string
    $learner_id = $_GET['id'];

    // Prepare the DELETE SQL statement
    $sql = "DELETE FROM learners WHERE id = ?";
    
    // Initialize a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the learner ID to the statement
        $stmt->bind_param("i", $learner_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Success: Set a session variable
            session_start();
            $_SESSION['message'] = 'Learner deleted successfully';
            header("Location: enroll-student.php");
            exit();
        } else {
            // Error: Redirect back with an error message
            session_start();
            $_SESSION['error'] = 'Could not delete learner';
            header("Location: enroll-student.php");
            exit();
        }
        
        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        session_start();
        $_SESSION['error'] = 'Could not prepare statement';
        header("Location: enroll-student.php");
        exit();
    }
} else {
    // If no ID is set, redirect with an error
    session_start();
    $_SESSION['error'] = 'Invalid request';
    header("Location: enroll-student.php");
    exit();
}

// Close the database connection
$conn->close();
