<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require('dbconnection.php');

// Fetch all users from the database without the created_at column
$userQuery = "SELECT id, username, email FROM users";
$userResult = $conn->query($userQuery);

// Handle delete user action
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $deleteId);

    if ($stmt->execute()) {
        header("Location: view_users.php"); // Refresh the page after deleting a user
        exit();
    } else {
        echo "Error deleting user.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="admin_dashboard.css"> <!-- Add your CSS file here -->
</head>
<body>
    <div class="dashboard-container">
        <h1>All Users</h1>

        <!-- Users Table -->
        <section>
            <h2>Manage Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($userResult->num_rows > 0): ?>
                        <?php while ($user = $userResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="button">Edit</a>
                                    <a href="view_users.php?delete_id=<?php echo $user['id']; ?>" class="button" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Back to Dashboard Button -->
        <section>
            <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
        </section>

    </div>
</body>
</html>
