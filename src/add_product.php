<?php
session_start();

// Ensure admin is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require('dbconnection.php');

// Handle product addition
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    if (!empty($name) && !empty($price)) {
        $query = "INSERT INTO products (name, price) VALUES ('$name', '$price')";
        if (mysqli_query($conn, $query)) {
            $message = "Product added successfully!";
        } else {
            $message = "Error adding product: " . mysqli_error($conn);
        }
    } else {
        $message = "Please provide both product name and price.";
    }
}

// Handle product updates
if (isset($_POST['update_product'])) {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    if (!empty($name) && !empty($price)) {
        $query = "UPDATE products SET name = '$name', price = '$price' WHERE id = $product_id";
        if (mysqli_query($conn, $query)) {
            $message = "Product updated successfully!";
        } else {
            $message = "Error updating product: " . mysqli_error($conn);
        }
    } else {
        $message = "Please provide both product name and price.";
    }
}

// Handle product deletion
if (isset($_GET['delete_product'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['delete_product']);
    $query = "DELETE FROM products WHERE id = $product_id";
    if (mysqli_query($conn, $query)) {
        $message = "Product deleted successfully!";
    } else {
        $message = "Error deleting product: " . mysqli_error($conn);
    }
}

// Fetch all products
$productQuery = "SELECT * FROM products";
$productResult = mysqli_query($conn, $productQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <link rel="stylesheet" href="add_product.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Manage Products</h1>

        <!-- Display message -->
        <?php if (isset($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Add new product form -->
        <section>
            <h2>Add New Product</h2>
            <form action="add_product.php" method="POST">
                <label for="name">Product Name:</label>
                <input type="text" name="name" required>

                <label for="price">Product Price:</label>
                <input type="number" step="0.01" name="price" required>

                <button type="submit" name="add_product">Add Product</button>
            </form>
        </section>

        <!-- List of products -->
        <section>
            <h2>Current Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($productResult->num_rows > 0): ?>
                        <?php while ($product = $productResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $product['id']; ?></td>
                                <td><?php echo $product['name']; ?></td>
                                <td><?php echo $product['price']; ?></td>
                                <td>
                                    <form action="add_product.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <input type="text" name="name" value="<?php echo $product['name']; ?>" required>
                                        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                                        <button type="submit" name="update_product">Update</button>
                                    </form>
                                    <a href="add_product.php?delete_product=<?php echo $product['id']; ?>" class="button">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No products found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>

        <a href="admin_dashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
