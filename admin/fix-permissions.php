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
    header('Location: fix-permissions.php');
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
        <title>Fix Permissions - Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-light text-gray-800 mb-8 text-center">Fix Permissions Login</h1>
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

// Handle permission fix
$fix_result = '';
if ($_POST['action'] == 'fix_permissions') {
    $fix_result = "<h3 class='text-lg font-medium mb-4'>Permission Fix Results:</h3>";
    $fix_result .= "<div class='bg-gray-100 p-4 rounded'>";

    $directories = [
        '../images/',
        '../images/products/',
        '../images/banners/'
    ];

    foreach ($directories as $dir) {
        $fix_result .= "<p><strong>Checking:</strong> " . $dir . "</p>";

        // Check if directory exists
        if (!is_dir($dir)) {
            if (mkdir($dir, 0755, true)) {
                $fix_result .= "<p class='text-green-600'>✅ Created directory: " . $dir . "</p>";
            } else {
                $fix_result .= "<p class='text-red-600'>❌ Failed to create directory: " . $dir . "</p>";
            }
        } else {
            $fix_result .= "<p class='text-blue-600'>📁 Directory exists: " . $dir . "</p>";
        }

        // Set permissions
        if (is_dir($dir)) {
            // Use 777 for upload directories to ensure web server can write
            $permissions = (strpos($dir, 'products') !== false || strpos($dir, 'banners') !== false) ? 0777 : 0755;
            if (chmod($dir, $permissions)) {
                $fix_result .= "<p class='text-green-600'>✅ Set permissions " . substr(sprintf('%o', $permissions), -4) . " on: " . $dir . "</p>";
            } else {
                $fix_result .= "<p class='text-red-600'>❌ Failed to set permissions on: " . $dir . "</p>";
            }

            // Try to change ownership to daemon (Apache user on macOS)
            if (function_exists('posix_getpwuid')) {
                $current_owner = posix_getpwuid(fileowner($dir))['name'];
                if ($current_owner !== 'daemon') {
                    $fix_result .= "<p class='text-yellow-600'>⚠️ Directory owned by: " . $current_owner . " (should be daemon for Apache)</p>";
                    $fix_result .= "<p class='text-blue-600'>💡 Run: sudo chown daemon:daemon " . $dir . "</p>";
                } else {
                    $fix_result .= "<p class='text-green-600'>✅ Directory owned by daemon (Apache user)</p>";
                }
            }

            // Check if writable
            if (is_writable($dir)) {
                $fix_result .= "<p class='text-green-600'>✅ Directory is writable: " . $dir . "</p>";
            } else {
                $fix_result .= "<p class='text-red-600'>❌ Directory is not writable: " . $dir . "</p>";
            }
        }

        $fix_result .= "<br>";
    }

    // Test file creation
    $test_file = '../images/products/test_permissions.txt';
    if (file_put_contents($test_file, 'Test write permission - ' . date('Y-m-d H:i:s'))) {
        $fix_result .= "<p class='text-green-600'>✅ Successfully created test file: " . $test_file . "</p>";
        unlink($test_file); // Clean up
        $fix_result .= "<p class='text-blue-600'>🗑️ Cleaned up test file</p>";
    } else {
        $fix_result .= "<p class='text-red-600'>❌ Failed to create test file: " . $test_file . "</p>";
    }

    $fix_result .= "</div>";
}

// Get current permissions info
$permissions_info = "<h3 class='text-lg font-medium mb-4'>Current Directory Permissions:</h3>";
$permissions_info .= "<div class='bg-gray-100 p-4 rounded'>";

$directories = [
    '../images/',
    '../images/products/',
    '../images/banners/'
];

foreach ($directories as $dir) {
    $permissions_info .= "<p><strong>" . $dir . "</strong></p>";
    $permissions_info .= "<ul class='ml-4'>";
    $permissions_info .= "<li>Exists: " . (is_dir($dir) ? 'Yes' : 'No') . "</li>";
    if (is_dir($dir)) {
        $permissions_info .= "<li>Permissions: " . substr(sprintf('%o', fileperms($dir)), -4) . "</li>";
        $permissions_info .= "<li>Writable: " . (is_writable($dir) ? 'Yes' : 'No') . "</li>";
        $permissions_info .= "<li>Owner: " . posix_getpwuid(fileowner($dir))['name'] . "</li>";
        $permissions_info .= "<li>Group: " . posix_getgrgid(filegroup($dir))['name'] . "</li>";
    }
    $permissions_info .= "</ul><br>";
}

$permissions_info .= "</div>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Permissions - ÉVORA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-light text-gray-800">Fix Permissions</h1>
                    <p class="text-sm text-gray-600">Fix directory permissions for file uploads</p>
                </div>
                <div class="flex space-x-4">
                    <a href="product-manager.php" class="text-blue-600 hover:text-blue-800">Product Manager</a>
                    <a href="test-upload.php" class="text-green-600 hover:text-green-800">Test Upload</a>
                    <form method="POST" class="inline">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="text-gray-600 hover:text-gray-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Current Permissions -->
        <?php echo $permissions_info; ?>

        <!-- Fix Permissions Form -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Fix Directory Permissions</h2>
            <p class="text-gray-600 mb-4">This will create necessary directories and set proper permissions for file uploads.</p>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="fix_permissions">

                <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
                    Fix Permissions
                </button>
            </form>
        </div>

        <!-- Fix Results -->
        <?php if ($fix_result): ?>
            <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
                <?php echo $fix_result; ?>
            </div>
        <?php endif; ?>

        <!-- Manual Instructions -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-medium text-yellow-800 mb-2">Manual Fix Instructions</h3>
            <p class="text-yellow-700 mb-4">If the automatic fix doesn't work, you can manually fix permissions using these commands:</p>
            <div class="bg-yellow-100 p-4 rounded font-mono text-sm">
                <p>cd /Applications/XAMPP/xamppfiles/htdocs/evora</p>
                <p>chmod 755 images/</p>
                <p>chmod 777 images/products/ # Upload directory needs full permissions</p>
                <p>chmod 777 images/banners/ # Upload directory needs full permissions</p>
                <p>sudo chown daemon:daemon images/products/ # For macOS XAMPP</p>
                <p>sudo chown daemon:daemon images/banners/ # For macOS XAMPP</p>
                <p>sudo chown _www:_www images/products/ # For macOS Apache</p>
                <p>sudo chown www-data:www-data images/products/ # For Linux</p>
            </div>
        </div>
    </div>
</body>

</html>