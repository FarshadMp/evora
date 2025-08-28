<?php
require_once dirname(__DIR__) . '/config/database.php';

/**
 * Get bestseller products
 */
function getBestsellerProducts($limit = 6)
{
    global $pdo;

    try {
        // Use LIMIT directly in the query since MariaDB doesn't support parameterized LIMIT
        $stmt = $pdo->prepare("SELECT * FROM products WHERE is_bestseller = 1 AND is_active = 1 ORDER BY sort_order ASC, created_at DESC LIMIT " . (int)$limit);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching bestseller products: " . $e->getMessage());
        return [];
    }
}

/**
 * Get new arrival products
 */
function getNewArrivalProducts($limit = 6)
{
    global $pdo;

    try {
        // Use LIMIT directly in the query since MariaDB doesn't support parameterized LIMIT
        $stmt = $pdo->prepare("SELECT * FROM products WHERE is_new_arrival = 1 AND is_active = 1 ORDER BY sort_order ASC, created_at DESC LIMIT " . (int)$limit);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching new arrival products: " . $e->getMessage());
        return [];
    }
}

/**
 * Get all products with filtering
 */
function getAllProducts($category = null, $sort = 'featured', $limit = null, $productType = null)
{
    global $pdo;

    try {
        $sql = "SELECT * FROM products WHERE is_active = 1";
        $params = [];

        if ($category && $category !== 'all') {
            $sql .= " AND category = ?";
            $params[] = $category;
        }

        // Filter by product type (new arrivals, bestsellers, etc.)
        if ($productType === 'new_arrivals') {
            $sql .= " AND is_new_arrival = 1";
        } elseif ($productType === 'bestsellers') {
            $sql .= " AND is_bestseller = 1";
        }

        // Add sorting
        switch ($sort) {
            case 'price-low-high':
                $sql .= " ORDER BY price ASC";
                break;
            case 'price-high-low':
                $sql .= " ORDER BY price DESC";
                break;
            case 'newest':
                $sql .= " ORDER BY created_at DESC";
                break;
            case 'best-selling':
                $sql .= " ORDER BY is_bestseller DESC, sort_order ASC";
                break;
            case 'featured':
            default:
                $sql .= " ORDER BY sort_order ASC, created_at DESC";
                break;
        }

        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching products: " . $e->getMessage());
        return [];
    }
}

/**
 * Get product by ID
 */
function getProductById($id)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (Exception $e) {
        error_log("Error fetching product by ID: " . $e->getMessage());
        return null;
    }
}

/**
 * Get status badge HTML
 */
function getStatusBadge($status)
{
    switch ($status) {
        case 'NEW':
            return '<span class="bg-[#AB8C52] text-primary text-xs px-3 py-1.5 font-light">NEW</span>';
        case 'SOLD OUT':
            return '<span class="bg-red-500 text-white text-xs px-3 py-1.5 font-light">SOLD OUT</span>';
        case 'REFILL':
            return '<span class="bg-green-500 text-white text-xs px-3 py-1.5 font-light">REFILL</span>';
        default:
            return '';
    }
}

/**
 * Display product card HTML
 */
function displayProductCard($product, $cardClass = 'flex-shrink-0 w-75')
{
    $statusBadge = getStatusBadge($product['status']);
    $hoverImage = $product['image_hover'] ? $product['image_hover'] : $product['image_main'];

    echo '<div class="' . $cardClass . '">';
    echo '<a href="product-details.php?id=' . $product['id'] . '" class="cursor-pointer block">';
    echo '<div class="relative overflow-hidden mb-4 aspect-square">';
    echo '<img src="' . htmlspecialchars($product['image_main']) . '" alt="' . htmlspecialchars($product['name']) . '" class="w-full h-full object-cover object-center product-image-main">';
    echo '<img src="' . htmlspecialchars($hoverImage) . '" alt="' . htmlspecialchars($product['name']) . '" class="absolute inset-0 w-full h-full object-cover object-center opacity-0 product-image-hover">';
    if ($statusBadge) {
        echo '<div class="absolute top-3 left-3">' . $statusBadge . '</div>';
    }
    echo '</div>';
    echo '<h3 class="text-sm font-medium text-primary mb-2">' . htmlspecialchars($product['name']) . '</h3>';
    echo '<div class="flex items-center gap-2">';
    echo '<span class="text-sm font-semibold text-primary">Rs. ' . number_format($product['price'], 2) . '</span>';
    if ($product['original_price']) {
        echo '<span class="text-sm text-gray-500 line-through">Rs. ' . number_format($product['original_price'], 2) . '</span>';
    }
    echo '</div>';
    echo '</a>';
    echo '</div>';
}

/**
 * Display product grid HTML
 */
