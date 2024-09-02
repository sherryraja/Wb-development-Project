<?php
include 'db_connect.php'; // Include your database connection file

// Admin credentials
$username = 'admin'; // Example username
$password = 'sherry'; // Example password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert into the database
$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Admin account created successfully.";
} else {
    echo "Error creating admin account.";
}

$stmt->close();
$conn->close();
?>
