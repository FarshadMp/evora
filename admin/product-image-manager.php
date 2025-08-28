<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/product-functions.php';

// Simple authentication (you can enhance this later)
$admin_password = "admin123"; // Change this to a secure password

if ($_POST['action'] == 'login' && $_POST['password'] == $admin_password) {
    $_SESSION['admin_logged_in'] = true;
}

if ($_POST['action'] == 'logout') {
    session_destroy();
    header('Location: product-image-manager.php');
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
        <title>ÉVORA Admin - Product Image Manager</title>
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
                <p class="text-gray-600">Product Image Manager</p>
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

// Get all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
$products = $stmt->fetchAll();

// Get selected product and its images
$selectedProduct = null;
$productImages = [];
if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $selectedProduct = $stmt->fetch();

    if ($selectedProduct) {
        $productImages = getAllProductImages($productId);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉVORA Admin - Product Image Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }

        .image-preview {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-light text-gray-800">ÉVORA Product Image Manager</h1>
                    <p class="text-sm text-gray-600">Manage product images and galleries</p>
                </div>
                <div class="flex space-x-4">
                    <a href="product-manager.php" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        Product Manager
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
        <!-- Product Selection -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Select Product</h2>

            <form method="GET" class="flex gap-4">
                <select name="product_id" onchange="this.form.submit()" class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    <option value="">Choose a product...</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['id']; ?>" <?php echo (isset($_GET['product_id']) && $_GET['product_id'] == $product['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($product['name']); ?> (<?php echo ucfirst($product['category']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if ($selectedProduct): ?>
            <!-- Product Images Management -->
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="px-6 py-4 border-b">
                    <h2 class="text-lg font-medium text-gray-800">Images for: <?php echo htmlspecialchars($selectedProduct['name']); ?></h2>
                    <p class="text-sm text-gray-600">Manage the image gallery for this product</p>
                </div>

                <div class="p-6">
                    <!-- Current Images -->
                    <div class="mb-8">
                        <h3 class="text-md font-medium text-gray-800 mb-4">Current Images</h3>

                        <?php if (empty($productImages)): ?>
                            <p class="text-gray-500">No images found for this product.</p>
                        <?php else: ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="images-container">
                                <?php foreach ($productImages as $index => $image): ?>
                                    <div class="border rounded-lg p-4" data-image-id="<?php echo $image['id']; ?>">
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-sm font-medium text-gray-700">
                                                <?php
                                                switch ($image['type']) {
                                                    case 'main':
                                                        echo 'Main Image';
                                                        break;
                                                    case 'hover':
                                                        echo 'Hover Image';
                                                        break;
                                                    default:
                                                        echo 'Additional Image #' . ($index + 1);
                                                }
                                                ?>
                                            </span>
                                            <?php if ($image['type'] === 'additional'): ?>
                                                <button onclick="deleteImage(<?php echo $image['id']; ?>)" class="text-red-600 hover:text-red-800">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                        <img src="../<?php echo htmlspecialchars($image['image_path']); ?>"
                                            alt="Product Image"
                                            class="image-preview w-full h-24 object-cover rounded">
                                        <div class="mt-2 text-xs text-gray-500">
                                            Order: <?php echo $image['image_order']; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Add New Image -->
                    <div class="border-t pt-6">
                        <h3 class="text-md font-medium text-gray-800 mb-4">Add New Image</h3>

                        <form id="add-image-form" class="space-y-4">
                            <input type="hidden" name="product_id" value="<?php echo $selectedProduct['id']; ?>">

                            <div>
                                <label for="new_image" class="block text-sm font-medium text-gray-700 mb-2">Select Image</label>
                                <input type="file" name="image" id="new_image" accept="image/*" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                            </div>

                            <button type="submit"
                                class="bg-[#5A3E28] text-white py-2 px-4 rounded-md hover:bg-[#4A2E18] transition-colors duration-200 uppercase text-sm tracking-wide">
                                Add Image
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Add new image
        document.getElementById('add-image-form')?.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('action', 'add_image');
            formData.append('product_id', <?php echo $selectedProduct ? $selectedProduct['id'] : 'null'; ?>);
            formData.append('image', document.getElementById('new_image').files[0]);

            fetch('product-images-api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Image added successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding image. Please try again.');
                });
        });

        // Delete image
        function deleteImage(imageId) {
            if (!confirm('Are you sure you want to delete this image?')) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'delete_image');
            formData.append('image_id', imageId);

            fetch('product-images-api.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Image deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting image. Please try again.');
                });
        }
    </script>
</body>

</html>
