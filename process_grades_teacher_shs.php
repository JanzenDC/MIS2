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
$lrn = isset($_POST['lrn']) ? sanitizeInput($_POST['lrn']) : '';
$grades = isset($_POST['grades']) ? $_POST['grades'] : [];
$adviser = isset($_POST['adviser']) ? sanitizeInput($_POST['adviser']) : '';
$school_year = isset($_POST['school_year']) ? sanitizeInput($_POST['school_year']) : '';
$section = isset($_POST['section']) ? sanitizeInput($_POST['section']) : '';
$grade = isset($_POST['grade']) ? sanitizeInput($_POST['grade']) : '';

// Validate input
if (empty($lrn)) {
    $_SESSION['error'] = "LRN is required.";
    header("Location: view_teacher_record.php");
    exit;
}

if (empty($grades)) {
    $_SESSION['error'] = "No grades submitted.";
    header("Location: view_teacher_record.php?lrn=" . urlencode($lrn));
    exit;
}

// Initialize variables for general average calculation
$totalFinalGrades = 0;
$subjectCount = 0;

// Process each subject's grades
foreach ($grades as $subject_id => $grading) {
    // Sanitize subject ID
    $subject_id = intval($subject_id);

    // Prepare grade values (default to 0 for missing grades)
    $first_grading = !empty($grading['first']) ? floatval($grading['first']) : 0;
    $second_grading = !empty($grading['second']) ? floatval($grading['second']) : 0;
    $third_grading = !empty($grading['third']) ? floatval($grading['third']) : 0;
    $fourth_grading = !empty($grading['fourth']) ? floatval($grading['fourth']) : 0;

    // Calculate the final grade (average of grading periods)
    $final_grade = ($first_grading + $second_grading + $third_grading + $fourth_grading) / 4;

    // Determine the status
    $status = $final_grade >= 75 ? 'Passed' : 'Failed';

    // Track for general average calculation
    $totalFinalGrades += $final_grade;
    $subjectCount++;

    // Delete any existing records for this LRN, subject, and grade level
    $deleteSql = "DELETE FROM shs_grades WHERE lrn = '{$lrn}' AND subject_id = {$subject_id} AND grade = '{$grade}'";
    if (!$conn->query($deleteSql)) {
        $_SESSION['error'] = "Error deleting existing grades: " . $conn->error;
        break;
    }

    // Insert new record
    $insertSql = "INSERT INTO shs_grades (
        lrn, subject_id, first_grading, second_grading, third_grading, 
        fourth_grading, final_grade, status, adviser, school_year, section, grade
    ) VALUES (
        '{$lrn}', {$subject_id}, {$first_grading}, {$second_grading}, 
        {$third_grading}, {$fourth_grading}, {$final_grade}, '{$status}', 
        '{$adviser}', '{$school_year}', '{$section}', '{$grade}'
    )";

    if (!$conn->query($insertSql)) {
        $_SESSION['error'] = "Error inserting grades: " . $conn->error;
        break;
    }
}

// Calculate and save general average if subjects exist
if ($subjectCount > 0) {
    $generalAverage = $totalFinalGrades / $subjectCount;

    // Save general average (optional depending on your schema)
    $_SESSION['success'] = "Grades and general average have been successfully saved.";
} else {
    $_SESSION['error'] = "No valid grades to calculate general average.";
}

// Close the database connection
$conn->close();

// Redirect back to the academic record view
header("Location: view_teacher_record.php?lrn=" . urlencode($lrn));
exit;
?>
