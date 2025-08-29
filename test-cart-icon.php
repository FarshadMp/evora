<?php
session_start();
require_once 'includes/cart-functions.php';

// Add a test item to cart for testing
if (isset($_GET['add_test'])) {
    addToCart(1, 1);
    header('Location: test-cart-icon.php');
    exit;
}

// Clear cart for testing
if (isset($_GET['clear_test'])) {
    clearCart();
    header('Location: test-cart-icon.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Icon Test - ÉVORA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-evora-beige">
    <!-- Header -->
    <?php include 'header.php'; ?>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-light text-primary mb-8">Cart Icon Test Page</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-medium text-primary mb-4">Current Cart Status</h2>

            <div class="space-y-4">
                <div>
                    <strong>Cart Item Count:</strong> <?php echo getCartItemCount(); ?>
                </div>
                <div>
                    <strong>Cart Total:</strong> Rs. <?php echo number_format(getCartTotal(), 2); ?>
                </div>
                <div>
                    <strong>Cart Items:</strong>
                    <ul class="mt-2">
                        <?php foreach (getCartItems() as $item): ?>
                            <li><?php echo htmlspecialchars($item['name']); ?> - Qty: <?php echo $item['quantity']; ?> - Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="mt-6 space-x-4">
                <a href="?add_test=1" class="bg-evora-brown text-white px-4 py-2 rounded hover:bg-primary transition-colors">Add Test Item</a>
                <a href="?clear_test=1" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">Clear Cart</a>
                <a href="cart.php" class="bg-primary text-white px-4 py-2 rounded hover:bg-evora-brown transition-colors">View Cart</a>
            </div>
        </div>

        <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-medium text-primary mb-4">Test Navigation</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="index.php" class="bg-gray-100 p-4 rounded hover:bg-gray-200 transition-colors">
                    <strong>Home Page</strong><br>
                    <span class="text-sm text-gray-600">Test cart icon on main page</span>
                </a>
                <a href="bestsellers.php" class="bg-gray-100 p-4 rounded hover:bg-gray-200 transition-colors">
                    <strong>Bestsellers</strong><br>
                    <span class="text-sm text-gray-600">Test cart icon on products page</span>
                </a>
                <a href="product-details.php?id=1" class="bg-gray-100 p-4 rounded hover:bg-gray-200 transition-colors">
                    <strong>Product Details</strong><br>
                    <span class="text-sm text-gray-600">Test cart icon on product page</span>
                </a>
                <a href="contact.php" class="bg-gray-100 p-4 rounded hover:bg-gray-200 transition-colors">
                    <strong>Contact</strong><br>
                    <span class="text-sm text-gray-600">Test cart icon on contact page</span>
                </a>
                <a href="how-to-order.php" class="bg-gray-100 p-4 rounded hover:bg-gray-200 transition-colors">
                    <strong>How to Order</strong><br>
                    <span class="text-sm text-gray-600">Test cart icon on info page</span>
                </a>
                <a href="cart.php" class="bg-gray-100 p-4 rounded hover:bg-gray-200 transition-colors">
                    <strong>Cart Page</strong><br>
                    <span class="text-sm text-gray-600">Test cart icon on cart page</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="assets/js/script.js"></script>
</body>

</html>