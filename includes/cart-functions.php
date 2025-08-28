<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

/**
 * Initialize cart if it doesn't exist
 */
function initializeCart()
{
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

/**
 * Add item to cart
 */
function addToCart($product_id, $quantity = 1)
{
    initializeCart();

    // Get product details
    $product = getProductById($product_id);
    if (!$product) {
        return ['success' => false, 'message' => 'Product not found'];
    }

    // Check if product is available
    if ($product['status'] === 'SOLD OUT') {
        return ['success' => false, 'message' => 'Product is sold out'];
    }

    // Check if item already exists in cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image_main'],
            'quantity' => $quantity,
            'status' => $product['status']
        ];
    }

    return ['success' => true, 'message' => 'Product added to cart'];
}

/**
 * Update cart item quantity
 */
function updateCartQuantity($product_id, $quantity)
{
    initializeCart();

    if ($quantity <= 0) {
        removeFromCart($product_id);
        return ['success' => true, 'message' => 'Item removed from cart'];
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        return ['success' => true, 'message' => 'Cart updated'];
    }

    return ['success' => false, 'message' => 'Item not found in cart'];
}

/**
 * Remove item from cart
 */
function removeFromCart($product_id)
{
    initializeCart();

    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        return ['success' => true, 'message' => 'Item removed from cart'];
    }

    return ['success' => false, 'message' => 'Item not found in cart'];
}

/**
 * Clear entire cart
 */
function clearCart()
{
    $_SESSION['cart'] = [];
    return ['success' => true, 'message' => 'Cart cleared'];
}

/**
 * Get cart items
 */
function getCartItems()
{
    initializeCart();
    return $_SESSION['cart'];
}

/**
 * Get cart item count
 */
function getCartItemCount()
{
    initializeCart();
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }
    return $count;
}

/**
 * Get cart total
 */
function getCartTotal()
{
    initializeCart();
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

/**
 * Get cart subtotal (before tax/shipping)
 */
function getCartSubtotal()
{
    return getCartTotal();
}

/**
 * Check if cart is empty
 */
function isCartEmpty()
{
    return empty($_SESSION['cart']);
}

/**
 * Display cart icon with item count
 */
function displayCartIcon()
{
    $itemCount = getCartItemCount();
    $hasItems = $itemCount > 0;

    echo '<a href="cart.php" class="relative text-primary hover:text-evora-brown transition-colors duration-200">';
    echo '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>';
    echo '</svg>';

    if ($hasItems) {
        echo '<span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">';
        echo $itemCount > 99 ? '99+' : $itemCount;
        echo '</span>';
    }

    echo '</a>';
}

/**
 * Display cart summary (for header dropdown)
 */
function displayCartSummary()
{
    $cartItems = getCartItems();
    $itemCount = getCartItemCount();
    $total = getCartTotal();

    if (empty($cartItems)) {
        echo '<div class="p-4 text-center text-gray-500">';
        echo '<svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>';
        echo '</svg>';
        echo '<p class="text-sm">Your cart is empty</p>';
        echo '</div>';
        return;
    }

    echo '<div class="p-4">';
    echo '<div class="space-y-3 max-h-64 overflow-y-auto">';

    foreach ($cartItems as $item) {
        echo '<div class="flex items-center space-x-3">';
        echo '<img src="' . htmlspecialchars($item['image']) . '" alt="' . htmlspecialchars($item['name']) . '" class="w-12 h-12 object-cover rounded">';
        echo '<div class="flex-1 min-w-0">';
        echo '<p class="text-sm font-medium text-primary truncate">' . htmlspecialchars($item['name']) . '</p>';
        echo '<p class="text-xs text-gray-500">Qty: ' . $item['quantity'] . '</p>';
        echo '<p class="text-sm font-semibold text-primary">Rs. ' . number_format($item['price'] * $item['quantity'], 2) . '</p>';
        echo '</div>';
        echo '<button onclick="removeFromCart(' . $item['id'] . ')" class="text-gray-400 hover:text-red-500">';
        echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
        echo '</svg>';
        echo '</button>';
        echo '</div>';
    }

    echo '</div>';
    echo '<div class="border-t pt-3 mt-3">';
    echo '<div class="flex justify-between items-center mb-3">';
    echo '<span class="text-sm font-medium text-primary">Total (' . $itemCount . ' items):</span>';
    echo '<span class="text-lg font-semibold text-primary">Rs. ' . number_format($total, 2) . '</span>';
    echo '</div>';
    echo '<a href="cart.php" class="block w-full bg-primary text-white text-center py-2 px-4 rounded-md hover:bg-evora-brown transition-colors duration-200 text-sm font-medium uppercase tracking-wide">';
    echo 'View Cart';
    echo '</a>';
    echo '</div>';
}

/**
 * Handle AJAX cart requests
 */
function handleCartAjax()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return ['success' => false, 'message' => 'Invalid request method'];
    }

    $action = $_POST['action'] ?? '';
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);

    switch ($action) {
        case 'add':
            return addToCart($product_id, $quantity);
        case 'update':
            return updateCartQuantity($product_id, $quantity);
        case 'remove':
            return removeFromCart($product_id);
        case 'clear':
            return clearCart();
        case 'get_count':
            return ['success' => true, 'count' => getCartItemCount()];
        case 'get_total':
            return ['success' => true, 'total' => getCartTotal()];
        default:
            return ['success' => false, 'message' => 'Invalid action'];
    }
}
