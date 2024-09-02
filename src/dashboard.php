<?php
session_start();
require('dbconnection.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Not logged in, redirect to login page
    header('Location: login.php');
    exit();
}

// Rest of the code for dashboard.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <!-- Page content here -->
</body>
</html>
