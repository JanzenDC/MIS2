<?php
session_start(); // Start the session

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: login_page.php"); // Change 'login.php' to your desired redirect page
exit();
?>