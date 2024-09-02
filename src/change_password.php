<?php
require('dbconnection.php');

$currentUsername = 'admin';  // Username of the admin whose password is to be changed
$newPassword = 'newpassword';  // New password

// Hash the new password
$hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    // Update the password in the database
    $stmt = $pdo->prepare("UPDATE admins SET password = :password WHERE username = :username");
    $stmt->execute(['password' => $hashedNewPassword, 'username' => $currentUsername]);
    echo "Password has been updated successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
