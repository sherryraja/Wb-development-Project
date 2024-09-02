<?php
require('dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id, email FROM users WHERE email = ? UNION SELECT id, email FROM admins WHERE email = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();

        $resetLink = "http://yourwebsite.com/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click on this link to reset your password: " . $resetLink;
        mail($email, $subject, $message);

        echo "A password reset link has been sent to your email.";
    } else {
        echo "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        /* Basic styling */
        .forgot-password-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }
        button, input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .back-button {
            background-color: #f0f0f0;
            color: #333;
            text-decoration: none;
            padding: 10px;
            display: inline-block;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <h1>Forgot Password</h1>
        <form action="forgot_password.php" method="POST">
            <label for="email">Enter your email</label>
            <input type="email" name="email" required>
            <button type="submit">Request Reset Link</button>
        </form>

        <!-- Back Button -->
        <a href="javascript:history.back()" class="back-button">Back to Previous Page</a>
    </div>
</body>
</html>
