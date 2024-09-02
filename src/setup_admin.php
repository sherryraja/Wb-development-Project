<?php
require('dbconnection.php');

// Admin credentials to be set
$username = 'sherry';  // Username for admin login
$password = 'root';  // Password for admin login

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

try {
    // Insert admin credentials into the database
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
    echo "Admin credentials have been set up successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
