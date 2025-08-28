<?php
// Simple test page for banner system
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banner System Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8">Banner System Test</h1>

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Database Test</h2>
            <?php
            try {
                require_once 'config/database.php';
                echo "<p class='text-green-600'>✓ Database connection successful</p>";

                // Test banner count
                $stmt = $pdo->query("SELECT COUNT(*) as count FROM banners");
                $result = $stmt->fetch();
                echo "<p>Total banners: " . $result['count'] . "</p>";

                $stmt = $pdo->query("SELECT COUNT(*) as count FROM banners WHERE is_active = 1");
                $result = $stmt->fetch();
                echo "<p>Active banners: " . $result['count'] . "</p>";
            } catch (Exception $e) {
                echo "<p class='text-red-600'>✗ Database error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Banner Display Test</h2>
            <div class="border-2 border-dashed border-gray-300 p-4">
                <?php
                try {
                    require_once 'includes/banner-functions.php';
                    displayBannerCarousel();
                    echo "<p class='text-green-600 mt-4'>✓ Banner display function executed</p>";
                } catch (Exception $e) {
                    echo "<p class='text-red-600'>✗ Banner display error: " . $e->getMessage() . "</p>";
                }
                ?>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
            <div class="space-y-2">
                <a href="admin/banner-manager.php" class="block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Admin Panel</a>
                <a href="index.php" class="block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">View Homepage</a>
                <a href="debug-banners.php" class="block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600">Debug System</a>
            </div>
        </div>
    </div>
</body>

</html>