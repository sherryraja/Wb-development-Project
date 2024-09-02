<?php
require('dbconnection.php'); // Include the database connection file

if ($pdo) {
    echo "Database connection is working.";
} else {
    echo "Failed to connect to the database.";
}
?>
