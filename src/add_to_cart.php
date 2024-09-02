<?php
session_start();
require('dbconnection.php'); // Ensure the database connection is included if needed

$response = ['status' => 'error', 'message' => 'An error occurred'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if ($product_id > 0) {
        // Define your product details
        $products = [
            1 => ['name' => 'Tasty Pets Food', 'price' => 14.05],
            2 => ['name' => 'Delicious Pet Snacks', 'price' => 15.40],
            3 => ['name' => 'Healthy Pet Food', 'price' => 10.03],
            4 => ['name' => 'Organic Cat Food', 'price' => 24.99],
            5 => ['name' => 'Cat Treats - Chicken Bites', 'price' => 9.99],
            // Add more products as needed
        ];

        // Check if the product ID exists in the product list
        if (isset($products[$product_id])) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (!isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = $products[$product_id];
                $_SESSION['cart'][$product_id]['quantity'] = 1; // Initialize quantity
                $response = ['status' => 'success', 'message' => 'Item added to cart'];
            } else {
                // Item already in cart, just update the quantity
                $_SESSION['cart'][$product_id]['quantity'] += 1;
                $response = ['status' => 'success', 'message' => 'Item quantity updated'];
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid product'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Invalid product ID'];
    }

    echo json_encode($response);
}
