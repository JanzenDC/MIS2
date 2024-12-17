<?php
session_start();

// Check if LRN is provided
if (!isset($_GET['lrn'])) {
    echo "No student selected.";
    exit;
}

$lrn = $_GET['lrn'];

// Database connection
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

// Fetch learner details including guardian name and school attended
$sqlLearner = "SELECT * FROM learners WHERE lrn = ?";
$stmtLearner = $conn->prepare($sqlLearner);
$stmtLearner->bind_param("s", $lrn);
$stmtLearner->execute();
$resultLearner = $stmtLearner->get_result();

// Check if learner is found
if ($resultLearner->num_rows == 0) {
    echo "No records found for the selected student.";
    exit;
}

$learner = $resultLearner->fetch_assoc();

// Fetch SHS grades along with adviser, school year, and section
$sqlGrades = "
    SELECT shs_subjects.subject_name, shs_grades.first_grading, shs_grades.second_grading, shs_grades.third_grading, 
           shs_grades.fourth_grading, shs_grades.final_grade, shs_grades.status, shs_grades.general_average, 
           shs_grades.adviser, shs_grades.school_year, shs_grades.section
    FROM shs_grades 
    JOIN shs_subjects ON shs_grades.subject_id = shs_subjects.id 
    WHERE shs_grades.lrn = ?";
$stmtGrades = $conn->prepare($sqlGrades);
$stmtGrades->bind_param("s", $lrn);
$stmtGrades->execute();
$resultGrades = $stmtGrades->get_result();

$grades = [];
$adviser = '';
$school_year = '';
$section = '';
if ($resultGrades->num_rows > 0) {
    while ($row = $resultGrades->fetch_assoc()) {
        $grades[] = $row;
        $adviser = $row['adviser'];
        $schoolYear = $row['school_year'];
        $section = $row['section'];
    }
}
// TODO:
function loadStudentGrades($conn, $lrn) {
    // SQL Query to get subjects and grades based on the student’s LRN for Grade 11 and 12
    $sql = "SELECT 
                s.subject_name, 
                s.semester,
                s.grade_level,
                sg.first_grading, 
                sg.second_grading, 
                sg.third_grading, 
                sg.fourth_grading,
                sg.final_grade,
                s.curriculum
            FROM shs_grades sg
            JOIN shs_subjects s ON sg.subject_id = s.id
            WHERE sg.lrn = '$lrn' AND s.grade_level IN ('11', '12')
            ORDER BY s.grade_level, s.curriculum, s.semester, s.subject_name";
    
    $result = $conn->query($sql);

    if ($result === false) {
        die("Error in query: " . $conn->error);
    }

    // Separate subjects into first and second semester for both Grade 11 and Grade 12
    $grades = [
        'grade_11' => [
            'first_semester' => [],
            'second_semester' => []
        ],
        'grade_12' => [
            'first_semester' => [],
            'second_semester' => []
        ]
    ];

    while ($row = $result->fetch_assoc()) {
        if ($row['grade_level'] === '11') {
            if ($row['semester'] === '1') {
                $grades['grade_11']['first_semester'][] = $row;
            } elseif ($row['semester'] === '2') {
                $grades['grade_11']['second_semester'][] = $row;
            }
        } elseif ($row['grade_level'] === '12') {
            if ($row['semester'] === '1') {
                $grades['grade_12']['first_semester'][] = $row;
            } elseif ($row['semester'] === '2') {
                $grades['grade_12']['second_semester'][] = $row;
            }
        }
    }

    return $grades;
}

