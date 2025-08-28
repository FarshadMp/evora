<?php
require_once 'includes/cart-functions.php';
require_once 'includes/product-functions.php';

// Set JSON content type
header('Content-Type: application/json');

// Handle AJAX request
$response = handleCartAjax();

// Return JSON response
echo json_encode($response);
exit;
