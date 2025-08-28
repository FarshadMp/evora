<?php
// Simple banner test without database
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Banner Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-8">Simple Banner Test</h1>

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Test 1: Static Banner</h2>
            <div class="border-2 border-dashed border-gray-300 p-4">
                <section class="banner-section">
                    <img src="images/banners/banner.jpg" alt="ÉVORA Luxury Jewelry Banner"
                        class="w-full h-64 object-cover object-center">
                </section>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Test 2: Database Connection</h2>
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

                // Show banner details
                $stmt = $pdo->query("SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order ASC");
                $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($banners)) {
                    echo "<h3>Active Banners:</h3>";
                    foreach ($banners as $banner) {
                        echo "<div class='border p-4 mb-4'>";
                        echo "<p><strong>Title:</strong> " . htmlspecialchars($banner['title']) . "</p>";
                        echo "<p><strong>Image:</strong> " . htmlspecialchars($banner['image_path']) . "</p>";
                        echo "<p><strong>Active:</strong> " . ($banner['is_active'] ? 'Yes' : 'No') . "</p>";
                        echo "<p><strong>Sort Order:</strong> " . $banner['sort_order'] . "</p>";

                        // Check if image exists
                        $imagePath = $banner['image_path'];
                        $fullPath = __DIR__ . '/' . $imagePath;
                        if (file_exists($fullPath)) {
                            echo "<p class='text-green-600'>✓ Image exists</p>";
                            echo "<img src='$imagePath' alt='Banner' class='w-32 h-20 object-cover mt-2'>";
                        } else {
                            echo "<p class='text-red-600'>✗ Image missing: $fullPath</p>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-orange-600'>No active banners found</p>";
                }
            } catch (Exception $e) {
                echo "<p class='text-red-600'>✗ Database error: " . $e->getMessage() . "</p>";
            }
            ?>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Test 3: Banner Functions</h2>
            <?php
            try {
                require_once 'includes/banner-functions.php';
                echo "<p class='text-green-600'>✓ Banner functions loaded</p>";

                $activeBanners = getActiveBanners();
                echo "<p>Active banners from function: " . count($activeBanners) . "</p>";

                if (!empty($activeBanners)) {
                    echo "<h3>Banner Display:</h3>";
                    echo "<div class='border-2 border-dashed border-gray-300 p-4'>";
                    displayBannerCarousel();
                    echo "</div>";
                } else {
                    echo "<p class='text-orange-600'>No banners to display</p>";
                }
            } catch (Exception $e) {
                echo "<p class='text-red-600'>✗ Banner functions error: " . $e->getMessage() . "</p>";
            }
            ?>
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