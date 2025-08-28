<?php
// Debug script for admin panel banner addition issues
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Admin Panel Debug</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <style>
        body { font-family: Arial, sans-serif; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
    </style>
</head>
<body class='bg-gray-50'>
    <div class='max-w-6xl mx-auto p-8'>
        <h1 class='text-3xl font-bold mb-8'>Admin Panel Debug</h1>";

// Test 1: Database Connection
echo "<div class='bg-white rounded-lg shadow p-6 mb-6'>
        <h2 class='text-xl font-semibold mb-4'>1. Database Connection Test</h2>";

try {
    require_once 'config/database.php';
    echo "<p class='success'>✓ Database connection successful</p>";
    echo "<p class='info'>Database: " . DB_NAME . "</p>";
} catch (Exception $e) {
    echo "<p class='error'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    exit;
}

echo "</div>";

// Test 2: Check if banners table exists and has correct structure
echo "<div class='bg-white rounded-lg shadow p-6 mb-6'>
        <h2 class='text-xl font-semibold mb-4'>2. Database Table Test</h2>";

try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'banners'");
    if ($stmt->rowCount() > 0) {
        echo "<p class='success'>✓ Banners table exists</p>";

        // Check table structure
        $stmt = $pdo->query("DESCRIBE banners");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3 class='font-semibold mt-4 mb-2'>Table Structure:</h3>";
        echo "<table class='border-collapse border border-gray-300 w-full'>";
        echo "<tr class='bg-gray-100'><th class='border border-gray-300 p-2'>Field</th><th class='border border-gray-300 p-2'>Type</th><th class='border border-gray-300 p-2'>Null</th><th class='border border-gray-300 p-2'>Key</th><th class='border border-gray-300 p-2'>Default</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td class='border border-gray-300 p-2'>" . $column['Field'] . "</td>";
            echo "<td class='border border-gray-300 p-2'>" . $column['Type'] . "</td>";
            echo "<td class='border border-gray-300 p-2'>" . $column['Null'] . "</td>";
            echo "<td class='border border-gray-300 p-2'>" . $column['Key'] . "</td>";
            echo "<td class='border border-gray-300 p-2'>" . $column['Default'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>✗ Banners table does not exist</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error checking table: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Test 3: Test banner insertion
echo "<div class='bg-white rounded-lg shadow p-6 mb-6'>
        <h2 class='text-xl font-semibold mb-4'>3. Banner Insertion Test</h2>";

try {
    // Test data
    $testBanner = [
        'title' => 'Test Banner ' . time(),
        'description' => 'This is a test banner to verify insertion works',
        'image_path' => 'images/banners/test_banner.jpg',
        'link_url' => '#',
        'button_text' => 'TEST',
        'is_active' => 1,
        'sort_order' => 999
    ];

    // Try to insert test banner
    $stmt = $pdo->prepare("INSERT INTO banners (title, description, image_path, link_url, button_text, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([
        $testBanner['title'],
        $testBanner['description'],
        $testBanner['image_path'],
        $testBanner['link_url'],
        $testBanner['button_text'],
        $testBanner['is_active'],
        $testBanner['sort_order']
    ]);

    if ($result) {
        $bannerId = $pdo->lastInsertId();
        echo "<p class='success'>✓ Test banner inserted successfully! ID: " . $bannerId . "</p>";

        // Verify the banner was inserted
        $stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ?");
        $stmt->execute([$bannerId]);
        $insertedBanner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($insertedBanner) {
            echo "<p class='success'>✓ Banner verified in database</p>";
            echo "<pre>" . print_r($insertedBanner, true) . "</pre>";
        } else {
            echo "<p class='error'>✗ Banner not found after insertion</p>";
        }

        // Clean up test banner
        $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
        $stmt->execute([$bannerId]);
        echo "<p class='info'>✓ Test banner cleaned up</p>";
    } else {
        echo "<p class='error'>✗ Banner insertion failed</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error during insertion test: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Test 4: File upload directory permissions
echo "<div class='bg-white rounded-lg shadow p-6 mb-6'>
        <h2 class='text-xl font-semibold mb-4'>4. File Upload Directory Test</h2>";

$uploadDir = 'images/banners/';

if (is_dir($uploadDir)) {
    echo "<p class='success'>✓ Upload directory exists: $uploadDir</p>";

    if (is_writable($uploadDir)) {
        echo "<p class='success'>✓ Upload directory is writable</p>";
    } else {
        echo "<p class='error'>✗ Upload directory is not writable</p>";
        echo "<p class='info'>Try: chmod 755 $uploadDir</p>";
    }
} else {
    echo "<p class='error'>✗ Upload directory does not exist: $uploadDir</p>";
    echo "<p class='info'>Try: mkdir -p $uploadDir</p>";
}

// Test file creation
$testFile = $uploadDir . 'test_' . time() . '.txt';
if (file_put_contents($testFile, 'test') !== false) {
    echo "<p class='success'>✓ File creation test successful</p>";
    unlink($testFile);
    echo "<p class='info'>✓ Test file cleaned up</p>";
} else {
    echo "<p class='error'>✗ File creation test failed</p>";
}

echo "</div>";

// Test 5: Current banners in database
echo "<div class='bg-white rounded-lg shadow p-6 mb-6'>
        <h2 class='text-xl font-semibold mb-4'>5. Current Banners in Database</h2>";

try {
    $stmt = $pdo->query("SELECT * FROM banners ORDER BY created_at DESC");
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($banners)) {
        echo "<p class='warning'>⚠ No banners found in database</p>";
    } else {
        echo "<p class='success'>✓ Found " . count($banners) . " banners</p>";
        echo "<table class='border-collapse border border-gray-300 w-full'>";
        echo "<tr class='bg-gray-100'>";
        echo "<th class='border border-gray-300 p-2'>ID</th>";
        echo "<th class='border border-gray-300 p-2'>Title</th>";
        echo "<th class='border border-gray-300 p-2'>Active</th>";
        echo "<th class='border border-gray-300 p-2'>Created</th>";
        echo "<th class='border border-gray-300 p-2'>Image Exists</th>";
        echo "</tr>";

        foreach ($banners as $banner) {
            echo "<tr>";
            echo "<td class='border border-gray-300 p-2'>" . $banner['id'] . "</td>";
            echo "<td class='border border-gray-300 p-2'>" . htmlspecialchars($banner['title']) . "</td>";
            echo "<td class='border border-gray-300 p-2'>" . ($banner['is_active'] ? 'Yes' : 'No') . "</td>";
            echo "<td class='border border-gray-300 p-2'>" . $banner['created_at'] . "</td>";

            // Check if image exists
            $imagePath = $banner['image_path'];
            $fullPath = __DIR__ . '/' . $imagePath;
            if (file_exists($fullPath)) {
                echo "<td class='border border-gray-300 p-2 success'>✓ Exists</td>";
            } else {
                echo "<td class='border border-gray-300 p-2 error'>✗ Missing</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
    }
} catch (Exception $e) {
    echo "<p class='error'>✗ Error checking banners: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Test 6: PHP Configuration
echo "<div class='bg-white rounded-lg shadow p-6 mb-6'>
        <h2 class='text-xl font-semibold mb-4'>6. PHP Configuration Test</h2>";

echo "<p class='info'>PHP Version: " . PHP_VERSION . "</p>";
echo "<p class='info'>Upload Max Filesize: " . ini_get('upload_max_filesize') . "</p>";
echo "<p class='info'>Post Max Size: " . ini_get('post_max_size') . "</p>";
echo "<p class='info'>Max File Uploads: " . ini_get('max_file_uploads') . "</p>";
echo "<p class='info'>File Uploads Enabled: " . (ini_get('file_uploads') ? 'Yes' : 'No') . "</p>";

echo "</div>";

// Recommendations
echo "<div class='bg-blue-50 border border-blue-200 rounded-lg p-6'>
        <h2 class='text-xl font-semibold mb-4'>Recommendations</h2>
        <ul class='list-disc list-inside space-y-2 text-blue-700'>";

echo "<li>If insertion test failed, check database permissions</li>";
echo "<li>If file upload failed, check directory permissions</li>";
echo "<li>If no banners exist, try adding one through the admin panel</li>";
echo "<li>Check PHP error logs for specific error messages</li>";
echo "</ul>
      </div>";

// Quick Actions
echo "<div class='bg-white rounded-lg shadow p-6 mt-6'>
        <h2 class='text-xl font-semibold mb-4'>Quick Actions</h2>
        <div class='grid grid-cols-1 md:grid-cols-3 gap-4'>
            <a href='admin/banner-manager.php' class='bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center'>Admin Panel</a>
            <a href='reset-banner-database.php' class='bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-center'>Reset Database</a>
            <a href='index.php' class='bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-center'>View Homepage</a>
        </div>
      </div>";

echo "</div></body></html>";
