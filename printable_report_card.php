<?php
session_start();

// Check if LRN is provided
if (!isset($_GET['lrn']) || !isset($_GET['grade'])) {
    echo "No student selected.";
    exit;
}

$lrn = $_GET['lrn'];
$gradeSelected = $_GET['grade'];

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

// Sanitize input to prevent SQL injection
$lrn = $conn->real_escape_string($lrn);
$gradeSelected = $conn->real_escape_string($gradeSelected);

// Fetch learner details including guardian name and school attended
$sqlLearner = "SELECT *
               FROM learners 
               WHERE lrn = '$lrn'";
$resultLearner = $conn->query($sqlLearner);

// Check if learner is found
if ($resultLearner->num_rows == 0) {
    echo "No records found for the selected student.";
    exit;
}

$learner = $resultLearner->fetch_assoc();

// Concatenate the full name
$fullName = $learner['first_name'] . 
    (isset($learner['middle_name']) && !empty($learner['middle_name']) ? ' ' . $learner['middle_name'] : '') . 
    ' ' . $learner['last_name'];

// Fetch grades for the learner and join with the subjects table
$sqlGrades = "
    SELECT 
        g.lrn, 
        g.subject_id, 
        g.first_grading, 
        g.second_grading, 
        g.third_grading, 
        g.fourth_grading, 
        g.final_grade, 
        g.status,
        g.grade,
        s.subject_name
    FROM 
        grades g
    LEFT JOIN 
        subjects s ON g.subject_id = s.id
    WHERE 
        g.lrn = '$lrn' 
        AND g.grade = '$gradeSelected'
    ORDER BY 
        s.subject_name
";

$resultGrades = $conn->query($sqlGrades);