// Call the function
$subjects = loadStudentGrades($conn, $lrn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>SF10-JHS Form</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 85%;
            margin: auto;
            padding: 10px;
            border: 1px solid #000;
            margin-top: 20px;
            box-sizing: border-box;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #000;
            text-align: center;
        }
        .header img {
            height: 80px;
        }
        .title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            flex: 1;
        }
        .section {
            margin-top: 10px;
            border: 1px solid #000;
            padding: 8px;
            page-break-inside: avoid;
        }
        .section h3 {
            text-align: center;
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }
        .info-row .column {
            width: 48%;
            padding: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table, .table th, .table td {
            border: 1px solid #000;
            text-align: center;
            padding: 5px;
            font-size: 11px;
        }
        .certification {
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
            line-height: 1.5;
        }
        .certification p {
            margin: 5px 0;
        }

        .hidden-grade-label {
    display: none;
}

.buttons {
            display: flex;
            margin: 20px 0;
        }
        .buttons button {
            padding: 10px 10px;
            font-size: 14px;
            margin: 0 5px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .buttons .print-btn {
            background-color: #4CAF50; /* Green for Print */
        }

        .buttons .cancel-btn {
            background-color: #f44336; /* Red for Cancel */
        }

        .buttons button i {
            font-size: 16px;
        }

        /* Hide buttons when printing */
        @media print {
            .buttons {
                display: none;
            }
        }

    </style>
</head>

<body>
<div class="buttons">
    <button class="print-btn" onclick="window.print()">
        <i class="fas fa-print"></i> Print
    </button>
    <button class="cancel-btn" onclick="window.history.back()">
        <i class="fas fa-times-circle"></i> Cancel
    </button>
</div>

<div class="container">
    <div class="header">
        <img src="dist/img/macayo_logo.png" alt="School Logo">
        <div class="title">
            <p>Republic of the Philippines</p>
            <p>Department of Education</p>
            <p>Learner's Permanent Academic Record for Junior High School (SF10-JHS)</p>
            <p>(Formerly Form 137)</p>
        </div>
        <img src="dist/img/deped_logo.png" alt="DepEd Logo">
    </div>

    <!-- Learner's Information -->
    <div class="section">
        <h3>LEARNER'S INFORMATION</h3>
        <div class="info-row">
            <div class="column">
                <p>Last Name: <?= htmlspecialchars($learner['last_name']); ?></p>
                <p>First Name: <?= htmlspecialchars($learner['first_name']); ?></p>
                <p>Middle Name: <?= htmlspecialchars($learner['middle_name']); ?></p>
                <p>Name Extn: (Jr, II, III): <?= htmlspecialchars($learner['name_extension'] ?? ''); ?></p>
                <p>Learner Reference Number (LRN): <?= htmlspecialchars($learner['lrn']); ?></p>
            </div>
            <div class="column">
                <p>Birthdate (mm/dd/yyyy): <?= htmlspecialchars($learner['dob']); ?></p>
                <p>Sex: <?= htmlspecialchars($learner['gender']); ?></p>
                <p>Guardian Name: <?= htmlspecialchars($learner['guardian_name']); ?></p>
            </div>
        </div>
    </div>

    <!-- Eligibility for JHS Enrollment -->
    <div class="section">
        <h3>ELIGIBILITY FOR JHS ENROLLMENT</h3>
        <p>Elementary School Completer General Average: _____</p>
        <p>
            Name of Elementary School: <?= !empty($learner['other_school']) ? htmlspecialchars($learner['other_school']) : htmlspecialchars($learner['school_attended']); ?>
            School ID: _____ 
            Address of School: ___________
        </p>
    </div>

    <!-- Scholastic Record for Grades 7 to 10 -->
    <div class="section">
        <h3>SCHOLASTIC RECORD</h3>
        <!-- 1ST SEM OF G11 -->
        <div style="width: 100%;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL ID: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">GRADE LEVEL: 11</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SY: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SECTION:_________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SEM: 1st</div>
                </div>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SUBJECTS</th>
                        <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Quarter</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SEM FINAL GRADE</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">ACTION TAKEN</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">1ST</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">2ND</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($subjects['grade_11']['first_semester'] as $subject) {
                        echo "<tr>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['curriculum']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['subject_name']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['first_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['second_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['final_grade']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'></td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="4" style="border: 1px solid black; padding: 5px; text-align: center;">General Ave. for the Semester</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                <div>REMARKS:</div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">JULIUS T. ARAW</span>
                    Signature of Adviser over Printed Name
                </div>
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">AMELITA B. CELEMIN, PH.D. / PRINCIPAL</span>
                    Signature of Authorized Person over Printed Name, Designation
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <div>Certified True and Correct:</div>
                <div>Date Checked (MM/DD/YYYY):</div>
            </div>
        </div>

        <!-- <div style="width: 100%;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL ID: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">GRADE LEVEL: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SY: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SECTION:_________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SEM: 1st</div>
                </div>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SUBJECTS</th>
                        <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Quarter</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SEM FINAL GRADE</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">ACTION TAKEN</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">3RD</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">4TH</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($subjects['first_semester'] as $subject) {
                        echo "<tr>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['curriculum']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['subject_name']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['third_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['fourth_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['final_grade']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'></td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="4" style="border: 1px solid black; padding: 5px; text-align: center;">General Ave. for the Semester</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                <div>REMARKS:</div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">JULIUS T. ARAW</span>
                    Signature of Adviser over Printed Name
                </div>
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">AMELITA B. CELEMIN, PH.D. / PRINCIPAL</span>
                    Signature of Authorized Person over Printed Name, Designation
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <div>Certified True and Correct:</div>
                <div>Date Checked (MM/DD/YYYY):</div>
            </div>
        </div> -->
        
        <div style="width: 100%;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL ID: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">GRADE LEVEL: 11</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SY: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SECTION:_________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SEM: 1st</div>
                </div>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SUBJECTS</th>
                        <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Quarter</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SEM FINAL GRADE</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">ACTION TAKEN</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">3rd</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">4th</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($subjects['grade_11']['first_semester'] as $subject) {
                        echo "<tr>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['curriculum']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['subject_name']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['third_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['fourth_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['final_grade']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'></td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="4" style="border: 1px solid black; padding: 5px; text-align: center;">General Ave. for the Semester</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                <div>REMARKS:</div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">JULIUS T. ARAW</span>
                    Signature of Adviser over Printed Name
                </div>
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">AMELITA B. CELEMIN, PH.D. / PRINCIPAL</span>
                    Signature of Authorized Person over Printed Name, Designation
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <div>Certified True and Correct:</div>
                <div>Date Checked (MM/DD/YYYY):</div>
            </div>
        </div>
        <!-- SECOND SEM OF G11 -->

        <div style="width: 100%;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL ID: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">GRADE LEVEL: 12</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SY: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SECTION:_________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SEM: 2nd</div>
                </div>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SUBJECTS</th>
                        <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Quarter</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SEM FINAL GRADE</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">ACTION TAKEN</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">1ST</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">2ND</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($subjects['grade_12']['first_semester'] as $subject) {
                        echo "<tr>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['curriculum']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['subject_name']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['first_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['second_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['final_grade']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'></td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="4" style="border: 1px solid black; padding: 5px; text-align: center;">General Ave. for the Semester</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                <div>REMARKS:</div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">JULIUS T. ARAW</span>
                    Signature of Adviser over Printed Name
                </div>
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">AMELITA B. CELEMIN, PH.D. / PRINCIPAL</span>
                    Signature of Authorized Person over Printed Name, Designation
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <div>Certified True and Correct:</div>
                <div>Date Checked (MM/DD/YYYY):</div>
            </div>
        </div> 

        <div style="width: 100%;">
            <div style="text-align: center; margin-bottom: 20px;">
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SCHOOL ID: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">GRADE LEVEL: 12</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SY: _________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SECTION:_________________</div>
                    <div style="flex: 1 1 16%; text-align: center; margin-bottom: 5px;">SEM: 2nd</div>
                </div>
            </div>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Indicate if Subject is CORE, APPLIED, or SPECIALIZED</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SUBJECTS</th>
                        <th colspan="2" style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">Quarter</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">SEM FINAL GRADE</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center; background-color: #d3d3d3;">ACTION TAKEN</th>
                    </tr>
                    <tr>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">3rd</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;">4th</th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                        <th style="border: 1px solid black; padding: 5px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($subjects['grade_12']['second_semester'] as $subject) {
                        echo "<tr>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['curriculum']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['subject_name']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['third_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['fourth_grading']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'>".$subject['final_grade']."</td>";
                        echo "<td style='border: 1px solid black; padding: 5px; text-align: center;'></td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="4" style="border: 1px solid black; padding: 5px; text-align: center;">General Ave. for the Semester</td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                        <td style="border: 1px solid black; padding: 5px; text-align: center;"></td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 20px;">
                <div>REMARKS:</div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">JULIUS T. ARAW</span>
                    Signature of Adviser over Printed Name
                </div>
                <div style="text-align: center;">
                    <span style="display: block; margin-top: 40px; border-top: 1px solid black;">AMELITA B. CELEMIN, PH.D. / PRINCIPAL</span>
                    Signature of Authorized Person over Printed Name, Designation
                </div>
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <div>Certified True and Correct:</div>
                <div>Date Checked (MM/DD/YYYY):</div>
            </div>
        </div>


    </div>

    <!-- Certification Section -->
    <div class="section certification">
        <h3 style="background-color: #d3cfcf; padding: 5px; text-align: center; font-weight: bold; border: 1px solid #000;">CERTIFICATION</h3>
        
        <p style="margin: 10px; text-align: justify;">
            I CERTIFY that this is a true record of 
            <span style="text-decoration: underline;"><?= htmlspecialchars($learner['first_name'] . ' ' . $learner['last_name']); ?></span> 
            with LRN <span style="text-decoration: underline;"><?= htmlspecialchars($learner['lrn']); ?></span>
            and that he/she is eligible for admission to Grade _____.
        </p>
        
        <div style="display: flex; justify-content: space-between; margin: 10px 0;">
            <div style="flex: 1; border-top: 1px solid #000; padding-top: 5px;">
                <p>Name of School: <span style="text-decoration: underline;">____________________</span></p>
            </div>
            <div style="flex: 1; text-align: center; border-top: 1px solid #000; padding-top: 5px;">
                <p>School ID: <span style="text-decoration: underline;">__________</span></p>
            </div>
            <div style="flex: 1; text-align: right; border-top: 1px solid #000; padding-top: 5px;">
                <p>Last School Year Attended: <span style="text-decoration: underline;">____________________</span></p>
            </div>
        </div>
        
        <div style="display: flex; justify-content: space-around; align-items: center; margin-top: 30px;">
            <div style="text-align: center; flex: 1;">
                <p style="border-top: 1px solid #000; width: 100px; margin: 0 auto;">Date</p>
            </div>
            <div style="text-align: center; flex: 2;">
                <p style="border-top: 1px solid #000; width: 250px; margin: 0 auto;">Signature of Principal/School Head over Printed Name</p>
            </div>
            <div style="text-align: center; flex: 1;">
                <p style="border-top: 1px solid #000; width: 200px; margin: 0 auto;">(Affix School Seal Here)</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
