<?php
require_once dirname(__DIR__) . '/config/database.php';

/**
 * Get all active categories ordered by sort order
 */
function getActiveCategories()
{
    global $pdo;

    try {
        // Make sure we're using the correct database
        $pdo->exec("USE " . DB_NAME);

        $stmt = $pdo->prepare("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching categories: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("General error in getActiveCategories: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single category by ID
 */
function getCategoryById($id)
{
    global $pdo;

    try {
        // Make sure we're using the correct database
        $pdo->exec("USE " . DB_NAME);

        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching category: " . $e->getMessage());
        return null;
    }
}

/**
 * Get a single category by name (slug)
 */
function getCategoryByName($name)
{
    global $pdo;

    try {
        // Make sure we're using the correct database
        $pdo->exec("USE " . DB_NAME);

        $stmt = $pdo->prepare("SELECT * FROM categories WHERE LOWER(name) = LOWER(?) AND is_active = 1");
        $stmt->execute([$name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching category by name: " . $e->getMessage());
        return null;
    }
}

/**
 * Display category grid HTML
 */
function displayCategoryGrid()
{
    try {
        $categories = getActiveCategories();

        if (empty($categories)) {
            echo '<div class="text-center text-gray-500 py-8">No categories available</div>';
            return;
        }
    } catch (Exception $e) {
        error_log("Category display error: " . $e->getMessage());
        echo '<div class="text-center text-gray-500 py-8">Error loading categories</div>';
        return;
    }

    echo '<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6">';

    foreach ($categories as $category) {
        $categorySlug = strtolower(str_replace(' ', '-', $category['name']));
        echo '<a href="category.php?slug=' . urlencode($categorySlug) . '" class="group cursor-pointer block">';
        echo '<div class="relative overflow-hidden mb-4">';

        if ($category['image_path']) {
            echo '<img src="' . htmlspecialchars($category['image_path']) . '" alt="' . htmlspecialchars($category['name']) . ' Collection"';
            echo ' class="w-full h-75 object-cover object-center transition-opacity duration-300">';
        } else {
            echo '<div class="w-full h-75 bg-gray-200 flex items-center justify-center">';
            echo '<span class="text-gray-400 text-sm">No Image</span>';
            echo '</div>';
        }

        echo '</div>';
        echo '<h3 class="text-sm font-medium text-primary">' . htmlspecialchars($category['name']) . '</h3>';
        echo '</a>';
    }

    echo '</div>';
}

/**
 * Display category grid HTML with custom class
 */
function displayCategoryGridWithClass($gridClass = 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4 md:gap-6')
{
    try {
        $categories = getActiveCategories();

        if (empty($categories)) {
            echo '<div class="text-center text-gray-500 py-8">No categories available</div>';
            return;
        }
    } catch (Exception $e) {
        error_log("Category display error: " . $e->getMessage());
        echo '<div class="text-center text-gray-500 py-8">Error loading categories</div>';
        return;
    }

    echo '<div class="' . $gridClass . '">';

    foreach ($categories as $category) {
        $categorySlug = strtolower(str_replace(' ', '-', $category['name']));
        echo '<a href="category.php?slug=' . urlencode($categorySlug) . '" class="group cursor-pointer block">';
        echo '<div class="relative overflow-hidden mb-4">';

        if ($category['image_path']) {
            echo '<img src="' . htmlspecialchars($category['image_path']) . '" alt="' . htmlspecialchars($category['name']) . ' Collection"';
            echo ' class="w-full h-75 object-cover object-center transition-opacity duration-300">';
        } else {
            echo '<div class="w-full h-75 bg-gray-200 flex items-center justify-center">';
            echo '<span class="text-gray-400 text-sm">No Image</span>';
            echo '</div>';
        }

        echo '</div>';
        echo '<h3 class="text-sm font-medium text-primary">' . htmlspecialchars($category['name']) . '</h3>';
        echo '</a>';
    }

    echo '</div>';
}