$grades = [];
if ($resultGrades->num_rows > 0) {
    while ($row = $resultGrades->fetch_assoc()) {
        $grades[] = $row;
    }
}
$query = "SELECT from_year, to_year FROM school_years ORDER BY created_at DESC LIMIT 1";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $defaultFromYear = $row['from_year'];
    $defaultToYear = $row['to_year'];
    $fullyear = $defaultFromYear . ' - ' . $defaultToYear;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>SF9 Report Card</title>
    <style>
           body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 20px;
        }
        .report-card { max-width: 952px; margin: 0 auto; border: 1px solid #000; padding: 20px; }
        .header, .footer { text-align: center; }
        h2, h3, h4 { margin: 5px 0; }
        .learner-info { text-align: center; margin-bottom: 20px; }
        .learner-info h3 { font-size: 1.2em; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: center; font-size: 0.9em; }
        .subject-row { text-align: left; padding-left: 10px; }
        .footer-section { display: flex; justify-content: space-between; margin-top: 20px; }
        .core-values { font-size: 0.9em; margin-top: 10px; }
        .descriptor-table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        .descriptor-table th, .descriptor-table td { border: 1px solid #000; padding: 6px; text-align: center; }

        .section-title { 
        text-align: center; /* Center the section title */
        font-size: 1.0em; 
        margin-top: 20px; 
        font-weight: normal; /* Remove bold styling */
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

        .container {
            display: flex;
            justify-content: space-between;
            max-width: 1000px;
            margin: auto;
            border: 1px solid black;
            padding: 20px;
            box-sizing: border-box;
        }
        .left, .right {
            width: 48%;
        }
        .header, .section-title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .section-title {
            margin-top: 15px;
        }
        .small-text {
            font-size: 12px;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
        }
        .no-border td {
            border: none;
            padding: 2px 4px;
        }
        .signature-table td {
            padding: 5px;
            border: none;
        }
        .underline {
            display: inline-block;
            border-bottom: 1px solid black;
            width: 150px;
            height: 1px;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .indent {
            margin-left: 20px;
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
    <!-- Left Side: Attendance and Parent Signature -->
    <div class="left">
        <div class="header">Attendance Record</div>
        <table>
            <tr>
                <th rowspan="2">No. of School Days</th>
                <th colspan="12">Months</th>
                <th rowspan="2">Total</th>
            </tr>
            <tr>
                <th>Jun</th><th>Jul</th><th>Aug</th><th>Sept</th><th>Oct</th><th>Nov</th>
                <th>Dec</th><th>Jan</th><th>Feb</th><th>Mar</th><th>Apr</th><th>May</th>
            </tr>
            <tr><td>No. of Days</td><td></td><td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>Present</td><td></td><td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>No. of Times Absent</td><td></td><td></td><td></td><td></td><td></td><td></td>
                <td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        </table>

        <div class="section-title">PARENT/GUARDIAN'S SIGNATURE</div>
        <table class="signature-table">
            <tr>
           <td class="small-text">1st Quarter:</td><td>__________________________</td>
            </tr>
            <tr>
                <td class="small-text">2nd Quarter:</td><td>__________________________</td>
            </tr>
            <tr>
                <td class="small-text">3rd Quarter:</td><td>__________________________</td>
            </tr>
            <tr>
                <td class="small-text">4th Quarter:</td><td>__________________________</td>
            </tr>
        </table>
    </div>

    <!-- Right Side: Report Card and Certification -->
    <div class="right" style="position: relative;">
    <img src="https://hrms-jshs.edu.ph/wp-content/uploads/2021/07/DepEd.png" style="width: 100px; position: absolute; top: 0; left: 0;">
        <div style="text-align: center; font-size: 12px; font-weight: bold; position: absolute; top: -10px; left: 23px;">JHS 9 - ES</div>
        <div class="header" style=>Republic of the Philippines<br>DEPARTMENT OF EDUCATION</div>
        
        <table class="no-border">
            <tr>
                <td class="small-text">Region: __________________________</td>
            </tr>
            <tr>
                <td class="small-text">Division: __________________________</td> 
            </tr>
            <tr>
                <td class="small-text">District: __________________________</td>
            </tr>
            <tr>
                <td class="small-text">School: __________________________</td>
            </tr>
        </table>
<br>
<div class="header">LEARNER'S PROGRESS REPORT CARD<br>School Year <?= isset($fullyear) ? $fullyear : '__________' ?></div>

<div style='padding: 12px;'>
    Name: <span style="display: inline-block; border-bottom: 1px solid #000; width: 200px;">
        <?= 
            (isset($learner['first_name']) ? $learner['first_name'] . ' ' : '') .
            (isset($learner['middle_name']) && !empty($learner['middle_name']) ? $learner['middle_name'] . ' ' : '') .
            (isset($learner['last_name']) ? $learner['last_name'] : '')
        ?>
    </span><br>
    Age: <span style="display: inline-block; border-bottom: 1px solid #000; width: 50px;">
        <?= isset($learner['age']) ? $learner['age'] : '' ?>
    </span>
    Sex: <span style="display: inline-block; border-bottom: 1px solid #000; width: 100px;">
        <?= isset($learner['gender']) ? $learner['gender'] : '' ?>
    </span><br>
    Grade: <span style="display: inline-block; border-bottom: 1px solid #000; width: 50px;">
        <?= isset($learner['grade_level']) ? $learner['grade_level'] : '' ?>
    </span>
    Section: <span style="display: inline-block; border-bottom: 1px solid #000; width: 100px;">
        <?= isset($grades[0]['section']) ? $grades[0]['section'] : '' ?>
    </span>
    LRN: <span style="display: inline-block; border-bottom: 1px solid #000; width: 150px;">
        <?= isset($lrn) ? $lrn : '' ?>
    </span>
</div>

<!-- <table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="width: 15%; white-space: nowrap;" class="small-text">Name:</td>
        <td style="border-bottom: 1px solid #000; width: 40%;">
            <?= 
                (isset($learner['first_name']) ? $learner['first_name'] . ' ' : '') .
                (isset($learner['middle_name']) && !empty($learner['middle_name']) ? $learner['middle_name'] . ' ' : '') .
                (isset($learner['last_name']) ? $learner['last_name'] : '')
            ?>
        </td>
        <td style="width: 10%; white-space: nowrap;" class="small-text">Sex:</td>
        <td style="border-bottom: 1px solid #000; width: 35%;">
            <?= isset($learner['gender']) ? $learner['gender'] : '' ?>
        </td>
    </tr>
    <tr>
        <td class="small-text">Age:</td>
        <td style="border-bottom: 1px solid #000;">
            <?= isset($learner['age']) ? $learner['age'] : '' ?>
        </td>
        <td class="small-text">Grade:</td>
        <td style="border-bottom: 1px solid #000;">
            <?= isset($learner['grade_level']) ? $learner['grade_level'] : '' ?>
        </td>
    </tr>
    <tr>
        <td class="small-text">Section:</td>
        <td style="border-bottom: 1px solid #000;">
            <?= isset($grades[0]['section']) ? $grades[0]['section'] : '' ?>
        </td>
        <td class="small-text">LRN:</td>
        <td style="border-bottom: 1px solid #000;">
            <?= isset($lrn) ? $lrn : '' ?>
        </td>
    </tr>
</table> -->


        <br>
        <p class="indent small-text">Dear Parent,</p>
        <p class="indent small-text">This report card shows the ability and the progress your child has made in the different learning areas as well as his/her progress in core values.</p>
        <p class="indent small-text">The school welcomes you should you desire to know more about your child’s progress.</p>
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div><?= isset($grades[0]['adviser']) ? $grades[0]['adviser'] : '__________' ?></div>
                <p class="indent small-text">Adviser</p>
            </div>
            <div>
                <div class="underline"></div>
                <p class="indent small-text">Principal</p>
            </div>
        </div>


        <div class="section-title">Certificate of Transfer</div>
        <p class="indent small-text">Admitted to Grade ______ Section ______ Room ______</p>
        <p class="indent small-text">Eligible for Admission to Grade ______</p>
        <p class="indent small-text">Approved:</p>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 25px;">
            <div>
                <div class="underline"></div>
                <p class="indent small-text">Head Teacher / Principal</p>
            </div>
            <div>
                <div class="underline"></div>
                <p class="indent small-text">Teacher</p>
            </div>
        </div>


        <div class="section-title">Cancellation of Eligibility to Transfer</div>
        <p class="indent small-text">Admitted in ________________________</p>
        <p class="indent small-text">Date:_____________________________</p>
        <div class="underline" style='margin-left: 270px;'></div>
        <p class="indent small-text" style='margin-left: 320px;'>Principal</p>
    </div>
</div>

<br><br><br><br><br><br><br><br>
<div class="report-card">

    <!-- Report on Learning Progress -->
    <div class="section-title">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</div>
<table class="table">
    <tr>
        <th rowspan="2">Learning Areas</th>
        <th colspan="4">Quarter</th>
        <th rowspan="2">Final Rating</th>
        <th rowspan="2">Remarks</th>
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
    </tr>

    <?php foreach ($grades as $grade): ?>
        <tr>
            <td class="subject-row"><?php echo htmlspecialchars($grade['subject_name'] ?? 'Unknown Subject'); ?></td>
            <td><?php echo htmlspecialchars($grade['first_grading'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($grade['second_grading'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($grade['third_grading'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($grade['fourth_grading'] ?? '-'); ?></td>
            <td><?php echo htmlspecialchars($grade['final_grade'] ?? '-'); ?></td>
            <td><?php echo ($grade['final_grade'] >= 75) ? 'Passed' : 'Failed'; ?></td>
        </tr>
    <?php endforeach; ?>
    
    <tr>
        <td colspan="5"><strong>General Average</strong></td>
        <td colspan="2">
            <?php
            $total_grades = 0;
            $subject_count = 0;
            foreach ($grades as $grade) {
                if (!is_null($grade['final_grade'])) {
                    $total_grades += $grade['final_grade'];
                    $subject_count++;
                }
            }
            echo $subject_count ? round($total_grades / $subject_count, 2) : '-';
            ?>
        </td>
    </tr>
</table>

    <!-- Report on Learner's Observed Values -->
    <div class="section-title">REPORT ON LEARNER'S OBSERVE VALUES</div>
    <table class="table">
        <tr>
            <th>Core Values</th>
            <th>Behavior Statements</th>
            <th>1st</th>
            <th>2nd</th>
            <th>3rd</th>
            <th>4th</th>
        </tr>
        <tr>
            <td rowspan="2">Maka-Diyos</td>
            <td>Expresses one's spiritual beliefs while respecting others'</td>
            <td>AO</td><td>SO</td><td>RO</td><td>NO</td>
        </tr>
        <tr>
            <td>Shows adherence to ethical principles by upholding truth</td>
            <td>AO</td><td>SO</td><td>RO</td><td>NO</td>
        </tr>
        <tr>
            <td rowspan="2">Maka-Tao</td>
            <td>Demonstrates a caring attitude towards others</td>
            <td>AO</td><td>SO</td><td>RO</td><td>NO</td>
        </tr>
        <tr>
            <td>Acts with kindness and compassion</td>
            <td>AO</td><td>SO</td><td>RO</td><td>NO</td>
        </tr>
        <tr>
            <td rowspan="2">Maka-Kalikasan</td>
            <td>Shows care for the environment</td>
            <td>AO</td><td>SO</td><td>RO</td><td>NO</td>
        </tr>
        <tr>
            <td>Participates in activities that promote environmental awareness</td>
            <td>AO</td><td>SO</td><td>RO</td><td>NO</td>
        </tr>
        <tr>
            <td rowspan="2">Maka-Bansa</td>
            <td>Demonstrates love for country</td>
            <td>AO</td><td>SO</td><td>RO</td><td>NO</td>
        </tr>
        <tr>
            <td>Participates in community service activities</td>
            <td>AO</td><td>SO</td><td>RO</td><td>NO</td>
        </tr>
    </table>

    <div class="footer-section">
    <table class="descriptor-table" style="width: 60%;  font-size: 0.9em; ">
        <tr>
            <th>Descriptors</th>
            <th>Grading Scale</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td>Outstanding</td>
            <td>90-100</td>
            <td>Passed</td>
        </tr>
        <tr>
            <td>Very Satisfactory</td>
            <td>85-89</td>
            <td>Passed</td>
        </tr>
        <tr>
            <td>Satisfactory</td>
            <td>80-84</td>
            <td>Passed</td>
        </tr>
        <tr>
            <td>Fairly Satisfactory</td>
            <td>75-79</td>
            <td>Passed</td>
        </tr>
        <tr>
            <td>Did Not Meet Expectations</td>
            <td>Below 75</td>
            <td>Failed</td>
        </tr>
    </table>

        <div class="grading-scale"style="width: 45%;  font-size: 0.9em; margin-left: 100px;">
            <table class="descriptor-table">
                <tr><th>Marking</th><th>Non-Numerical Rating</th></tr>
                <tr><td>AO</td><td>Always Observed</td></tr>
                <tr><td>SO</td><td>Sometimes Observed</td></tr>
                <tr><td>RO</td><td>Rarely Observed</td></tr>
                <tr><td>NO</td><td>Not Observed</td></tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
