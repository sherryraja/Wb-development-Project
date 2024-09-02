<?php
require('dbconnection.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $reset = $result->fetch_assoc();

    if ($reset) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $newPassword = $_POST['password'];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $email = $reset['email'];

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? UNION UPDATE admins SET password = ? WHERE email = ?");
            $stmt->bind_param("ssss", $hashedPassword, $email, $hashedPassword, $email);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();

            echo "Your password has been reset successfully!";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <div class="reset-password-container">
        <h1>Reset Password</h1>
        <form action="reset_password.php?token=<?php echo htmlspecialchars($_GET['token']); ?>" method="POST">
            <label for="password">Enter your new password</label>
            <input type="password" name="password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
