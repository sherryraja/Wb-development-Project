<?php
// Database connection parameters
$host = 'mysql';         // Database server name, e.g., 'localhost' or 'mysql' for Docker
$user = 'sherry';        // Your MySQL username
$password = 'root';      // Your MySQL password
$db = 'Pet';             // Your database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Optionally log successful connection in a file instead
// file_put_contents('log.txt', 'Connected successfully to the database!', FILE_APPEND);
?>
