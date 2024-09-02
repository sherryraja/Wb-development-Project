<?php
session_start();
require('dbconnection.php');

// SECTION: Login logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $loginType = $_POST['login_type'];  // Determines if it's admin or user login

    // Manually hardcoded admin credentials
    $hardcodedAdminUsername = 'sherry';
    $hardcodedAdminPassword = '1234'; // Plaintext password

    try {
        if ($loginType === 'admin') {
            // Check against hardcoded admin credentials
            if ($username === $hardcodedAdminUsername && $password === $hardcodedAdminPassword) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = 1;  // Assign a fixed ID for admin, e.g., 1
                $_SESSION['user_type'] = 'admin';

                header('Location: admin_dashboard.php');
                exit();
            } else {
                // If not the hardcoded admin, continue checking the database
                $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $admin = $result->fetch_assoc();

                if ($admin && password_verify($password, $admin['password'])) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $admin['id'];
                    $_SESSION['user_type'] = 'admin';

                    header('Location: admin_dashboard.php');
                    exit();
                } else {
                    $message = "Invalid admin username or password.";
                }
            }
        } else {
            // Regular user login logic
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'user';

                header('Location: user_dashboard.php');
                exit();
            } else {
                $message = "Invalid user username or password.";
            }
        }
    } catch (Exception $e) {
        $message = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="username">Username</label>
            <input type="text" name="username" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <label for="login_type">Login as:</label>
            <select name="login_type" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit">Login</button>
        </form>

        <p><a href="forgot_password.php">Forgot your password?</a></p>
        <p>Don't have an account? <a href="register.php">Register here</a></p>

        <!-- Back to Home Button -->
        <p><a href="index.php" class="back-home">Back to Home</a></p>
    </div>
</body>
</html>
