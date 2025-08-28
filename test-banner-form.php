<?php
// Simple test form for banner addition
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Banner Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-8">
        <div class="bg-white rounded-lg shadow p-8">
            <h1 class="text-3xl font-bold mb-8">Test Banner Addition</h1>

            <form method="POST" action="admin/banner-manager.php" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="action" value="add_banner">

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" id="title" required
                        value="Test Banner <?php echo date('Y-m-d H:i:s'); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">This is a test banner to verify the system is working.</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="link_url" class="block text-sm font-medium text-gray-700 mb-2">Link URL</label>
                        <input type="url" name="link_url" id="link_url" value="#"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="button_text" class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                        <input type="text" name="button_text" id="button_text" value="SHOP NOW"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="1" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" checked
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">Active</label>
                    </div>
                </div>

                <div>
                    <label for="banner_image" class="block text-sm font-medium text-gray-700 mb-2">Banner Image *</label>
                    <input type="file" name="banner_image" id="banner_image" accept="image/*" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Choose an image file (JPG, PNG, GIF)</p>
                </div>

                <div class="flex space-x-4">
                    <button type="submit"
                        class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors duration-200">
                        Add Test Banner
                    </button>
                    <a href="debug-admin-panel.php"
                        class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-colors duration-200">
                        Debug System
                    </a>
                </div>
            </form>

            <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h3 class="font-semibold text-blue-800 mb-2">Instructions:</h3>
                <ol class="list-decimal list-inside space-y-1 text-blue-700 text-sm">
                    <li>Fill in the form above (pre-filled with test data)</li>
                    <li>Choose an image file to upload</li>
                    <li>Click "Add Test Banner"</li>
                    <li>Check the debug page to see what happened</li>
                    <li>Check your homepage to see if the banner appears</li>
                </ol>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="admin/banner-manager.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-center">
                    Admin Panel
                </a>
                <a href="index.php" class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 text-center">
                    View Homepage
                </a>
                <a href="debug-admin-panel.php" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 text-center">
                    Debug System
                </a>
            </div>
        </div>
    </div>
</body>

</html>