function displayProductGrid($products, $gridClass = 'grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8')
{
    echo '<div class="' . $gridClass . '" id="products-grid">';

    foreach ($products as $product) {
        echo '<a href="product-details.php?id=' . $product['id'] . '" class="product-card cursor-pointer block" data-category="' . htmlspecialchars($product['category']) . '" data-price="' . $product['price'] . '">';
        echo '<div class="relative overflow-hidden mb-4 aspect-square">';
        echo '<img src="' . htmlspecialchars($product['image_main']) . '" alt="' . htmlspecialchars($product['name']) . '" class="w-full h-full object-cover object-center product-image-main">';
        $hoverImage = $product['image_hover'] ? $product['image_hover'] : $product['image_main'];
        echo '<img src="' . htmlspecialchars($hoverImage) . '" alt="' . htmlspecialchars($product['name']) . '" class="absolute inset-0 w-full h-full object-cover object-center opacity-0 product-image-hover">';
        $statusBadge = getStatusBadge($product['status']);
        if ($statusBadge) {
            echo '<div class="absolute top-3 left-3">' . $statusBadge . '</div>';
        }
        echo '</div>';
        echo '<h3 class="text-sm font-medium text-primary mb-2">' . htmlspecialchars($product['name']) . '</h3>';
        echo '<div class="flex items-center gap-2">';
        echo '<span class="text-sm font-semibold text-primary">Rs. ' . number_format($product['price'], 2) . '</span>';
        if ($product['original_price']) {
            echo '<span class="text-sm text-gray-500 line-through">Rs. ' . number_format($product['original_price'], 2) . '</span>';
        }
        echo '</div>';
        echo '</a>';
    }

    echo '</div>';
}

/**
 * Display product carousel HTML
 */
function displayProductCarousel($products, $carouselId = 'product-carousel')
{
    echo '<div class="flex gap-3 sm:gap-4 md:gap-6 overflow-x-auto scrollbar-hide pb-4" id="' . $carouselId . '">';

    foreach ($products as $product) {
        displayProductCard($product);
    }

    echo '</div>';
}

/**
 * Get product images by product ID
 */
function getProductImages($productId)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? AND is_active = 1 ORDER BY image_order ASC, created_at ASC");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error fetching product images: " . $e->getMessage());
        return [];
    }
}

/**
 * Add product image
 */
function addProductImage($productId, $imagePath, $imageOrder = 0)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("INSERT INTO product_images (product_id, image_path, image_order) VALUES (?, ?, ?)");
        return $stmt->execute([$productId, $imagePath, $imageOrder]);
    } catch (Exception $e) {
        error_log("Error adding product image: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete product image
 */
function deleteProductImage($imageId)
{
    global $pdo;

    try {
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE id = ?");
        $stmt->execute([$imageId]);
        $image = $stmt->fetch();

        if ($image) {
            // Delete file from server
            $filePath = dirname(__DIR__) . '/' . $image['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM product_images WHERE id = ?");
        return $stmt->execute([$imageId]);
    } catch (Exception $e) {
        error_log("Error deleting product image: " . $e->getMessage());
        return false;
    }
}

/**
 * Update product image order
 */
function updateProductImageOrder($imageId, $newOrder)
{
    global $pdo;

    try {
        $stmt = $pdo->prepare("UPDATE product_images SET image_order = ? WHERE id = ?");
        return $stmt->execute([$newOrder, $imageId]);
    } catch (Exception $e) {
        error_log("Error updating product image order: " . $e->getMessage());
        return false;
    }
}

/**
 * Get all images for a product (including main and hover images)
 */
function getAllProductImages($productId)
{
    global $pdo;

    try {
        // Get main product data
        $stmt = $pdo->prepare("SELECT image_main, image_hover FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $product = $stmt->fetch();

        if (!$product) {
            return [];
        }

        // Get additional images
        $stmt = $pdo->prepare("SELECT * FROM product_images WHERE product_id = ? AND is_active = 1 ORDER BY image_order ASC, created_at ASC");
        $stmt->execute([$productId]);
        $additionalImages = $stmt->fetchAll();

        // Combine all images
        $allImages = [];

        // Add main image first
        if ($product['image_main']) {
            $allImages[] = [
                'id' => 'main',
                'image_path' => $product['image_main'],
                'image_order' => 0,
                'type' => 'main'
            ];
        }

        // Add hover image if different from main
        if ($product['image_hover'] && $product['image_hover'] !== $product['image_main']) {
            $allImages[] = [
                'id' => 'hover',
                'image_path' => $product['image_hover'],
                'image_order' => 1,
                'type' => 'hover'
            ];
        }

        // Add additional images
        foreach ($additionalImages as $image) {
            $allImages[] = [
                'id' => $image['id'],
                'image_path' => $image['image_path'],
                'image_order' => $image['image_order'] + 2, // Start after main and hover
                'type' => 'additional'
            ];
        }

        // Sort by image_order
        usort($allImages, function ($a, $b) {
            return $a['image_order'] - $b['image_order'];
        });

        return $allImages;
    } catch (Exception $e) {
        error_log("Error fetching all product images: " . $e->getMessage());
        return [];
    }
}
