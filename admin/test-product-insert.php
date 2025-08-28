<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

// Simple authentication
$admin_password = "admin123";

if ($_POST['action'] == 'login' && $_POST['password'] == $admin_password) {
    $_SESSION['admin_logged_in'] = true;
}

if ($_POST['action'] == 'logout') {
    session_destroy();
    header('Location: test-product-insert.php');
    exit;
}

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test Product Insert - Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-light text-gray-800 mb-8 text-center">Test Product Insert Login</h1>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="action" value="login">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md">
                    Login
                </button>
            </form>
        </div>
    </body>

    </html>
<?php
    exit;
}

$test_result = '';
if ($_POST['action'] == 'test_insert') {
    $test_result = "<h3 class='text-lg font-medium mb-4'>Product Insert Test Results:</h3>";
    $test_result .= "<div class='bg-gray-100 p-4 rounded'>";

    try {
        // Test data
        $name = 'Test Product ' . time();
        $description = 'This is a test product';
        $price = 99.99;
        $original_price = null; // This was causing the issue
        $category = 'test';
        $status = 'NORMAL';
        $image_main = 'images/products/test.jpg';
        $image_hover = null;
        $is_bestseller = 0;
        $is_new_arrival = 0;
        $is_active = 1;
        $sort_order = 999;

        $test_result .= "<p><strong>Test Data:</strong></p>";
        $test_result .= "<ul class='ml-4'>";
        $test_result .= "<li>Name: " . $name . "</li>";
        $test_result .= "<li>Price: " . $price . "</li>";
        $test_result .= "<li>Original Price: " . (is_null($original_price) ? 'NULL' : $original_price) . "</li>";
        $test_result .= "<li>Image Hover: " . (is_null($image_hover) ? 'NULL' : $image_hover) . "</li>";
        $test_result .= "</ul>";

        // Test the insert
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, original_price, category, status, image_main, image_hover, is_bestseller, is_new_arrival, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$name, $description, $price, $original_price, $category, $status, $image_main, $image_hover, $is_bestseller, $is_new_arrival, $is_active, $sort_order]);

        if ($result) {
            $productId = $pdo->lastInsertId();
            $test_result .= "<p class='text-green-600'>✅ Product inserted successfully! ID: " . $productId . "</p>";

            // Clean up - delete the test product
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$productId]);
            $test_result .= "<p class='text-blue-600'>🗑️ Test product deleted</p>";
        } else {
            $test_result .= "<p class='text-red-600'>❌ Failed to insert product</p>";
        }
    } catch (Exception $e) {
        $test_result .= "<p class='text-red-600'>❌ Error: " . $e->getMessage() . "</p>";
    }

    $test_result .= "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Product Insert - ÉVORA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-light text-gray-800">Test Product Insert</h1>
                    <p class="text-sm text-gray-600">Test database insertion with NULL values</p>
                </div>
                <div class="flex space-x-4">
                    <a href="product-manager.php" class="text-blue-600 hover:text-blue-800">Product Manager</a>
                    <a href="fix-permissions.php" class="text-green-600 hover:text-green-800">Fix Permissions</a>
                    <form method="POST" class="inline">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="text-gray-600 hover:text-gray-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Test Form -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Test Product Insertion</h2>
            <p class="text-gray-600 mb-4">This will test inserting a product with NULL original_price and image_hover values.</p>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="test_insert">

                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700">
                    Test Product Insert
                </button>
            </form>
        </div>

        <!-- Test Results -->
        <?php if ($test_result): ?>
            <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
                <?php echo $test_result; ?>
            </div>
        <?php endif; ?>

        <!-- Database Schema Info -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Database Schema Information</h3>
            <div class="bg-gray-100 p-4 rounded">
                <?php
                try {
                    $stmt = $pdo->query("DESCRIBE products");
                    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    echo "<table class='w-full text-sm'>";
                    echo "<tr class='border-b'><th class='text-left py-2'>Column</th><th class='text-left py-2'>Type</th><th class='text-left py-2'>Null</th><th class='text-left py-2'>Default</th></tr>";

                    foreach ($columns as $column) {
                        $nullStatus = $column['Null'] === 'YES' ? '✅ NULL' : '❌ NOT NULL';
                        echo "<tr class='border-b'>";
                        echo "<td class='py-1'>" . $column['Field'] . "</td>";
                        echo "<td class='py-1'>" . $column['Type'] . "</td>";
                        echo "<td class='py-1'>" . $nullStatus . "</td>";
                        echo "<td class='py-1'>" . ($column['Default'] ?? 'NULL') . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } catch (Exception $e) {
                    echo "<p class='text-red-600'>Error: " . $e->getMessage() . "</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>