<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/includes/product-functions.php';

// Simple authentication check
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

if ($_POST['action'] == 'add_image') {
    $product_id = $_POST['product_id'] ?? null;

    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Product ID is required']);
        exit;
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        echo json_encode(['success' => false, 'message' => 'No image uploaded or upload error']);
        exit;
    }

    // Validate file type
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed']);
        exit;
    }

    // Create upload directory if it doesn't exist
    $upload_dir = '../images/products/';
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
            exit;
        }
    }

    // Generate unique filename
    $file_name = 'product_additional_' . time() . '_' . uniqid() . '.' . $file_extension;
    $upload_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
        $image_path = 'images/products/' . $file_name;

        // Get current max order
        $stmt = $pdo->prepare("SELECT MAX(image_order) as max_order FROM product_images WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $result = $stmt->fetch();
        $new_order = ($result['max_order'] ?? -1) + 1;

        // Insert into database
        if (addProductImage($product_id, $image_path, $new_order)) {
            echo json_encode(['success' => true, 'message' => 'Image added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save image to database']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
    }
} elseif ($_POST['action'] == 'delete_image') {
    $image_id = $_POST['image_id'] ?? null;

    if (!$image_id) {
        echo json_encode(['success' => false, 'message' => 'Image ID is required']);
        exit;
    }

    if (deleteProductImage($image_id)) {
        echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete image']);
    }
} elseif ($_POST['action'] == 'update_order') {
    $image_id = $_POST['image_id'] ?? null;
    $new_order = $_POST['new_order'] ?? null;

    if (!$image_id || $new_order === null) {
        echo json_encode(['success' => false, 'message' => 'Image ID and new order are required']);
        exit;
    }

    if (updateProductImageOrder($image_id, $new_order)) {
        echo json_encode(['success' => true, 'message' => 'Image order updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update image order']);
    }
} elseif ($_POST['action'] == 'get_images') {
    $product_id = $_POST['product_id'] ?? null;

    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Product ID is required']);
        exit;
    }

    $images = getProductImages($product_id);
    echo json_encode(['success' => true, 'images' => $images]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

