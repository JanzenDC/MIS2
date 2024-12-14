<?php
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "school_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<script>showAlertAndRedirect('Connection failed: " . $conn->connect_error . "', 'view_admin_record_shs.php');</script>";
    exit; // Ensure exit after alert
}

function sanitizeInput($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

$lrn = isset($_POST['lrn']) ? sanitizeInput($conn->real_escape_string($_POST['lrn'])) : '';

if (!empty($lrn)) {
    $checkQuery = "SELECT COUNT(*) as count FROM learners WHERE lrn = '$lrn'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $row = $checkResult->fetch_assoc();
        if ($row['count'] > 0) {
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
                    $insertQuery = "INSERT INTO promoted_student_tbl (learnersID, approveStatus) VALUES ('$lrn', 1)";
                    if ($conn->query($insertQuery) === TRUE) {
                        echo "<script>showAlertAndRedirect('Learner promoted successfully.', 'view_admin_record_shs.php?lrn=" . urlencode($lrn) . "&status=success');</script>";
                    } else {
                        echo "<script>showAlertAndRedirect('Failed to promote learner.', 'view_admin_record_shs.php');</script>";
                    }
                } else {
                    echo "<script>showAlertAndRedirect('Learner already promoted.', 'view_admin_record_shs.php');</script>";
                }
            }
        } else {
            echo "<script>showAlertAndRedirect('LRN not found.', 'view_admin_record_shs.php?lrn=" . urlencode($lrn) . "&status=lrn_not_found');</script>";
            exit; // Ensure exit after alert
        }
    } else {
        echo "<script>showAlertAndRedirect('Failed to check LRN.', 'view_admin_record_shs.php');</script>";
        exit; // Ensure exit after alert
    }
} else {
    echo "<script>showAlertAndRedirect('No LRN provided.', 'view_admin_record_shs.php');</script>";
    exit; // Ensure exit after alert
}

$conn->close();
?>