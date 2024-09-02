<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require('dbconnection.php');

// Fetch Orders
$orderQuery = "SELECT * FROM orders";
$orderResult = $conn->query($orderQuery);

// Fetch Contacts
$contactQuery = "SELECT * FROM contacts";
$contactResult = $conn->query($contactQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, Admin!</h1>
        <p>Logged in as: <?php echo $_SESSION['username']; ?></p>

        <!-- Orders Management -->
        <section>
            <h2>Manage Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($orderResult->num_rows > 0): ?>
                        <?php while ($order = $orderResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $order['id']; ?></td>
                                <td><?php echo $order['customer_name']; ?></td>
                                <td><?php echo $order['customer_email']; ?></td>
                                <td><?php echo $order['customer_address']; ?></td>
                                <td><?php echo $order['total_amount']; ?></td>
                                <td><?php echo $order['payment_status']; ?></td>
                                <td><?php echo $order['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No orders found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Customer Contacts -->
        <section>
            <h2>Customer Contacts</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($contactResult->num_rows > 0): ?>
                        <?php while ($contact = $contactResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $contact['id']; ?></td>
                                <td><?php echo $contact['name']; ?></td>
                                <td><?php echo $contact['email']; ?></td>
                                <td><?php echo $contact['phone']; ?></td>
                                <td><?php echo $contact['subject']; ?></td>
                                <td><?php echo $contact['message']; ?></td>
                                <td><?php echo $contact['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7">No contacts found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <!-- Admin Actions -->
        <section>
            <h2>Admin Actions</h2>
            <a href="add_product.php" class="button">Add New Product</a>
            <a href="view_users.php" class="button">View All Users</a>
        </section>

        <!-- Logout -->
        <section>
            <a href="logout.php" class="button">Logout</a>
        </section>
    </div>
</body>
</html>
