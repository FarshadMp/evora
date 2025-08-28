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
    header('Location: test-upload.php');
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
        <title>Upload Test - Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-light text-gray-800 mb-8 text-center">Upload Test Login</h1>
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

// Handle file upload test
$upload_result = '';
if ($_POST['action'] == 'test_upload' && isset($_FILES['test_file'])) {
    $upload_result = "<h3 class='text-lg font-medium mb-4'>Upload Test Results:</h3>";
    $upload_result .= "<div class='bg-gray-100 p-4 rounded'>";

    // Check if file was uploaded
    if (!isset($_FILES['test_file'])) {
        $upload_result .= "<p class='text-red-600'>No file uploaded</p>";
    } else {
        $file = $_FILES['test_file'];
        $upload_result .= "<p><strong>File name:</strong> " . htmlspecialchars($file['name']) . "</p>";
        $upload_result .= "<p><strong>File size:</strong> " . $file['size'] . " bytes</p>";
        $upload_result .= "<p><strong>File type:</strong> " . $file['type'] . "</p>";
        $upload_result .= "<p><strong>Upload error:</strong> " . $file['error'] . "</p>";

        if ($file['error'] == 0) {
            // Test upload directory
            $upload_dir = '../images/products/';
            $upload_result .= "<p><strong>Upload directory:</strong> " . $upload_dir . "</p>";
            $upload_result .= "<p><strong>Directory exists:</strong> " . (is_dir($upload_dir) ? 'Yes' : 'No') . "</p>";
            $upload_result .= "<p><strong>Directory writable:</strong> " . (is_writable($upload_dir) ? 'Yes' : 'No') . "</p>";

            if (is_dir($upload_dir) && is_writable($upload_dir)) {
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $file_name = 'test_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;

                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    $upload_result .= "<p class='text-green-600'>✅ File uploaded successfully to: " . $upload_path . "</p>";
                } else {
                    $upload_result .= "<p class='text-red-600'>❌ Failed to move uploaded file</p>";
                }
            } else {
                if (!is_dir($upload_dir)) {
                    $upload_result .= "<p class='text-red-600'>❌ Upload directory does not exist</p>";
                    if (mkdir($upload_dir, 0755, true)) {
                        $upload_result .= "<p class='text-green-600'>✅ Created upload directory</p>";
                    } else {
                        $upload_result .= "<p class='text-red-600'>❌ Failed to create upload directory</p>";
                    }
                }
                if (!is_writable($upload_dir)) {
                    $upload_result .= "<p class='text-red-600'>❌ Upload directory is not writable</p>";
                }
            }
        } else {
            $upload_result .= "<p class='text-red-600'>❌ File upload error: " . $file['error'] . "</p>";
        }
    }

    $upload_result .= "</div>";
}

// Check PHP configuration
$php_info = "<h3 class='text-lg font-medium mb-4'>PHP Configuration:</h3>";
$php_info .= "<div class='bg-gray-100 p-4 rounded'>";
$php_info .= "<p><strong>Upload max filesize:</strong> " . ini_get('upload_max_filesize') . "</p>";
$php_info .= "<p><strong>Post max size:</strong> " . ini_get('post_max_size') . "</p>";
$php_info .= "<p><strong>Max file uploads:</strong> " . ini_get('max_file_uploads') . "</p>";
$php_info .= "<p><strong>File uploads enabled:</strong> " . (ini_get('file_uploads') ? 'Yes' : 'No') . "</p>";
$php_info .= "<p><strong>Temp directory:</strong> " . ini_get('upload_tmp_dir') ?: sys_get_temp_dir() . "</p>";
$php_info .= "</div>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Test - ÉVORA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-light text-gray-800">Upload Test</h1>
                    <p class="text-sm text-gray-600">Debug file upload issues</p>
                </div>
                <div class="flex space-x-4">
                    <a href="product-manager.php" class="text-blue-600 hover:text-blue-800">Product Manager</a>
                    <form method="POST" class="inline">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="text-gray-600 hover:text-gray-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- PHP Configuration -->
        <?php echo $php_info; ?>

        <!-- Upload Test Form -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Test File Upload</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="test_upload">

                <div>
                    <label for="test_file" class="block text-sm font-medium text-gray-700 mb-1">Select Test File</label>
                    <input type="file" name="test_file" id="test_file" accept="image/*" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Test Upload
                </button>
            </form>
        </div>

        <!-- Upload Results -->
        <?php if ($upload_result): ?>
            <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
                <?php echo $upload_result; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>