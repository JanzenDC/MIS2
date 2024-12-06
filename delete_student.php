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

// Check if the learner ID is set in the query string
if (isset($_GET['id'])) {
    // Get the learner ID from the query string
    $learner_id = $_GET['id'];

    // Prepare the SQL statement to fetch the learner's grade level
    $sql = "SELECT grade_level FROM learners WHERE id = ?";
    
    // Initialize a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the learner ID to the statement
        $stmt->bind_param("i", $learner_id);
        
        // Execute the statement
        $stmt->execute();
        
        // Bind the result
        $stmt->bind_result($grade_level);
        $stmt->fetch();
        
        // Close the statement
        $stmt->close();

        // Prepare the DELETE SQL statement
        $delete_sql = "DELETE FROM learners WHERE id = ?";
        
        // Initialize a prepared statement for deletion
        if ($stmt = $conn->prepare($delete_sql)) {
            // Bind the learner ID to the statement
            $stmt->bind_param("i", $learner_id);

            // Execute the statement
            if ($stmt->execute()) {
                // Success: Set a session variable
                $_SESSION['message'] = 'Student deleted successfully';
            } else {
                // Error: Set an error message
                $_SESSION['error'] = 'Could not delete learner';
            }
            
            // Close the statement
            $stmt->close();
        } else {
            // Error preparing the statement
            $_SESSION['error'] = 'Could not prepare statement';
        }
        
        // Redirect based on the grade level
        switch ($grade_level) {
            case '7':
                header("Location: grade7_student.php");
                break;
            case '8':
                header("Location: grade8_student.php");
                break;
            case '9':
                header("Location: grade9_student.php");
                break;
            case '10':
                header("Location: grade10_student.php");
                break;
            case '11':
                header("Location: grade11_student.php");
                break;
            case '12':
                header("Location: grade12_student.php");
                break;
            default:
                header("Location: grade7_student.php"); // Fallback redirect
                break;
        }
        exit();
    } else {
        // Error preparing the statement
        $_SESSION['error'] = 'Could not prepare statement';
        header("Location: grade7_student.php"); // Fallback redirect
        exit();
    }
} else {
    // If no ID is set, redirect with an error
    $_SESSION['error'] = 'Invalid request';
    header("Location: grade7_student.php"); // Fallback redirect
    exit();
}

// Close the database connection
$conn->close();
