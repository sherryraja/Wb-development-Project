<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

require('dbconnection.php');

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
if (!empty($cart)) {
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $conn->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($cart)), ...$cart);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = array();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Cart</title>
</head>
<body>
    <h1>Your Cart</h1>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li>
                <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p>Price: $<?php echo htmlspecialchars($row['price']); ?></p>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
