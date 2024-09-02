<?php
session_start();
require('dbconnection.php'); // Ensure this connects to the database using MySQLi and assigns to $conn

// Initialize totalAmount
$totalAmount = 0;

// Calculate total price of the cart for display
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
        $price = isset($item['price']) ? (float)$item['price'] : 0;
        $totalAmount += $price * $quantity;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $customerName = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $customerEmail = mysqli_real_escape_string($conn, $_POST['customer_email']);
    $customerAddress = mysqli_real_escape_string($conn, $_POST['customer_address']);
    $cardNumber = mysqli_real_escape_string($conn, $_POST['card_number']);
    $cardExpiry = mysqli_real_escape_string($conn, $_POST['card_expiry']);
    $cardCVV = mysqli_real_escape_string($conn, $_POST['card_cvv']);

    if (empty($customerName) || empty($customerEmail) || empty($customerAddress) || empty($cardNumber) || empty($cardExpiry) || empty($cardCVV)) {
        echo "Please fill in all fields.";
        exit;
    }

    $orderTotalAmount = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $price = isset($item['price']) ? (float)$item['price'] : 0;
            $orderTotalAmount += $price * $quantity;
        }
    }

    mysqli_begin_transaction($conn);
    try {
        $orderQuery = "INSERT INTO orders (customer_name, customer_email, customer_address, total_amount, payment_status) VALUES ('$customerName', '$customerEmail', '$customerAddress', '$orderTotalAmount', 'Paid')";
        
        if (!mysqli_query($conn, $orderQuery)) {
            throw new Exception("Order insertion failed: " . mysqli_error($conn));
        }

        $orderId = mysqli_insert_id($conn);

        $itemQuery = "INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $itemQuery);
        
        foreach ($_SESSION['cart'] as $item) {
            $productId = isset($item['id']) ? (int)$item['id'] : 0;
            $productName = isset($item['name']) ? mysqli_real_escape_string($conn, $item['name']) : 'Unknown Item';
            $quantity = isset($item['quantity']) ? (int)$item['quantity'] : 1;
            $price = isset($item['price']) ? (float)$item['price'] : 0.00;

            if ($productId > 0) {
                mysqli_stmt_bind_param($stmt, 'iisid', $orderId, $productId, $productName, $quantity, $price);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Order item insertion failed: " . mysqli_error($conn));
                }
            }
        }

        mysqli_commit($conn);
        unset($_SESSION['cart']);
        echo "Order placed successfully! Your order ID is: " . $orderId;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "Failed to place the order. Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="checkout.css"> <!-- Add your styles here -->
</head>
<body>

<h1>Checkout</h1>

<!-- Back Button -->
<a href="javascript:history.back()" class="btn">Back to Cart</a>

<!-- Display Cart Summary -->
<h2>Your Cart</h2>
<ul>
    <?php if (!empty($_SESSION['cart'])): ?>
        <?php foreach ($_SESSION['cart'] as $item): ?>
            <li>
                <?php echo htmlspecialchars($item['name'] ?? 'Unknown Item'); ?> -
                <?php echo htmlspecialchars($item['quantity'] ?? 1); ?> x 
                $<?php echo number_format($item['price'], 2); ?>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Your cart is empty!</p>
    <?php endif; ?>
</ul>

<!-- Calculate Total Amount Here -->
<h3>Total Amount: $<?php echo number_format($totalAmount, 2); ?></h3>

<!-- Checkout Form -->
<form action="checkout.php" method="post">
    <h2>Customer Information</h2>
    <label for="customer_name">Name:</label>
    <input type="text" name="customer_name" id="customer_name" required><br>

    <label for="customer_email">Email:</label>
    <input type="email" name="customer_email" id="customer_email" required><br>

    <label for="customer_address">Address:</label>
    <textarea name="customer_address" id="customer_address" required></textarea><br>

    <h2>Payment Information</h2>
    <label for="card_number">Card Number:</label>
    <input type="text" name="card_number" id="card_number" required><br>

    <label for="card_expiry">Card Expiry (MM/YY):</label>
    <input type="text" name="card_expiry" id="card_expiry" required><br>

    <label for="card_cvv">Card CVV:</label>
    <input type="text" name="card_cvv" id="card_cvv" required><br>

    <input type="submit" name="place_order" value="Place Order" class="btn">
</form>

</body>
</html>
