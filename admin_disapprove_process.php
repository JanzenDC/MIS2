<?php
session_start();

// Database connection setup
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "school_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input function
function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Fetch and sanitize POST data
$lrn = isset($_POST['lrn']) ? sanitizeInput($conn->real_escape_string($_POST['lrn'])) : '';

if (!empty($lrn)) {
    // Check if the LRN exists in the learners table
    $checkQuery = "SELECT COUNT(*) as count FROM learners WHERE lrn = '$lrn'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        if ($row['count'] > 0) {
            // Check if the LRN already exists in the promoted_student_tbl
            $existsQuery = "
                SELECT COUNT(*) as count 
                FROM promoted_student_tbl 
                WHERE learnersID = '$lrn' 
                AND approveStatus IN ('1', '2') 
                AND promotedStatus = '0'
            ";
                 
            $existsResult = $conn->query($existsQuery);

            if ($existsResult && $existsResult->num_rows > 0) {
                $existsRow = $existsResult->fetch_assoc();
                if ($existsRow['count'] == 0) {
                    // If not already in promoted_student_tbl, insert the record
                    $insertQuery = "INSERT INTO promoted_student_tbl (learnersID, approveStatus) VALUES ('$lrn', 2)";
                    $conn->query($insertQuery);
                }
            }
        } else {
            // If the LRN doesn't exist in learners, redirect with an error message
            header("Location: view_admin_record.php?lrn=" . urlencode($lrn) . "&status=lrn_not_found");
            exit;
        }
    } else {
        // If the learners query failed, redirect with an error message
        header("Location: view_admin_record.php?status=query_failed");
        exit;
    }
} else {
    // If no LRN is provided, redirect with an error message
    header("Location: view_admin_record.php?status=missing_lrn");
    exit;
}

// Close the database connection
$conn->close();

// Redirect back to the academic record view
header("Location: view_admin_record.php?lrn=" . urlencode($lrn) . "&status=success");
exit;
?>
