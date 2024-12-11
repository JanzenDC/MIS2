<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $behavior_type = mysqli_real_escape_string($conn, $_POST['behavior_type']);
    $first_behavior_statement = mysqli_real_escape_string($conn, $_POST['first_behavior_statement']);
    $second_behavior_statement = mysqli_real_escape_string($conn, $_POST['second_behavior_statement']);

    // Check if another record with the same coreName already exists (excluding current record)
    $check_duplicate_query = "SELECT COUNT(*) as count 
                               FROM core_values_db 
                               WHERE coreName = '$behavior_type' 
                               AND coreID != '$id'";
    
    $duplicate_result = $conn->query($check_duplicate_query);
    $duplicate_row = $duplicate_result->fetch_assoc();

    // If duplicate exists, prevent update
    if ($duplicate_row['count'] > 0) {
        echo "<script>
                alert('A record with this behavior type already exists. Please choose a different behavior type.');
                window.history.back();
              </script>";
        exit();
    }

    // If no duplicate, proceed with update
    $update_query = "UPDATE core_values_db 
                     SET coreName = '$behavior_type', 
                         behaviour_one = '$first_behavior_statement', 
                         behavior_two = '$second_behavior_statement' 
                     WHERE coreID = '$id'";

    // Execute the update query
    if ($conn->query($update_query) === TRUE) {
        echo "<script>
                alert('Record updated successfully');
                window.location.href='card-maintenance.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating record: " . addslashes($conn->error) . "');
                window.location.href='card-maintenance.php';
              </script>";
    }

    $conn->close();
    exit();
}
?>