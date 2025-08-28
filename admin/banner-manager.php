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
    header('Location: banner-manager.php');
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
                <p class="text-gray-600">Banner Management System</p>
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

// Handle banner operations
if ($_POST['action'] == 'add_banner') {
    // Debug: Log the POST data
    error_log("Add banner action triggered");
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $link_url = $_POST['link_url'] ?? '';
    $button_text = $_POST['button_text'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = $_POST['sort_order'] ?? 0;

    error_log("Processed data - Title: $title, Active: $is_active, Sort: $sort_order");

    // Handle file upload
    $image_path = '';
    $upload_errors = [];

    if (!isset($_FILES['banner_image'])) {
        $upload_errors[] = "No banner image file uploaded";
    } elseif ($_FILES['banner_image']['error'] !== 0) {
        $upload_errors[] = "Banner image upload error: " . $_FILES['banner_image']['error'];
    } else {
        // Validate file type
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_types)) {
            $upload_errors[] = "Banner image must be JPG, PNG, GIF, or WebP";
        } else {
            // Create upload directory if it doesn't exist
            $upload_dir = '../images/banners/';
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0755, true)) {
                    $upload_errors[] = "Failed to create upload directory";
                }
            }

            // Check if directory is writable
            if (!is_writable($upload_dir)) {
                $upload_errors[] = "Upload directory is not writable";
            }

            $file_name = 'banner_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $upload_path)) {
                $image_path = 'images/banners/' . $file_name;
            } else {
                $upload_errors[] = "Failed to upload banner image";
            }
        }
    }

    // Debug information
    if (!empty($upload_errors)) {
        error_log("Banner upload errors: " . implode(", ", $upload_errors));
    }

    // If no upload errors, insert the banner
    if (empty($upload_errors) && $image_path) {
        try {
            $stmt = $pdo->prepare("INSERT INTO banners (title, description, image_path, link_url, button_text, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $description, $image_path, $link_url, $button_text, $is_active, $sort_order]);

            if ($result) {
                $bannerId = $pdo->lastInsertId();
                $_SESSION['success_message'] = "Banner added successfully!";
                error_log("Banner added successfully! ID: $bannerId, Title: $title, Image: $image_path");
            } else {
                $_SESSION['error_message'] = "Failed to add banner to database";
                error_log("Banner insertion failed - no rows affected");
            }
        } catch (Exception $e) {
            error_log("Error adding banner: " . $e->getMessage());
            $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = implode(", ", $upload_errors);
    }

    header('Location: banner-manager.php');
    exit;
}

if ($_POST['action'] == 'delete_banner') {
    $banner_id = $_POST['banner_id'];

    // Get image path before deleting
    $stmt = $pdo->prepare("SELECT image_path FROM banners WHERE id = ?");
    $stmt->execute([$banner_id]);
    $banner = $stmt->fetch();

    if ($banner && $banner['image_path'] != 'images/banners/banner.jpg') {
        // Delete image file
        $file_path = '../' . $banner['image_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Delete from database
    try {
        $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
        $result = $stmt->execute([$banner_id]);

        if ($result) {
            $_SESSION['success_message'] = "Banner deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to delete banner";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error deleting banner: " . $e->getMessage();
    }

    header('Location: banner-manager.php');
    exit;
}

if ($_POST['action'] == 'update_banner') {
    $banner_id = $_POST['banner_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link_url = $_POST['link_url'];
    $button_text = $_POST['button_text'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = $_POST['sort_order'];

    try {
        $stmt = $pdo->prepare("UPDATE banners SET title = ?, description = ?, link_url = ?, button_text = ?, is_active = ?, sort_order = ? WHERE id = ?");
        $result = $stmt->execute([$title, $description, $link_url, $button_text, $is_active, $sort_order, $banner_id]);

        if ($result) {
            $_SESSION['success_message'] = "Banner updated successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to update banner";
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error updating banner: " . $e->getMessage();
    }

    header('Location: banner-manager.php');
    exit;
}

// Get all banners
$stmt = $pdo->query("SELECT * FROM banners ORDER BY sort_order ASC, created_at DESC");
$banners = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉVORA Admin - Banner Manager</title>
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
                    <h1 class="text-2xl font-light text-gray-800">ÉVORA Banner Manager</h1>
                    <p class="text-sm text-gray-600">Manage your website banners</p>
                </div>
                <div class="flex space-x-4">
                    <a href="product-manager.php" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">
                        Product Manager
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

        <!-- Add New Banner Section -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
            <h2 class="text-lg font-medium text-gray-800 mb-4">Add New Banner</h2>

            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="add_banner">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" id="title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>

                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="0" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="link_url" class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                        <input type="url" name="link_url" id="link_url"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>

                    <div>
                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                        <input type="text" name="button_text" id="button_text"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-1">Banner Image</label>
                    <input type="file" name="banner_image" id="banner_image" accept="image/*" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Recommended size: 1920x600px or similar aspect ratio</p>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" checked
                        class="h-4 w-4 text-[#5A3E28] focus:ring-[#5A3E28] border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                </div>

                <button type="submit"
                    class="bg-[#5A3E28] text-white py-2 px-4 rounded-md hover:bg-[#4A2E18] transition-colors duration-200 uppercase text-sm tracking-wide">
                    Add Banner
                </button>
            </form>
        </div>

        <!-- Existing Banners -->
        <div class="bg-white rounded-lg shadow-sm border">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-medium text-gray-800">Existing Banners</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($banners as $banner): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="../<?php echo htmlspecialchars($banner['image_path']); ?>"
                                        alt="<?php echo htmlspecialchars($banner['title']); ?>"
                                        class="h-16 w-32 object-cover rounded">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($banner['title']); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($banner['description']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $banner['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo $banner['is_active'] ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo $banner['sort_order']; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="editBanner(<?php echo htmlspecialchars(json_encode($banner)); ?>)"
                                        class="text-[#5A3E28] hover:text-[#4A2E18] mr-3">Edit</button>
                                    <?php if ($banner['image_path'] != 'images/banners/banner.jpg'): ?>
                                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this banner?')">
                                            <input type="hidden" name="action" value="delete_banner">
                                            <input type="hidden" name="banner_id" value="<?php echo $banner['id']; ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Banner Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-medium text-gray-800">Edit Banner</h3>
                </div>

                <form method="POST" class="p-6 space-y-4">
                    <input type="hidden" name="action" value="update_banner">
                    <input type="hidden" name="banner_id" id="edit_banner_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title" id="edit_title" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                        </div>

                        <div>
                            <label for="edit_sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" id="edit_sort_order" min="0"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_link_url" class="block text-sm font-medium text-gray-700 mb-1">Link URL</label>
                            <input type="url" name="link_url" id="edit_link_url"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                        </div>

                        <div>
                            <label for="edit_button_text" class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                            <input type="text" name="button_text" id="edit_button_text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#5A3E28] focus:border-transparent">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="edit_is_active"
                            class="h-4 w-4 text-[#5A3E28] focus:ring-[#5A3E28] border-gray-300 rounded">
                        <label for="edit_is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors duration-200">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#5A3E28] text-white rounded-md hover:bg-[#4A2E18] transition-colors duration-200">
                            Update Banner
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editBanner(banner) {
            document.getElementById('edit_banner_id').value = banner.id;
            document.getElementById('edit_title').value = banner.title;
            document.getElementById('edit_description').value = banner.description;
            document.getElementById('edit_link_url').value = banner.link_url;
            document.getElementById('edit_button_text').value = banner.button_text;
            document.getElementById('edit_sort_order').value = banner.sort_order;
            document.getElementById('edit_is_active').checked = banner.is_active == 1;

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
    </script>
</body>

</html>