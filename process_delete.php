<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    
    // Prepare the delete query
    $delete_query = "DELETE FROM core_values_db WHERE coreID = ?";
    
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Successful deletion
        echo "<script>
                alert('Record deleted successfully');
                window.location.href='card-maintenance.php';
              </script>";
    } else {
        // Deletion failed
        echo "<script>
                alert('Error deleting record: " . addslashes($stmt->error) . "');
                window.location.href='card-maintenance.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>