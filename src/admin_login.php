<?php
session_start();

// Database connection parameters
$host = 'mysql';         // Database server name, e.g., 'localhost' or 'mysql' for Docker
$user = 'sherry';        // Your MySQL username
$password = '1234';      // Your MySQL password
$db = 'Pet';             // Your database name

// Create connection
$conn = mysqli_connect($host, $user, $password, $db);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form input
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($admin_id, $admin_username, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['admin_id'] = $admin_id;
            $_SESSION['admin_username'] = $admin_username;
            $_SESSION['loggedin'] = true;          // Set loggedin to true
            $_SESSION['user_type'] = 'admin';      // Set user_type to admin
            
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit();
        } else {
            // Password is incorrect
            $error_message = "Invalid username or password.";
        }
    } else {
        // Username does not exist
        $error_message = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Same styles as before */
    </style>
</head>
<body>
    <div class="admin-login-container">
        <h1>Admin Login</h1>
        <form action="admin_login.php" method="post">
            <input type="text" name="admin_username" placeholder="Admin Username" required>
            <input type="password" name="admin_password" placeholder="Admin Password" required>
            <button type="submit">Login</button>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
        </form>
        <p>Back to <a href="login.php">User Login</a></p>
    </div>
</body>
</html>
