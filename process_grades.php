<?php
session_start(); // Start the session

// Database connection setup
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "school_db"; 

// Create connection
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
    header("Location: view_academic_record.php");
    exit;
}

if (empty($grades)) {
    $_SESSION['error'] = "No grades submitted.";
    header("Location: view_academic_record.php?lrn=" . urlencode($lrn));
    exit;
}

// Track total final grades and subject count for general average
$totalFinalGrades = 0;
$subjectCount = 0;

// Process each subject's grades
foreach ($grades as $subject_id => $grading) {
    // Sanitize and validate subject ID
    $subject_id = intval($subject_id);

    // Check if record already exists
    $checkSql = "SELECT id FROM grades 
                 WHERE lrn = '{$lrn}' 
                 AND subject_id = {$subject_id} 
                 AND grade = '{$grade}'";
    $checkResult = $conn->query($checkSql);

    // Prepare grade values
    $first_grading = !empty($grading['first']) ? floatval($grading['first']) : 'NULL';
    $second_grading = !empty($grading['second']) ? floatval($grading['second']) : 'NULL';
    $third_grading = !empty($grading['third']) ? floatval($grading['third']) : 'NULL';
    $fourth_grading = !empty($grading['fourth']) ? floatval($grading['fourth']) : 'NULL';

    // Initialize final grade and status
    $final_grade = 'NULL';
    $status = "''";

    // Calculate final grade and status if all grading periods have values
    if ($first_grading !== 'NULL' && $second_grading !== 'NULL' && 
        $third_grading !== 'NULL' && $fourth_grading !== 'NULL') {
        
        $final_grade = "(" . $first_grading . " + " . $second_grading . " + " . 
                       $third_grading . " + " . $fourth_grading . ") / 4";
        
        // Determine status based on final grade
        $status = "CASE WHEN $final_grade >= 75 THEN 'Passed' ELSE 'Failed' END";
        
        // Track for general average calculation
        $finalGradeValue = ($first_grading + $second_grading + $third_grading + $fourth_grading) / 4;
        $totalFinalGrades += $finalGradeValue;
        $subjectCount++;
    }

    // Determine if it's an insert or update based on existence check
    if ($checkResult->num_rows > 0) {
        // Update existing record
        $sql = "UPDATE grades SET 
                first_grading = COALESCE(NULLIF({$first_grading}, 'NULL'), first_grading),
                second_grading = COALESCE(NULLIF({$second_grading}, 'NULL'), second_grading),
                third_grading = COALESCE(NULLIF({$third_grading}, 'NULL'), third_grading),
                fourth_grading = COALESCE(NULLIF({$fourth_grading}, 'NULL'), fourth_grading),
                final_grade = CASE
                    WHEN ({$first_grading} IS NOT NULL AND {$second_grading} IS NOT NULL AND 
                          {$third_grading} IS NOT NULL AND {$fourth_grading} IS NOT NULL) THEN
                        ($first_grading + $second_grading + $third_grading + $fourth_grading) / 4
                    ELSE final_grade
                END,
                status = CASE
                    WHEN ({$first_grading} IS NOT NULL AND {$second_grading} IS NOT NULL AND 
                          {$third_grading} IS NOT NULL AND {$fourth_grading} IS NOT NULL) THEN 
                        CASE 
                            WHEN (($first_grading + $second_grading + $third_grading + $fourth_grading) / 4) >= 75 THEN 'Passed'
                            ELSE 'Failed'
                        END
                    ELSE status
                END,
                adviser = '{$adviser}',
                school_year = '{$school_year}',
                section = '{$section}'
                WHERE lrn = '{$lrn}' 
                AND subject_id = {$subject_id} 
                AND grade = '{$grade}'";
    } else {
        // Insert new record
        $sql = "INSERT INTO grades (
                lrn, subject_id, first_grading, second_grading, 
                third_grading, fourth_grading, final_grade, 
                status, adviser, school_year, section, grade
            ) VALUES (
                '{$lrn}', {$subject_id}, {$first_grading}, {$second_grading}, 
                {$third_grading}, {$fourth_grading}, {$final_grade}, 
                {$status}, '{$adviser}', '{$school_year}', '{$section}', '{$grade}'
            )";
    }

    // Execute the query
    if (!$conn->query($sql)) {
        $_SESSION['error'] = "Error processing grades: " . $conn->error;
        break;
    }
}

// Calculate and save general average if subjects exist
if ($subjectCount > 0) {
    $generalAverage = $totalFinalGrades / $subjectCount;
    
    $avgSql = "
        UPDATE grades 
        SET general_average = {$generalAverage}
        WHERE lrn = '{$lrn}'
    ";
    
    if (!$conn->query($avgSql)) {
        $_SESSION['error'] = "Error saving general average: " . $conn->error;
    } else {
        $_SESSION['success'] = "Grades and general average have been successfully saved.";
    }
}

// Close the database connection
$conn->close();

// Redirect back to the academic record view
header("Location: view_academic_record.php?lrn=" . urlencode($lrn));
exit;
?>