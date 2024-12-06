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

// Fetch grades for the learner and join with the subjects table
$sqlGrades = "
    SELECT subjects.subject_name, grades.first_grading, grades.second_grading, grades.third_grading, 
           grades.fourth_grading, grades.final_grade, grades.status, grades.general_average
    FROM grades 
    JOIN subjects ON grades.subject_id = subjects.id 
    WHERE grades.lrn = ?";
$stmtGrades = $conn->prepare($sqlGrades);
$stmtGrades->bind_param("s", $lrn);
$stmtGrades->execute();
$resultGrades = $stmtGrades->get_result();

$grades = [];
if ($resultGrades->num_rows > 0) {
    while ($row = $resultGrades->fetch_assoc()) {
        $grades[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    </style>
</head>
<body>
    

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
                <p>Name Extn: (Jr, II, III): <?= htmlspecialchars($learner['name_ext'] ?? ''); ?></p>
                <p>Learner Reference Number (LRN): <?= htmlspecialchars($learner['lrn']); ?></p>
            </div>
            <div class="column">
                <p>Birthdate (mm/dd/yyyy): <?= htmlspecialchars($learner['dob']); ?></p>
                <p>Sex: <?= htmlspecialchars($learner['gender']); ?></p>
                <p>Guardian Name: <?= htmlspecialchars($learner['guardian_name']); ?></p>
                <p>School Attended: <?= htmlspecialchars($learner['school_attended']); ?></p>
            </div>
        </div>
    </div>

    <!-- Eligibility for JHS Enrollment -->
    <div class="section">
        <h3>ELIGIBILITY FOR JHS ENROLLMENT</h3>
        <p>
            <input type="checkbox"> Elementary School Completer 
            General Average: _____ Citation (If Any): ____________________
        </p>
        <p>
            Name of Elementary School: ___________ School ID: _____ 
            Address of School: ___________
        </p>
        <p>
            Other Credential Presented: 
            <input type="checkbox"> PEPT Passer Rating: _____ 
            <input type="checkbox"> ALS A & E Passer Rating: _____ 
            <input type="checkbox"> Others: ______
        </p>
    </div>

    <!-- Scholastic Record for Grades 7 to 10 -->
    <div class="section">
        <h3>SCHOLASTIC RECORD</h3>
        <?php for ($grade = 7; $grade <= 10; $grade++): ?>
    <div class="grade-record">
        <p><span class="hidden-grade-label"><strong>Grade <?= $grade ?>:</strong></span> School: ______ School ID: _____ District: _____ Division: _____ Region: _____</p>
        <p>Classified as Grade: <?= $grade ?> Section: _____ School Year: _____ Name of Adviser/Teacher: ______ Signature: ______</p>
        <table class="table">
                <tr>
                    <th>LEARNING AREAS</th>
                    <th>1st Quarter</th>
                    <th>2nd Quarter</th>
                    <th>3rd Quarter</th>
                    <th>4th Quarter</th>
                    <th>Final Rating</th>
                    <th>Remarks</th>
                </tr>
                <?php foreach ($grades as $gradeData): ?>
                <tr>
                    <td><?= htmlspecialchars($gradeData['subject_name']); ?></td>
                    <td><?= htmlspecialchars($gradeData['first_grading']); ?></td>
                    <td><?= htmlspecialchars($gradeData['second_grading']); ?></td>
                    <td><?= htmlspecialchars($gradeData['third_grading']); ?></td>
                    <td><?= htmlspecialchars($gradeData['fourth_grading']); ?></td>
                    <td><?= htmlspecialchars($gradeData['final_grade']); ?></td>
                    <td><?= htmlspecialchars($gradeData['status']); ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" style="text-align: right;">General Average</td>
                    <td><?= htmlspecialchars($grades[0]['general_average'] ?? ''); ?></td>
                    <td></td>
                </tr>
            </table>
        </div> 
        <?php endfor; ?>
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
