<?php
require 'db_connection.php'; // Include your DB connection here

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lrn = $_POST['lrn'];
    $lastName = $_POST['lastName'];

    // Validate inputs
    if (!empty($lrn) && !empty($lastName)) {
        // Prepare SQL query to search for the learner
        $stmt = $conn->prepare("SELECT * FROM learners WHERE lrn = ? AND last_name = ?");
        $stmt->bind_param("ss", $lrn, $lastName);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a learner was found
        if ($result->num_rows > 0) {
            // Fetch the learner's details
            $learner = $result->fetch_assoc();
            echo "<div class='alert alert-success'>Learner found:</div>";
            echo "<strong>LRN:</strong> " . htmlspecialchars($learner['lrn']) . "<br>";
            echo "<strong>Last Name:</strong> " . htmlspecialchars($learner['last_name']) . "<br>";
            echo "<strong>Status:</strong> " . htmlspecialchars($learner['status']) . "<br>";
            echo "<strong>Grade Level:</strong> " . htmlspecialchars($learner['grade_level']) . "<br>";
            echo "<strong>Guardian:</strong> " . htmlspecialchars($learner['guardian_name']) . "<br>";
            // You can display additional details here as needed
        } else {
            // No learner found
            echo "<div class='alert alert-danger'>No learner found with the provided LRN and Last Name.</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-warning'>Please provide both LRN and Last Name.</div>";
    }
}

$conn->close(); // Close the database connection
?>
