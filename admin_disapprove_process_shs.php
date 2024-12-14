<?php
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "school_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo "<script>alert('Connection failed: " . $conn->connect_error . "');</script>";
    exit;
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
                    $insertQuery = "INSERT INTO promoted_student_tbl (learnersID, approveStatus) VALUES ('$lrn', 2)";
                    if ($conn->query($insertQuery) === TRUE) {
                        echo "<script>alert('Learner promoted successfully.');</script>";
                    } else {
                        echo "<script>alert('Failed to promote learner.');</script>";
                    }
                } else {
                    echo "<script>alert('Learner already promoted.');</script>";
                }
            }
        } else {
            echo "<script>alert('LRN not found.');</script>";
            echo "<script>window.location.href = 'view_admin_record_shs.php?lrn=" . urlencode($lrn) . "&status=lrn_not_found';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Failed to check LRN.');</script>";
        echo "<script>window.location.href = 'view_admin_record_shs.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('No LRN provided.');</script>";
    echo "<script>window.location.href = 'view_admin_record_shs.php';</script>";
    exit;
}

$conn->close();

echo "<script>window.location.href = 'view_admin_record_shs.php?lrn=" . urlencode($lrn) . "&status=success';</script>";
exit;
?>
