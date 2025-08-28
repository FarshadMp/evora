<?php
session_start();

// Simple authentication
$admin_password = "admin123";

if ($_POST['action'] == 'login' && $_POST['password'] == $admin_password) {
    $_SESSION['admin_logged_in'] = true;
}

if ($_POST['action'] == 'logout') {
    session_destroy();
    header('Location: test-upload-simple.php');
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
        <title>Simple Upload Test - Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-light text-gray-800 mb-8 text-center">Simple Upload Test Login</h1>
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

$upload_result = '';
if ($_POST['action'] == 'test_upload' && isset($_FILES['test_file'])) {
    $upload_result = "<h3 class='text-lg font-medium mb-4'>Upload Test Results:</h3>";
    $upload_result .= "<div class='bg-gray-100 p-4 rounded'>";

    $file = $_FILES['test_file'];
    $upload_dir = '../images/products/';

    $upload_result .= "<p><strong>File name:</strong> " . $file['name'] . "</p>";
    $upload_result .= "<p><strong>File size:</strong> " . $file['size'] . " bytes</p>";
    $upload_result .= "<p><strong>File type:</strong> " . $file['type'] . "</p>";
    $upload_result .= "<p><strong>Upload error:</strong> " . $file['error'] . "</p>";

    if ($file['error'] == 0) {
        $upload_result .= "<p><strong>Directory exists:</strong> " . (is_dir($upload_dir) ? 'Yes' : 'No') . "</p>";
        $upload_result .= "<p><strong>Directory writable:</strong> " . (is_writable($upload_dir) ? 'Yes' : 'No') . "</p>";

        if (is_dir($upload_dir) && is_writable($upload_dir)) {
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $file_name = 'test_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $file_name;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $upload_result .= "<p class='text-green-600'>✅ File uploaded successfully to: " . $upload_path . "</p>";
                $upload_result .= "<p class='text-blue-600'>🗑️ File will be automatically deleted in 5 seconds...</p>";

                // Delete the test file after 5 seconds
                header("Refresh: 5; url=test-upload-simple.php");
                unlink($upload_path);
            } else {
                $upload_result .= "<p class='text-red-600'>❌ Failed to move uploaded file</p>";
            }
        } else {
            $upload_result .= "<p class='text-red-600'>❌ Directory is not writable</p>";
        }
    } else {
        $upload_result .= "<p class='text-red-600'>❌ File upload error: " . $file['error'] . "</p>";
    }

    $upload_result .= "</div>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Upload Test - ÉVORA Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div>
                    <h1 class="text-2xl font-light text-gray-800">Simple Upload Test</h1>
                    <p class="text-sm text-gray-600">Test file upload functionality</p>
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
        <!-- Upload Test Form -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Test File Upload</h2>
            <p class="text-gray-600 mb-4">Upload a test image to verify the upload functionality is working.</p>

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

        <!-- Current Directory Status -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-4">Current Directory Status</h3>
            <div class="bg-gray-100 p-4 rounded">
                <?php
                $upload_dir = '../images/products/';
                echo "<p><strong>Upload Directory:</strong> " . $upload_dir . "</p>";
                echo "<p><strong>Exists:</strong> " . (is_dir($upload_dir) ? 'Yes' : 'No') . "</p>";
                echo "<p><strong>Writable:</strong> " . (is_writable($upload_dir) ? 'Yes' : 'No') . "</p>";
                if (is_dir($upload_dir)) {
                    echo "<p><strong>Permissions:</strong> " . substr(sprintf('%o', fileperms($upload_dir)), -4) . "</p>";
                    echo "<p><strong>Owner:</strong> " . posix_getpwuid(fileowner($upload_dir))['name'] . "</p>";
                    echo "<p><strong>Group:</strong> " . posix_getgrgid(filegroup($upload_dir))['name'] . "</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>