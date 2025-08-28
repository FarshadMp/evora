<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

// Simple authentication (you can enhance this later)
$admin_password = "admin123"; // Change this to a secure password

if ($_POST['action'] == 'login' && $_POST['password'] == $admin_password) {
    $_SESSION['admin_logged_in'] = true;
}

if ($_POST['action'] == 'logout') {
    session_destroy();
    header('Location: product-manager.php');
    exit;
}

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    // Show login form
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ÉVORA Admin - Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Manrope', sans-serif;
            }
        </style>
    </head>

    <body class="bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-light text-gray-800 mb-2">ÉVORA Admin</h1>
                <p class="text-gray-600">Product Management System</p>
            </div>

            <form method="POST" class="space-y-6">
                <input type="hidden" name="action" value="login">

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                </div>

                <button type="submit"
                    class="w-full bg-[#5A3E28] text-white py-2 px-4 rounded-md hover:bg-[#4A2E18] transition-colors duration-200 uppercase text-sm tracking-wide">
                    Login
                </button>
            </form>
        </div>
    </body>

    </html>
<?php
    exit;
}

// Handle product operations
if ($_POST['action'] == 'add_product') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $original_price = !empty($_POST['original_price']) ? $_POST['original_price'] : null;
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? 'NORMAL';
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = $_POST['sort_order'] ?? 0;

    // Handle file uploads
    $image_main = '';
    $image_hover = '';
    $upload_errors = [];

    // Check if main image was uploaded
    if (!isset($_FILES['image_main'])) {
        $upload_errors[] = "No main image file uploaded";
    } elseif ($_FILES['image_main']['error'] !== 0) {
        $upload_errors[] = "Main image upload error: " . $_FILES['image_main']['error'];
    } else {
        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['image_main']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_types)) {
            $upload_errors[] = "Main image must be JPG, PNG, GIF, or WebP";
        } else {
            // Create upload directory if it doesn't exist
            $upload_dir = '../images/products/';
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $upload_errors[] = "Failed to create upload directory";
                }
            }

            // Check if directory is writable
            if (!is_writable($upload_dir)) {
                $upload_errors[] = "Upload directory is not writable";
            }

            $file_name = 'product_main_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image_main']['tmp_name'], $upload_path)) {
                $image_main = 'images/products/' . $file_name;
            } else {
                $upload_errors[] = "Failed to upload main image";
            }
        }
    }

    // Handle hover image (optional)
    if (isset($_FILES['image_hover']) && $_FILES['image_hover']['error'] == 0) {
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['image_hover']['name'], PATHINFO_EXTENSION));

        if (in_array($file_extension, $allowed_types)) {
            $file_name = 'product_hover_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['image_hover']['tmp_name'], $upload_path)) {
                $image_hover = 'images/products/' . $file_name;
            }
        }
    }

    // Debug information
    if (!empty($upload_errors)) {
        error_log("Product upload errors: " . implode(", ", $upload_errors));
    }

    // If no upload errors, insert the product
    if (empty($upload_errors) && $image_main) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, original_price, category, status, image_main, image_hover, is_bestseller, is_new_arrival, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$name, $description, $price, $original_price, $category, $status, $image_main, $image_hover, $is_bestseller, $is_new_arrival, $is_active, $sort_order]);

            if ($result) {
                $productId = $pdo->lastInsertId();

                // Handle additional images
                if (isset($_FILES['additional_images']) && is_array($_FILES['additional_images']['name'])) {
                    $additionalImageOrder = 0;
                    foreach ($_FILES['additional_images']['name'] as $index => $filename) {
                        if ($_FILES['additional_images']['error'][$index] === 0 && !empty($filename)) {
                            $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                            if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                $additional_file_name = 'product_additional_' . time() . '_' . $index . '.' . $file_extension;
                                $additional_upload_path = $upload_dir . $additional_file_name;

                                if (move_uploaded_file($_FILES['additional_images']['tmp_name'][$index], $additional_upload_path)) {
                                    $additional_image_path = 'images/products/' . $additional_file_name;

                                    // Insert additional image into database
                                    $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path, image_order) VALUES (?, ?, ?)");
                                    $stmt->execute([$productId, $additional_image_path, $additionalImageOrder]);
                                    $additionalImageOrder++;
                                }
                            }
                        }
                    }
                }

                $_SESSION['success_message'] = "Product added successfully!";
            } else {
                $_SESSION['error_message'] = "Failed to add product to database";
            }
        } catch (Exception $e) {
            error_log("Error adding product: " . $e->getMessage());
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = implode(", ", $upload_errors);
    }

    header('Location: product-manager.php');
    exit;
}

