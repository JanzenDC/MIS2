<?php
// db_connection.php

// Database connection parameters
$servername = "localhost"; // Replace with your server name if different
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$database = "school_db";    // Replace with your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally set the character set to UTF-8 for better compatibility
if (!$conn->set_charset("utf8")) {
    echo "Error loading character set utf8: " . $conn->error; // Handle character set error
}

// Your database connection is now established and ready to use
?>
