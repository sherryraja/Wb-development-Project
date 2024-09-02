<?php
session_start();
require('dbconnection.php'); // Ensure the database connection is included if needed

// Handle item removal
if (isset($_GET['remove'])) {
    $removeId = $_GET['remove'];
    if (isset($_SESSION['cart'][$removeId])) {
        unset($_SESSION['cart'][$removeId]); // Remove the item from the cart
    }
}

// Handle quantity updates
if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $action = $_POST['action'];

    // Ensure the quantity key exists for the product in the session
    if (isset($_SESSION['cart'][$productId])) {
        if (!isset($_SESSION['cart'][$productId]['quantity'])) {
            $_SESSION['cart'][$productId]['quantity'] = 1; // Default to 1 if not set
        }

        // Increase or decrease quantity based on the action
        if ($action == 'increase') {
            $_SESSION['cart'][$productId]['quantity']++;
        } elseif ($action == 'decrease' && $_SESSION['cart'][$productId]['quantity'] > 1) {
            $_SESSION['cart'][$productId]['quantity']--;
        }
    }
}

// Get cart items
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Set initial quantity to 1 for any item without a quantity
foreach ($cartItems as $id => &$item) {
    if (!isset($item['quantity'])) {
        $item['quantity'] = 1; // Initialize quantity to 1 if not set
    }
}

// Calculate total price
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity']; // Update to calculate based on quantity
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <header>
        <a href="index.php" class="btn">Back to Home</a>
        <h1>Your Cart</h1>
    </header>
    <main>
        <?php if (!empty($cartItems)): ?>
            <ul>
                <?php foreach ($cartItems as $id => $item): ?>
                    <li>
                        <span><?php echo htmlspecialchars($item['name']); ?></span> - 
                        <span>$<?php echo number_format($item['price'], 2); ?></span> - 
                        <span>Quantity: <?php echo $item['quantity']; ?></span>

                        <!-- Increase/Decrease Quantity Buttons -->
                        <form action="cart.php" method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <button type="submit" name="action" value="decrease" class="btn">-</button>
                            <button type="submit" name="action" value="increase" class="btn">+</button>
                            <input type="hidden" name="update_quantity" value="1">
                        </form>

                        <!-- Remove Button -->
                        <a href="cart.php?remove=<?php echo $id; ?>" class="btn">Remove</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <!-- Display Total Price -->
            <h3>Total: $<?php echo number_format($totalPrice, 2); ?></h3>

            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        <?php else: ?>
            <p>Your cart is empty!</p>
        <?php endif; ?>
    </main>
</body>
</html>
