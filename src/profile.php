<?php
session_start();
require('dbconnection.php');

// Redirect to login if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'user') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ensure cart is initialized
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize as an empty array if not set
}

// Fetch user profile and cart items
$user_query = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$user_query->bind_param("i", $user_id);
$user_query->execute();
$user = $user_query->get_result()->fetch_assoc();

// Fetch available products
$products_query = $conn->query("SELECT * FROM products");

// Handle adding to cart via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    
    // Check if product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        // Product already in cart, increase quantity
        $_SESSION['cart'][$product_id]++;
    } else {
        // Add new product to cart with quantity 1
        $_SESSION['cart'][$product_id] = 1;
    }

    header('Location: user_dashboard.php');
    exit();
}

// Handle cart removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $cart_id = intval($_POST['cart_id']);
    unset($_SESSION['cart'][$cart_id]);

    header('Location: user_dashboard.php');
    exit();
}

// Fetch cart items from the session
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $in_clause = implode(',', array_fill(0, count($product_ids), '?'));

    // Fetch product details from the database for cart items
    $cart_query = $conn->prepare("SELECT id, name, price FROM products WHERE id IN ($in_clause)");
    $cart_query->bind_param(str_repeat('i', count($product_ids)), ...$product_ids);
    $cart_query->execute();
    $result = $cart_query->get_result();

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $cart_items[] = $row;
    }
}

// Handle password update logic
$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Fetch the current hashed password from the database
    $password_query = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $password_query->bind_param("i", $user_id);
    $password_query->execute();
    $storedPasswordHash = $password_query->get_result()->fetch_assoc()['password'];

    // Verify the current password
    if (password_verify($currentPassword, $storedPasswordHash)) {
        if ($newPassword === $confirmPassword) {
            // Hash the new password
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the new password in the database
            $update_query = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_query->bind_param("si", $newPasswordHash, $user_id);
            $update_query->execute();

            $successMessage = "Password updated successfully!";
        } else {
            $errorMessage = "New passwords do not match.";
        }
    } else {
        $errorMessage = "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Food - User Dashboard</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="header">
    <section class="flex">
        <a href="#" class="logo"><i class="fas fa-bone"></i>pedie</a>
        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#category">category</a>
            <a href="#products">products</a>
            <a href="#contact">contact</a>
        </nav>
        <div class="icons">
            <a href="cart.php" class="fas fa-shopping-cart">
                <span id="cart-count">
                    <?php echo count($_SESSION['cart']); ?>
                </span>
            </a>
            <div class="dropdown">
                <a href="#" class="fas fa-user"> Profile</a>
                <div class="dropdown-content">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <form method="POST" action="user_dashboard.php">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>

                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>

                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>

                        <input type="submit" name="update_password" value="Update Password">
                    </form>
                    <p style="color:green;"><?php echo $successMessage; ?></p>
                    <p style="color:red;"><?php echo $errorMessage; ?></p>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </section>
</header>

<!-- Home section -->
<div class="home" id="home">
    <section class="flex">
        <div class="content">
            <h3>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h3>
        </div>
    </section>
</div>

<!-- Products section -->
<section class="products" id="products">
    <h1 class="heading"> <i class="fas fa-paw"></i> Our Pet Products <i class="fas fa-paw"></i> </h1>

    <div class="box-container">
        <?php while ($product = $products_query->fetch_assoc()): ?>
            <div class="box">
                <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                <span>$<?php echo htmlspecialchars($product['price']); ?></span>
                <form method="POST" action="user_dashboard.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <button type="submit" class="btn">Add to Cart</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<!-- Cart section -->
<section class="cart">
    <h1 class="heading"> <i class="fas fa-shopping-cart"></i> Your Cart <i class="fas fa-shopping-cart"></i> </h1>

    <ul>
        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $cart_item): ?>
                <li>
                    <?php echo htmlspecialchars($cart_item['name']); ?> - $<?php echo htmlspecialchars($cart_item['price']); ?> x <?php echo htmlspecialchars($cart_item['quantity']); ?>
                    <form method="POST" action="user_dashboard.php" style="display:inline;">
                        <input type="hidden" name="cart_id" value="<?php echo $cart_item['id']; ?>">
                        <button type="submit" name="remove_from_cart" class="btn">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Your cart is empty.</li>
        <?php endif; ?>
    </ul>
</section>

<!-- custom js file link  -->
<script src="script.js"></script>

<style>
    /* Dropdown menu style for profile */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 200px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        padding: 12px 16px;
        z-index: 1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content p, .dropdown-content form, .dropdown-content a {
        margin: 10px 0;
    }

    .dropdown-content form input[type="password"],
    .dropdown-content form input[type="submit"] {
        display: block;
        margin-bottom: 10px;
    }
</style>

</body>
</html>
