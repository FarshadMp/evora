<?php
session_start();
require_once dirname(__DIR__) . '/config/database.php';

// Simple authentication check
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

// Handle different API operations
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_banners':
        getBanners();
        break;

    case 'get_banner':
        getBanner();
        break;

    case 'add_banner':
        addBanner();
        break;

    case 'update_banner':
        updateBanner();
        break;

    case 'delete_banner':
        deleteBanner();
        break;

    case 'toggle_status':
        toggleBannerStatus();
        break;

    case 'reorder_banners':
        reorderBanners();
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}

function getBanners()
{
    global $pdo;

    try {
        $stmt = $pdo->query("SELECT * FROM banners ORDER BY sort_order ASC, created_at DESC");
        $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'banners' => $banners]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function getBanner()
{
    global $pdo;

    $id = $_GET['id'] ?? $_POST['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Banner ID required']);
        return;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        $banner = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($banner) {
            echo json_encode(['success' => true, 'banner' => $banner]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Banner not found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function addBanner()
{
    global $pdo;

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $link_url = $_POST['link_url'] ?? '';
    $button_text = $_POST['button_text'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = $_POST['sort_order'] ?? 0;

    if (!$title) {
        http_response_code(400);
        echo json_encode(['error' => 'Title is required']);
        return;
    }

    // Handle file upload
    $image_path = '';
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] == 0) {
        $upload_dir = '../images/banners/';
        $file_extension = pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION);
        $file_name = 'banner_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['banner_image']['tmp_name'], $upload_path)) {
            $image_path = 'images/banners/' . $file_name;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to upload image']);
            return;
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Image upload required']);
        return;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO banners (title, description, image_path, link_url, button_text, is_active, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $image_path, $link_url, $button_text, $is_active, $sort_order]);

        $banner_id = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'banner_id' => $banner_id, 'message' => 'Banner added successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function updateBanner()
{
    global $pdo;

    $id = $_POST['id'] ?? null;
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $link_url = $_POST['link_url'] ?? '';
    $button_text = $_POST['button_text'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = $_POST['sort_order'] ?? 0;

    if (!$id || !$title) {
        http_response_code(400);
        echo json_encode(['error' => 'Banner ID and title are required']);
        return;
    }

    try {
        $stmt = $pdo->prepare("UPDATE banners SET title = ?, description = ?, link_url = ?, button_text = ?, is_active = ?, sort_order = ? WHERE id = ?");
        $stmt->execute([$title, $description, $link_url, $button_text, $is_active, $sort_order, $id]);

        echo json_encode(['success' => true, 'message' => 'Banner updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function deleteBanner()
{
    global $pdo;

    $id = $_POST['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Banner ID required']);
        return;
    }

    try {
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image_path FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        $banner = $stmt->fetch();

        if ($banner && $banner['image_path'] != 'images/banners/banner.jpg') {
            // Delete image file
            $file_path = '../' . $banner['image_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM banners WHERE id = ?");
        $stmt->execute([$id]);

        echo json_encode(['success' => true, 'message' => 'Banner deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function toggleBannerStatus()
{
    global $pdo;

    $id = $_POST['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Banner ID required']);
        return;
    }

    try {
        $stmt = $pdo->prepare("UPDATE banners SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$id]);

        // Get updated status
        $stmt = $pdo->prepare("SELECT is_active FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        $banner = $stmt->fetch();

        echo json_encode(['success' => true, 'is_active' => $banner['is_active'], 'message' => 'Status updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

function reorderBanners()
{
    global $pdo;

    $orders = $_POST['orders'] ?? null;
    if (!$orders || !is_array($orders)) {
        http_response_code(400);
        echo json_encode(['error' => 'Order data required']);
        return;
    }

    try {
        $pdo->beginTransaction();

        foreach ($orders as $order) {
            $id = $order['id'] ?? null;
            $sort_order = $order['sort_order'] ?? 0;

            if ($id) {
                $stmt = $pdo->prepare("UPDATE banners SET sort_order = ? WHERE id = ?");
                $stmt->execute([$sort_order, $id]);
            }
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Banners reordered successfully']);
    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
