<?php
session_start(); // Start the session

// Database connection setup
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "school_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$lrn = isset($_POST['lrn']) ? $_POST['lrn'] : '';
$grades = isset($_POST['grades']) ? $_POST['grades'] : [];
$adviser = isset($_POST['adviser']) ? $_POST['adviser'] : '';
$school_year = isset($_POST['school_year']) ? $_POST['school_year'] : '';
$section = isset($_POST['section']) ? $_POST['section'] : '';

if (empty($grades)) {
    $_SESSION['error'] = "No grades submitted.";
    header("Location: view_academic_record_shs.php?lrn=" . urlencode($lrn));
    exit;
}

$totalFinalGrades = 0;
$subjectCount = 0;

// Prepare SQL statement for inserting or updating grades in `shs_grades`
$stmt = $conn->prepare("
    INSERT INTO shs_grades (
        lrn, subject_id, first_grading, second_grading, third_grading, fourth_grading, final_grade, status, adviser, school_year, section
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        first_grading = COALESCE(NULLIF(VALUES(first_grading), ''), first_grading),
        second_grading = COALESCE(NULLIF(VALUES(second_grading), ''), second_grading),
        third_grading = COALESCE(NULLIF(VALUES(third_grading), ''), third_grading),
        fourth_grading = COALESCE(NULLIF(VALUES(fourth_grading), ''), fourth_grading),
        final_grade = CASE
            WHEN (VALUES(first_grading) IS NOT NULL AND VALUES(second_grading) IS NOT NULL AND VALUES(third_grading) IS NOT NULL AND VALUES(fourth_grading) IS NOT NULL) THEN
                (VALUES(first_grading) + VALUES(second_grading) + VALUES(third_grading) + VALUES(fourth_grading)) / 4
            ELSE final_grade
        END,
        status = CASE
            WHEN (VALUES(first_grading) IS NOT NULL AND VALUES(second_grading) IS NOT NULL AND VALUES(third_grading) IS NOT NULL AND VALUES(fourth_grading) IS NOT NULL) THEN 
                CASE 
                    WHEN ((VALUES(first_grading) + VALUES(second_grading) + VALUES(third_grading) + VALUES(fourth_grading)) / 4) >= 75 THEN 'Passed'
                    ELSE 'Failed'
                END
            ELSE status
        END,
        adviser = VALUES(adviser),
        school_year = VALUES(school_year),
        section = VALUES(section)
");

if ($stmt) {
    foreach ($grades as $subject_id => $grading) {
        $first_grading = !empty($grading['first']) ? $grading['first'] : null;
        $second_grading = !empty($grading['second']) ? $grading['second'] : null;
        $third_grading = !empty($grading['third']) ? $grading['third'] : null;
        $fourth_grading = !empty($grading['fourth']) ? $grading['fourth'] : null;
    
        $final_grade = null;
    
        // Check if all grading periods have grades
        if (!is_null($first_grading) && !is_null($second_grading) && 
            !is_null($third_grading) && !is_null($fourth_grading)) {
            
            $final_grade = ($first_grading + $second_grading + $third_grading + $fourth_grading) / 4;
            $totalFinalGrades += $final_grade;
            $subjectCount++;
            
            // Determine status based on final grade
            $status = ($final_grade >= 75) ? 'Passed' : 'Failed';
        } else {
            // If not all grades are provided, set status to "Pending"
            $status = '';
        }
    
        // Bind parameters
        $stmt->bind_param(
            "siiiidsssss",
            $lrn,
            $subject_id,
            $first_grading,
            $second_grading,
            $third_grading,
            $fourth_grading,
            $final_grade,
            $status,
            $adviser,
            $school_year,
            $section
        );
    
        // Execute statement
        if (!$stmt->execute()) {
            $_SESSION['error'] = "Error executing statement: " . $stmt->error;
            break;
        }
    }

    // Calculate and save general average if there are subjects
    if ($subjectCount > 0) {
        $generalAverage = $totalFinalGrades / $subjectCount;
        $stmt_avg = $conn->prepare("UPDATE shs_grades SET general_average = ? WHERE lrn = ?");
        if ($stmt_avg) {
            $stmt_avg->bind_param("ds", $generalAverage, $lrn);
            if (!$stmt_avg->execute()) {
                $_SESSION['error'] = "Error saving general average: " . $stmt_avg->error;
            }
            $stmt_avg->close();
        } else {
            $_SESSION['error'] = "Error preparing general average statement: " . $conn->error;
        }
    }

    $stmt->close();
    $_SESSION['success'] = "Grades and general average have been successfully saved.";
} else {
    $_SESSION['error'] = "Error preparing statement: " . $conn->error;
}

$conn->close();

header("Location: view_academic_record_shs.php?lrn=" . urlencode($lrn));
exit;
?>