if ($_POST['action'] == 'delete_product') {
    $product_id = $_POST['product_id'];

    // Get image paths before deleting
    $stmt = $pdo->prepare("SELECT image_main, image_hover FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Delete image files
        if ($product['image_main']) {
            $file_path = '../' . $product['image_main'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        if ($product['image_hover']) {
            $file_path = '../' . $product['image_hover'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$product_id]);

    header('Location: product-manager.php');
    exit;
}

if ($_POST['action'] == 'update_product') {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $original_price = !empty($_POST['original_price']) ? $_POST['original_price'] : null;
    $category = $_POST['category'];
    $status = $_POST['status'];
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_new_arrival = isset($_POST['is_new_arrival']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = $_POST['sort_order'];

    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, original_price = ?, category = ?, status = ?, is_bestseller = ?, is_new_arrival = ?, is_active = ?, sort_order = ? WHERE id = ?");
    $stmt->execute([$name, $description, $price, $original_price, $category, $status, $is_bestseller, $is_new_arrival, $is_active, $sort_order, $product_id]);

    header('Location: product-manager.php');
    exit;
}

// Get all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY sort_order ASC, created_at DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉVORA Admin - Product Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-light text-gray-800">ÉVORA Product Manager</h1>
                    <p class="text-sm text-gray-600">Manage your products and collections</p>
                </div>
                <div class="flex space-x-4">
                    <a href="product-image-manager.php" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        Image Manager
                    </a>
                    <a href="banner-manager.php" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        Banner Manager
                    </a>
                    <form method="POST" class="inline">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <?php echo htmlspecialchars($_SESSION['success_message']); ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <?php echo htmlspecialchars($_SESSION['error_message']); ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Add New Product Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Add New Product</h2>

            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="add_product">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category" id="category" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                            <option value="">Select Category</option>
                            <option value="rings">Rings</option>
                            <option value="necklaces">Necklaces</option>
                            <option value="bracelets">Bracelets</option>
                            <option value="earrings">Earrings</option>
                            <option value="wrist-bands">Wrist Bands</option>
                            <option value="anklets">Anklets</option>
                            <option value="offers">Offers</option>
                            <option value="combos">Combos</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rs.)</label>
                        <input type="number" name="price" id="price" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>

                    <div>
                        <label for="original_price" class="block text-sm font-medium text-gray-700 mb-1">Original Price (Rs.)</label>
                        <input type="number" name="original_price" id="original_price" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                            <option value="NORMAL">Normal</option>
                            <option value="NEW">NEW</option>
                            <option value="SOLD OUT">SOLD OUT</option>
                            <option value="REFILL">REFILL</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="image_main" class="block text-sm font-medium text-gray-700 mb-1">Main Image</label>
                        <input type="file" name="image_main" id="image_main" accept="image/*" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>

                    <div>
                        <label for="image_hover" class="block text-sm font-medium text-gray-700 mb-1">Hover Image (Optional)</label>
                        <input type="file" name="image_hover" id="image_hover" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>
                </div>

                <!-- Additional Images Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Additional Images (Optional)</label>
                    <div id="additional-images-container" class="space-y-3">
                        <div class="additional-image-input flex items-center space-x-3">
                            <input type="file" name="additional_images[]" accept="image/*"
                                class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                            <button type="button" onclick="removeImageInput(this)" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" onclick="addImageInput()" class="mt-2 text-sm text-[#5A3E28] hover:text-[#4A2E18] font-medium">
                        + Add Another Image
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_bestseller" id="is_bestseller"
                            class="h-4 w-4 text-[#5A3E28] focus:ring-[#5A3E28] border-gray-300 rounded">
                        <label for="is_bestseller" class="ml-2 block text-sm text-gray-700">Best Seller</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_new_arrival" id="is_new_arrival"
                            class="h-4 w-4 text-[#5A3E28] focus:ring-[#5A3E28] border-gray-300 rounded">
                        <label for="is_new_arrival" class="ml-2 block text-sm text-gray-700">New Arrival</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" checked
                            class="h-4 w-4 text-[#5A3E28] focus:ring-[#5A3E28] border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>
                </div>

                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="0" min="0"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                </div>

                <button type="submit"
                    class="bg-[#5A3E28] text-white py-2 px-4 rounded-md hover:bg-[#4A2E18] transition-colors duration-200 uppercase text-sm tracking-wide">
                    Add Product
                </button>
            </form>
        </div>

        <!-- Existing Products -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-medium text-gray-800">Existing Products</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="../<?php echo htmlspecialchars($product['image_main']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                                        class="h-16 w-16 object-cover rounded">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($product['description']); ?></div>
                                    <div class="flex space-x-2 mt-1">
                                        <?php if ($product['is_bestseller']): ?>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Best Seller</span>
                                        <?php endif; ?>
                                        <?php if ($product['is_new_arrival']): ?>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">New Arrival</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo ucfirst(htmlspecialchars($product['category'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusColors = [
                                        'NEW' => 'bg-yellow-100 text-yellow-800',
                                        'SOLD OUT' => 'bg-red-100 text-red-800',
                                        'REFILL' => 'bg-green-100 text-green-800',
                                        'NORMAL' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusColor = $statusColors[$product['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusColor; ?>">
                                        <?php echo $product['status']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rs. <?php echo number_format($product['price'], 2); ?>
                                    <?php if ($product['original_price']): ?>
                                        <div class="text-xs text-gray-500 line-through">Rs. <?php echo number_format($product['original_price'], 2); ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)"
                                        class="text-[#5A3E28] hover:text-[#4A2E18] mr-3">Edit</button>
                                    <a href="product-image-manager.php?product_id=<?php echo $product['id']; ?>"
                                        class="text-blue-600 hover:text-blue-800 mr-3">Images</a>
                                    <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        <input type="hidden" name="action" value="delete_product">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-medium text-gray-800">Edit Product</h3>
                </div>

                <form method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="action" value="update_product">
                    <input type="hidden" name="product_id" id="edit_product_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <input type="text" name="name" id="edit_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                        </div>

                        <div>
                            <label for="edit_category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category" id="edit_category" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                                <option value="rings">Rings</option>
                                <option value="necklaces">Necklaces</option>
                                <option value="bracelets">Bracelets</option>
                                <option value="earrings">Earrings</option>
                                <option value="wrist-bands">Wrist Bands</option>
                                <option value="anklets">Anklets</option>
                                <option value="offers">Offers</option>
                                <option value="combos">Combos</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="edit_price" class="block text-sm font-medium text-gray-700 mb-1">Price (Rs.)</label>
                            <input type="number" name="price" id="edit_price" step="0.01" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                        </div>

                        <div>
                            <label for="edit_original_price" class="block text-sm font-medium text-gray-700 mb-1">Original Price (Rs.)</label>
                            <input type="number" name="original_price" id="edit_original_price" step="0.01"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                        </div>

                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="edit_status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                                <option value="NORMAL">Normal</option>
                                <option value="NEW">NEW</option>
                                <option value="SOLD OUT">SOLD OUT</option>
                                <option value="REFILL">REFILL</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_bestseller" id="edit_is_bestseller"
                                class="h-4 w-4 text-[#5A3E28] focus:ring-[#5A3E28] border-gray-300 rounded">
                            <label for="edit_is_bestseller" class="ml-2 block text-sm text-gray-700">Best Seller</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_new_arrival" id="edit_is_new_arrival"
                                class="h-4 w-4 text-[#5A3E28] focus:ring-[#5A3E28] border-gray-300 rounded">
                            <label for="edit_is_new_arrival" class="ml-2 block text-sm text-gray-700">New Arrival</label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="edit_is_active"
                                class="h-4 w-4 text-[#5A3E28] focus:ring-[#5A3E28] border-gray-300 rounded">
                            <label for="edit_is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                        </div>
                    </div>

                    <div>
                        <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_sort_order" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#5A3E28] text-white rounded-md hover:bg-[#4A2E18] transition-colors duration-200">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editProduct(product) {
            document.getElementById('edit_product_id').value = product.id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_original_price').value = product.original_price || '';
            document.getElementById('edit_category').value = product.category;
            document.getElementById('edit_status').value = product.status;
            document.getElementById('edit_sort_order').value = product.sort_order;
            document.getElementById('edit_is_bestseller').checked = product.is_bestseller == 1;
            document.getElementById('edit_is_new_arrival').checked = product.is_new_arrival == 1;
            document.getElementById('edit_is_active').checked = product.is_active == 1;

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Additional image management functions
        function addImageInput() {
            const container = document.getElementById('additional-images-container');
            const newInput = document.createElement('div');
            newInput.className = 'additional-image-input flex items-center space-x-3';
            newInput.innerHTML = `
                <input type="file" name="additional_images[]" accept="image/*"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                <button type="button" onclick="removeImageInput(this)" class="text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            container.appendChild(newInput);
        }

        function removeImageInput(button) {
            const container = document.getElementById('additional-images-container');
            const inputs = container.querySelectorAll('.additional-image-input');

            // Don't remove if it's the last input
            if (inputs.length > 1) {
                button.closest('.additional-image-input').remove();
            }
        }
    </script>
</body>

</html>