<?php
session_start();
require('dbconnection.php');

if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);
    $user_id = $_SESSION['user_id'];

    $query = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $query->bind_param("ii", $user_id, $product_id);
    $query->execute();

    header("Location: cart.php"); // Redirect to cart page
    exit();
}
?>
