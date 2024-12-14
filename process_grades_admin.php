<?php
session_start();

// Database connection setup
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "school_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

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

$lrn = isset($_POST['lrn']) ? sanitizeInput($_POST['lrn']) : '';
$grades = isset($_POST['grades']) ? $_POST['grades'] : [];
$adviser = isset($_POST['adviser']) ? sanitizeInput($_POST['adviser']) : '';
$school_year = isset($_POST['school_year']) ? sanitizeInput($_POST['school_year']) : '';
$section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
$grade = isset($_POST['grade']) ? sanitizeInput($_POST['grade']) : '';

if (empty($lrn)) {
    $_SESSION['error'] = "LRN is required.";
    header("Location: view_admin_record.php");
    exit;
}

if (empty($grades)) {
    $_SESSION['error'] = "No grades submitted.";
    header("Location: view_admin_record.php?lrn=" . urlencode($lrn));
    exit;
}

$totalFinalGrades = 0;
$subjectCount = 0;

foreach ($grades as $subject_id => $grading) {
    $subject_id = intval($subject_id);

    $first_grading = !empty($grading['first']) ? floatval($grading['first']) : '';
    $second_grading = !empty($grading['second']) ? floatval($grading['second']) : '';
    $third_grading = !empty($grading['third']) ? floatval($grading['third']) : '';
    $fourth_grading = !empty($grading['fourth']) ? floatval($grading['fourth']) : '';

    $final_grade = ($first_grading + $second_grading + $third_grading + $fourth_grading) / 4;

    $status = $final_grade >= 75 ? 'Passed' : 'Failed';

    $totalFinalGrades += $final_grade;
    $subjectCount++;

    $deleteSql = "DELETE FROM grades WHERE lrn = '{$lrn}' AND subject_id = {$subject_id} AND grade = '{$grade}'";
    if (!$conn->query($deleteSql)) {
        $_SESSION['error'] = "Error deleting existing grades: " . $conn->error;
        break;
    }

    $insertSql = "INSERT INTO grades (
        lrn, subject_id, first_grading, second_grading, third_grading, 
        fourth_grading, final_grade, status, adviser, school_year, section, grade
    ) VALUES (
        '{$lrn}', {$subject_id}, {$first_grading}, {$second_grading}, 
        {$third_grading}, {$fourth_grading}, {$final_grade}, '{$status}', 
        '{$adviser}', '{$school_year}', '{$section}', '{$grade}'
    )";

    if ($conn->query($insertSql)) {
        // Get the last inserted ID
        $lastInsertedId = $conn->insert_id;

        // Optionally update the general average for this subject or record
        $updateAverageSql = "UPDATE grades SET general_average = {$final_grade} WHERE id = {$lastInsertedId}";
        $conn->query($updateAverageSql);
    } else {
        $_SESSION['error'] = "Error inserting grades: " . $conn->error;
        break;
    }
}

// if ($subjectCount > 0) {
//     $generalAverage = $totalFinalGrades / $subjectCount;

//     // Save general average to another table if needed
//     $updateGeneralAverageSql = "UPDATE learners SET general_average = {$generalAverage} WHERE lrn = '{$lrn}'";
//     $conn->query($updateGeneralAverageSql);

//     $_SESSION['success'] = "Grades and general average have been successfully saved.";
// } else {
//     $_SESSION['error'] = "No valid grades to calculate general average.";
// }

$conn->close();

header("Location: view_admin_record.php?lrn=" . urlencode($lrn));
exit;
?>
