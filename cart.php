<?php
require_once 'includes/cart-functions.php';
require_once 'includes/product-functions.php';

// Handle form submissions
if ($_POST['action'] == 'update_cart') {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        updateCartQuantity($product_id, (int)$quantity);
    }
    $success_message = "Cart updated successfully!";
}

if ($_POST['action'] == 'clear_cart') {
    clearCart();
    $success_message = "Cart cleared successfully!";
}

// Get cart data
$cartItems = getCartItems();
$cartTotal = getCartTotal();
$itemCount = getCartItemCount();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ÉVORA Luxury Jewelry</title>
    <meta name="description" content="View and manage your shopping cart at ÉVORA Luxury Jewelry.">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon.svg">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.svg">
    <meta name="theme-color" content="#5A3E28">
</head>

<body class="bg-evora-beige">

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Breadcrumb Navigation -->
    <section class="py-4 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <div class="space-x-2 text-xs text-primary">
            <a href="index.php" class="text-primary hover:text-evora-brown transition-colors duration-200 uppercase">HOME</a>
            <span class="text-primary">/</span>
            <span class="text-primary font-medium uppercase">Shopping Cart</span>
        </div>
    </section>

    <!-- Cart Section -->
    <section class="py-8 sm:py-12 md:py-16 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <div class="">
            <!-- Page Header -->
            <div class="text-left mb-8">
                <h1 class="text-3xl sm:text-4xl md:text-4xl lg:text-4xl font-light text-primary mb-4">Shopping Cart</h1>
                <p class="text-sm text-primary font-light">
                    Review your items and proceed to checkout
                </p>
            </div>

            <!-- Messages -->
            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if (isCartEmpty()): ?>
                <!-- Empty Cart -->
                <div class="text-center py-12">
                    <div class="mb-6">
                        <svg class="w-24 h-24 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-light text-primary mb-4">Your cart is empty</h2>
                    <p class="text-gray-600 mb-8">Looks like you haven't added any items to your cart yet.</p>
                    <a href="index.php" class="inline-block bg-primary text-white px-8 py-3 font-medium hover:bg-evora-brown transition-colors duration-200 uppercase text-sm tracking-wide">
                        Continue Shopping
                    </a>
                </div>
            <?php else: ?>
                <!-- Cart Items -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
                    <!-- Cart Items List -->
                    <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                        <div class="bg-white shadow-sm border">
                            <div class="p-4 sm:p-6 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-primary">Cart Items (<?php echo $itemCount; ?>)</h2>
                            </div>

                            <form method="POST" id="cart-form">
                                <input type="hidden" name="action" value="update_cart">

                                <div class="divide-y divide-gray-200">
                                    <?php foreach ($cartItems as $product_id => $item): ?>
                                        <div class="p-4 sm:p-6">
                                            <!-- Mobile Layout -->
                                            <div class="block sm:hidden">
                                                <div class="flex items-start space-x-3 mb-4">
                                                    <!-- Product Image -->
                                                    <div class="flex-shrink-0">
                                                        <img src="<?php echo htmlspecialchars($item['image']); ?>"
                                                            alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                            class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded">
                                                    </div>

                                                    <!-- Product Details -->
                                                    <div class="flex-1 min-w-0">
                                                        <h3 class="text-sm font-medium text-primary">
                                                            <a href="product-details.php?id=<?php echo $item['id']; ?>"
                                                                class="hover:text-evora-brown transition-colors duration-200">
                                                                <?php echo htmlspecialchars($item['name']); ?>
                                                            </a>
                                                        </h3>
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            Price: Rs. <?php echo number_format($item['price'], 2); ?>
                                                        </p>
                                                        <?php if ($item['status'] !== 'NORMAL'): ?>
                                                            <p class="text-xs text-red-600 mt-1">
                                                                Status: <?php echo $item['status']; ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Remove Button -->
                                                    <button type="button"
                                                        onclick="removeItem(<?php echo $product_id; ?>)"
                                                        class="text-gray-400 hover:text-red-500 transition-colors duration-200 flex-shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Quantity Controls - Mobile -->
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center space-x-2">
                                                        <button type="button"
                                                            onclick="updateQuantity(<?php echo $product_id; ?>, -1)"
                                                            class="w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-50 transition-colors duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                            </svg>
                                                        </button>

                                                        <input type="number"
                                                            name="quantities[<?php echo $product_id; ?>]"
                                                            value="<?php echo $item['quantity']; ?>"
                                                            min="1"
                                                            class="w-16 text-center border border-gray-300 rounded py-1 text-sm"
                                                            onchange="updateQuantity(<?php echo $product_id; ?>, 0, this.value)">

                                                        <button type="button"
                                                            onclick="updateQuantity(<?php echo $product_id; ?>, 1)"
                                                            class="w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-50 transition-colors duration-200">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <!-- Item Total - Mobile -->
                                                    <div class="text-right">
                                                        <p class="text-sm font-semibold text-primary">
                                                            Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Desktop Layout -->
                                            <div class="hidden sm:flex items-center space-x-4">
                                                <!-- Product Image -->
                                                <div class="flex-shrink-0">
                                                    <img src="<?php echo htmlspecialchars($item['image']); ?>"
                                                        alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                        class="w-20 h-20 object-cover rounded">
                                                </div>

                                                <!-- Product Details -->
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-sm font-medium text-primary">
                                                        <a href="product-details.php?id=<?php echo $item['id']; ?>"
                                                            class="hover:text-evora-brown transition-colors duration-200">
                                                            <?php echo htmlspecialchars($item['name']); ?>
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-500 mt-1">
                                                        Price: Rs. <?php echo number_format($item['price'], 2); ?>
                                                    </p>
                                                    <?php if ($item['status'] !== 'NORMAL'): ?>
                                                        <p class="text-xs text-red-600 mt-1">
                                                            Status: <?php echo $item['status']; ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Quantity Controls -->
                                                <div class="flex items-center space-x-2">
                                                    <button type="button"
                                                        onclick="updateQuantity(<?php echo $product_id; ?>, -1)"
                                                        class="w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-50 transition-colors duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>

                                                    <input type="number"
                                                        name="quantities[<?php echo $product_id; ?>]"
                                                        value="<?php echo $item['quantity']; ?>"
                                                        min="1"
                                                        class="w-16 text-center border border-gray-300 rounded py-1 text-sm"
                                                        onchange="updateQuantity(<?php echo $product_id; ?>, 0, this.value)">

                                                    <button type="button"
                                                        onclick="updateQuantity(<?php echo $product_id; ?>, 1)"
                                                        class="w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center hover:bg-gray-50 transition-colors duration-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Item Total -->
                                                <div class="text-right">
                                                    <p class="text-sm font-semibold text-primary">
                                                        Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                                    </p>
                                                </div>

                                                <!-- Remove Button -->
                                                <button type="button"
                                                    onclick="removeItem(<?php echo $product_id; ?>)"
                                                    class="text-gray-400 hover:text-red-500 transition-colors duration-200">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Cart Actions -->
                                <div class="p-4 sm:p-6 border-t border-gray-200">
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0">
                                        <button type="submit"
                                            class="w-full sm:w-auto bg-gray-100 text-primary px-4 py-2 rounded-md hover:bg-gray-200 transition-colors duration-200 text-sm font-medium">
                                            Update Cart
                                        </button>

                                        <form method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Are you sure you want to clear your cart?')">
                                            <input type="hidden" name="action" value="clear_cart">
                                            <button type="submit"
                                                class="w-full sm:w-auto text-red-600 hover:text-red-800 transition-colors duration-200 text-sm font-medium">
                                                Clear Cart
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white shadow-sm border p-4 sm:p-6 sticky top-4">
                            <h2 class="text-lg font-medium text-primary mb-4">Order Summary</h2>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal (<?php echo $itemCount; ?> items):</span>
                                    <span class="font-medium text-primary">Rs. <?php echo number_format($cartTotal, 2); ?></span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Shipping:</span>
                                    <span class="font-medium text-primary">Free</span>
                                </div>

                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tax:</span>
                                    <span class="font-medium text-primary">Calculated at checkout</span>
                                </div>

                                <div class="border-t pt-3">
                                    <div class="flex justify-between text-lg font-semibold">
                                        <span class="text-primary">Total:</span>
                                        <span class="text-primary">Rs. <?php echo number_format($cartTotal, 2); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <button onclick="proceedToCheckout()"
                                    class="w-full bg-primary text-white py-3 px-6 font-medium hover:bg-evora-brown transition-colors duration-200 uppercase text-sm tracking-wide flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                    </svg>
                                    <span>Checkout via WhatsApp</span>
                                </button>

                                <a href="index.php"
                                    class="block w-full bg-gray-100 text-primary py-3 px-6 font-medium hover:bg-gray-200 transition-colors duration-200 text-center uppercase text-sm tracking-wide">
                                    Continue Shopping
                                </a>

                                <!-- WhatsApp Checkout Info -->
                                <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-start space-x-2">
                                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                        </svg>
                                        <div class="text-xs text-green-800">
                                            <p class="font-medium mb-1">WhatsApp Checkout</p>
                                            <p>Fill in your contact information above and click the button to send your complete order details to our WhatsApp. We'll help you complete your purchase!</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Shipping Info -->
                            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                <h3 class="text-sm font-medium text-primary mb-2">Shipping Information</h3>
                                <ul class="text-xs text-gray-600 space-y-1">
                                    <li>• Free shipping on orders over Rs. 500</li>
                                    <li>• Standard delivery: 3-5 business days</li>
                                    <li>• Express delivery available</li>
                                    <li>• Secure packaging guaranteed</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Contact Information Form -->
                        <div class="bg-white shadow-sm border mt-4 sm:mt-6" id="contact-form-section">
                            <div class="p-4 sm:p-6 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-primary">Contact Information</h2>
                                <p class="text-sm text-gray-600 mt-1">Please provide your details for order processing</p>
                            </div>

                            <div class="p-4 sm:p-6">
                                <form id="contact-form" class="space-y-4">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                        <input type="text" id="first_name" name="first_name" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                    </div>

                                    <div>
                                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country / Region *</label>
                                        <select id="country" name="country" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                            <option value="">Select Country</option>
                                            <option value="India" selected>India</option>
                                            <option value="United States">United States</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="France">France</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="street_address" class="block text-sm font-medium text-gray-700 mb-1">Street Address *</label>
                                        <textarea id="street_address" name="street_address" rows="3" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                            placeholder="House/Flat number, Street name, Landmark"></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Town / City *</label>
                                            <input type="text" id="city" name="city" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                        </div>

                                        <div>
                                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                                            <select id="state" name="state" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                                <option value="">Select State</option>
                                                <option value="Kerala">Kerala</option>
                                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                <option value="Assam">Assam</option>
                                                <option value="Bihar">Bihar</option>
                                                <option value="Chhattisgarh">Chhattisgarh</option>
                                                <option value="Goa">Goa</option>
                                                <option value="Gujarat">Gujarat</option>
                                                <option value="Haryana">Haryana</option>
                                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                <option value="Jharkhand">Jharkhand</option>
                                                <option value="Karnataka">Karnataka</option>
                                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                <option value="Maharashtra">Maharashtra</option>
                                                <option value="Manipur">Manipur</option>
                                                <option value="Meghalaya">Meghalaya</option>
                                                <option value="Mizoram">Mizoram</option>
                                                <option value="Nagaland">Nagaland</option>
                                                <option value="Odisha">Odisha</option>
                                                <option value="Punjab">Punjab</option>
                                                <option value="Rajasthan">Rajasthan</option>
                                                <option value="Sikkim">Sikkim</option>
                                                <option value="Tamil Nadu">Tamil Nadu</option>
                                                <option value="Telangana">Telangana</option>
                                                <option value="Tripura">Tripura</option>
                                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                <option value="Uttarakhand">Uttarakhand</option>
                                                <option value="West Bengal">West Bengal</option>
                                                <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                                <option value="Chandigarh">Chandigarh</option>
                                                <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                                                <option value="Delhi">Delhi</option>
                                                <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                                <option value="Ladakh">Ladakh</option>
                                                <option value="Lakshadweep">Lakshadweep</option>
                                                <option value="Puducherry">Puducherry</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="pin_code" class="block text-sm font-medium text-gray-700 mb-1">PIN Code *</label>
                                            <input type="text" id="pin_code" name="pin_code" required
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                                placeholder="123456">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                                        <input type="tel" id="phone" name="phone" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                                            placeholder="+91 98765 43210">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Custom JavaScript -->
    <script src="assets/js/script.js"></script>

    <!-- Cart JavaScript -->
    <script>
        function updateQuantity(productId, change, newValue = null) {
            let quantity;
            if (newValue !== null) {
                quantity = parseInt(newValue);
            } else {
                const input = document.querySelector(`input[name="quantities[${productId}]"]`);
                quantity = parseInt(input.value) + change;
            }

            if (quantity < 1) quantity = 1;

            // Update the input value
            const input = document.querySelector(`input[name="quantities[${productId}]"]`);
            input.value = quantity;

            // Send AJAX request to update cart
            fetch('cart-api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=update&product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count in header if it exists
                        updateCartCount();
                    } else {
                        showNotification('Error updating cart: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error updating cart. Please try again.', 'error');
                });
        }

        function removeItem(productId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            fetch('cart-api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=remove&product_id=${productId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to update the cart display
                        location.reload();
                    } else {
                        showNotification('Error removing item: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error removing item. Please try again.', 'error');
                });
        }

        function updateCartCount() {
            fetch('cart-api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=get_count'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart count in header if it exists
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            if (data.count > 0) {
                                cartCountElement.textContent = data.count > 99 ? '99+' : data.count;
                                cartCountElement.style.display = 'flex';
                            } else {
                                cartCountElement.style.display = 'none';
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating cart count:', error);
                });
        }

        function proceedToCheckout() {
            // Get cart data from PHP
            const cartItems = <?php echo json_encode($cartItems); ?>;
            const cartTotal = <?php echo $cartTotal; ?>;
            const itemCount = <?php echo $itemCount; ?>;

            if (itemCount === 0) {
                showNotification('Your cart is empty. Please add items before checkout.', 'error');
                return;
            }

            // Get contact information from form
            const contactForm = document.getElementById('contact-form');
            if (!contactForm) {
                showNotification('Contact form not found. Please refresh the page and try again.', 'error');
                return;
            }

            // Validate required fields
            const requiredFields = ['first_name', 'country', 'street_address', 'city', 'state', 'pin_code', 'phone'];
            const missingFields = [];
            let firstMissingField = null;

            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input || !input.value.trim()) {
                    missingFields.push(field.replace('_', ' '));
                    if (!firstMissingField) {
                        firstMissingField = input;
                    }
                }
            });

            if (missingFields.length > 0) {
                // Scroll to the contact form section
                const contactFormSection = document.getElementById('contact-form-section');
                if (contactFormSection) {
                    contactFormSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Add a subtle highlight effect to the form
                    contactFormSection.classList.add('form-section-highlight');

                    // Remove the highlight after 2 seconds
                    setTimeout(() => {
                        contactFormSection.classList.remove('form-section-highlight');
                    }, 2000);
                }

                // Focus on the first missing field if available
                if (firstMissingField) {
                    setTimeout(() => {
                        firstMissingField.focus();
                        firstMissingField.classList.add('form-field-error');

                        // Remove the error styling after 3 seconds
                        setTimeout(() => {
                            firstMissingField.classList.remove('form-field-error');
                        }, 3000);
                    }, 500);
                }

                // Show a more user-friendly notification
                showNotification('Please fill in all required fields: ' + missingFields.join(', '), 'error');
                return;
            }

            // Collect contact information
            const contactInfo = {
                first_name: document.getElementById('first_name').value.trim(),
                country: document.getElementById('country').value,
                street_address: document.getElementById('street_address').value.trim(),
                city: document.getElementById('city').value.trim(),
                state: document.getElementById('state').value.trim(),
                pin_code: document.getElementById('pin_code').value.trim(),
                phone: document.getElementById('phone').value.trim()
            };

            // Format the message for WhatsApp
            let message = `🛍️ *ÉVORA Luxury Jewelry - New Order*\n\n`;
            message += `*Order Summary:*\n`;
            message += `Total Items: ${itemCount}\n`;
            message += `Total Amount: Rs. ${cartTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})}\n\n`;
            message += `*Items in Cart:*\n`;

            let itemNumber = 1;
            for (const [productId, item] of Object.entries(cartItems)) {
                // Get current page URL to construct product link
                const currentUrl = window.location.origin + window.location.pathname.replace('cart.php', '');
                const productLink = `${currentUrl}product-details.php?id=${item.id}`;

                message += `${itemNumber}. *${item.name}*\n`;
                message += `   Quantity: ${item.quantity}\n`;
                message += `   Price: Rs. ${item.price.toLocaleString('en-IN', {minimumFractionDigits: 2})}\n`;
                message += `   Subtotal: Rs. ${(item.price * item.quantity).toLocaleString('en-IN', {minimumFractionDigits: 2})}\n`;
                message += `   Link: ${productLink}\n`;
                if (item.status !== 'NORMAL') {
                    message += `   Status: ${item.status}\n`;
                }
                message += `\n`;
                itemNumber++;
            }

            message += `*Customer Information:*\n`;
            message += `Name: ${contactInfo.first_name}\n`;
            message += `Phone: ${contactInfo.phone}\n`;
            message += `\n`;
            message += `*Shipping Address:*\n`;
            message += `${contactInfo.street_address}\n`;
            message += `${contactInfo.city}, ${contactInfo.state} ${contactInfo.pin_code}\n`;
            message += `${contactInfo.country}\n\n`;
            message += `*Payment Method:*\n`;
            message += `Please specify your preferred payment method.\n\n`;
            message += `Thank you for choosing ÉVORA Luxury Jewelry! 💎`;

            // Encode the message for URL
            const encodedMessage = encodeURIComponent(message);

            // WhatsApp number
            const whatsappNumber = '919544061145';

            // Create WhatsApp URL
            const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodedMessage}`;

            // Open WhatsApp in new tab
            window.open(whatsappUrl, '_blank');
        }

        // Notification system
        function showNotification(message, type = 'info') {
            // Remove any existing notifications
            const existingNotification = document.querySelector('.notification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

            // Set background color based on type
            if (type === 'error') {
                notification.className += ' bg-red-500 text-white';
            } else if (type === 'success') {
                notification.className += ' bg-green-500 text-white';
            } else {
                notification.className += ' bg-blue-500 text-white';
            }

            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

            // Add to page
            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Update cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            
            // Initialize state dropdown functionality
            initializeStateDropdown();
        });
        
        // Initialize state dropdown with search functionality
        function initializeStateDropdown() {
            const stateSelect = document.getElementById('state');
            if (!stateSelect) return;
            
            // Add search functionality
            stateSelect.addEventListener('keydown', function(e) {
                if (e.key.length === 1) {
                    // User typed a character, search for matching options
                    const searchChar = e.key.toLowerCase();
                    const options = Array.from(this.options);
                    
                    // Find the first option that starts with the typed character
                    const matchingOption = options.find(option => 
                        option.text.toLowerCase().startsWith(searchChar) && option.value !== ''
                    );
                    
                    if (matchingOption) {
                        matchingOption.selected = true;
                        e.preventDefault();
                    }
                }
            });
            
            // Add focus styles
            stateSelect.addEventListener('focus', function() {
                this.classList.add('ring-2', 'ring-primary', 'border-transparent');
            });
            
            stateSelect.addEventListener('blur', function() {
                this.classList.remove('ring-2', 'ring-primary', 'border-transparent');
            });
        }
    </script>
</body>

</html>