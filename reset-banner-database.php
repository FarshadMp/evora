<?php
// Reset and reinitialize banner database
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Reset Banner Database</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <style>
        body { font-family: Arial, sans-serif; }
    </style>
</head>
<body class='bg-gray-50'>
    <div class='max-w-4xl mx-auto p-8'>
        <div class='bg-white rounded-lg shadow p-8'>
            <h1 class='text-3xl font-bold mb-8'>Reset Banner Database</h1>";

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=localhost", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p class='text-green-600'>✓ Connected to MySQL server</p>";

    // Drop database if exists
    $pdo->exec("DROP DATABASE IF EXISTS evora_banners");
    echo "<p class='text-blue-600'>✓ Dropped existing database</p>";

    // Create database
    $pdo->exec("CREATE DATABASE evora_banners");
    echo "<p class='text-green-600'>✓ Created database 'evora_banners'</p>";

    // Use database
    $pdo->exec("USE evora_banners");
    echo "<p class='text-green-600'>✓ Using database 'evora_banners'</p>";

    // Create banners table
    $sql = "CREATE TABLE banners (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        image_path VARCHAR(500) NOT NULL,
        link_url VARCHAR(500),
        button_text VARCHAR(100),
        is_active BOOLEAN DEFAULT TRUE,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "<p class='text-green-600'>✓ Created banners table</p>";

    // Insert default banner
    $defaultBanner = "INSERT INTO banners (title, description, image_path, link_url, button_text, is_active, sort_order) 
                     VALUES ('ÉVORA Luxury Jewelry', 'Discover exquisite luxury jewelry at ÉVORA', 'images/banners/banner.jpg', '#', 'SHOP NOW', 1, 1)";
    $pdo->exec($defaultBanner);
    echo "<p class='text-green-600'>✓ Inserted default banner</p>";

    // Verify
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM banners");
    $result = $stmt->fetch();
    echo "<p class='text-green-600'>✓ Database contains " . $result['count'] . " banners</p>";

    echo "<div class='bg-green-50 border border-green-200 rounded-lg p-6 mt-6'>
            <h2 class='text-lg font-medium text-green-800 mb-2'>🎉 Database Reset Complete!</h2>
            <p class='text-green-700'>The banner database has been successfully reset and initialized.</p>
          </div>";
} catch (Exception $e) {
    echo "<div class='bg-red-50 border border-red-200 rounded-lg p-6 mt-6'>
            <h2 class='text-lg font-medium text-red-800 mb-2'>✗ Database Reset Failed</h2>
            <p class='text-red-700'>Error: " . $e->getMessage() . "</p>
          </div>";
}

echo "<div class='mt-6'>
        <h3 class='text-lg font-medium mb-4'>Next Steps:</h3>
        <div class='space-y-2'>
            <a href='admin/banner-manager.php' class='block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600'>Go to Admin Panel</a>
            <a href='index.php' class='block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600'>View Homepage</a>
            <a href='simple-banner-test.php' class='block bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600'>Test Banner System</a>
        </div>
      </div>";

echo "</div></div></body></html>";
