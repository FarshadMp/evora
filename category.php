<?php
include 'includes/category-functions.php';
include 'includes/product-functions.php';

// Get category slug from URL
$categorySlug = $_GET['slug'] ?? '';

// Convert slug back to category name for database lookup
$categoryName = str_replace('-', ' ', $categorySlug);
$categoryName = strtoupper($categoryName);

// Get category details
$category = getCategoryByName($categoryName);

// If category not found, redirect to home
if (!$category) {
    header('Location: index.php');
    exit;
}

// Get filter and sort parameters
$filterCategory = $_GET['category'] ?? strtolower($categoryName);
$sort = $_GET['sort'] ?? 'featured';

// Get products for this category
$products = getAllProducts($filterCategory, $sort);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> Collection - ÉVORA Luxury Jewelry</title>
    <meta name="description"
        content="<?php echo htmlspecialchars($category['description'] ?? 'Discover our ' . $category['name'] . ' collection at ÉVORA. Handcrafted luxury jewelry for every occasion.'); ?>">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon.svg">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon.svg">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.svg">
    <meta name="theme-color" content="#5A3E28">
</head>

<body class="bg-evora-beige">

    <!--  header  -->
    <?php include 'header.php'; ?>

    <!-- Breadcrumb Navigation -->
    <section class="py-4 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <div class="space-x-2 text-xs text-primary">
            <a href="index.php" class="text-primary hover:text-evora-brown transition-colors duration-200 uppercase">HOME</a>
            <span class="text-primary">/</span>
            <span class="text-primary font-medium uppercase"><?php echo htmlspecialchars($category['name']); ?></span>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="py-8 sm:py-12 md:py-8 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <div class="text-left">
            <h1 class="text-3xl sm:text-4xl md:text-4xl lg:text-4xl font-light text-primary mb-4"><?php echo htmlspecialchars($category['name']); ?></h1>
            <p class="text-sm text-primary font-light">
                <?php echo htmlspecialchars($category['description'] ?? 'Discover our exquisite collection of ' . strtolower($category['name']) . '. Handcrafted luxury pieces for every occasion.'); ?>
            </p>
        </div>
    </section>

    <!-- Filter and Sort Section -->
    <section class="py-4 px-4 sm:px-6 md:px-8 bg-evora-beige border-t border-b filter-sort-section">
        <div class="flex items-center justify-between">
            <!-- Filter Buttons -->
            <div class="flex items-center space-x-6 overflow-x-auto scrollbar-hide">
                <button
                    class="filter-btn active whitespace-nowrap text-sm font-medium text-primary hover:text-evora-brown transition-colors duration-200"
                    data-filter="all">
                    Shop All
                </button>
                <?php
                // Get all categories for filter buttons
                $allCategories = getActiveCategories();
                foreach ($allCategories as $cat) {
                    $catSlug = strtolower(str_replace(' ', '-', $cat['name']));
                    $isActive = (strtolower($cat['name']) === strtolower($category['name'])) ? 'active' : '';
                    echo '<button class="filter-btn ' . $isActive . ' whitespace-nowrap text-sm font-medium text-primary hover:text-evora-brown transition-colors duration-200" data-filter="' . strtolower($cat['name']) . '">';
                    echo htmlspecialchars($cat['name']);
                    echo '</button>';
                }
                ?>
            </div>

            <!-- Sort Dropdown -->
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-primary">Sort:</span>
                <div class="relative">
                    <button id="sort-dropdown-btn"
                        class="text-sm font-medium text-primary hover:text-evora-brown transition-colors duration-200 flex items-center space-x-1">
                        <span id="sort-selected">Featured</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div id="sort-dropdown"
                        class="absolute right-0 top-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg z-50 hidden min-w-32">
                        <div class="py-1">
                            <button
                                class="sort-option w-full text-left px-4 py-2 text-sm text-primary hover:bg-gray-50 transition-colors duration-200"
                                data-value="featured">Featured</button>
                            <button
                                class="sort-option w-full text-left px-4 py-2 text-sm text-primary hover:bg-gray-50 transition-colors duration-200"
                                data-value="newest">Newest</button>
                            <button
                                class="sort-option w-full text-left px-4 py-2 text-sm text-primary hover:bg-gray-50 transition-colors duration-200"
                                data-value="price-low-high">Price: Low to High</button>
                            <button
                                class="sort-option w-full text-left px-4 py-2 text-sm text-primary hover:bg-gray-50 transition-colors duration-200"
                                data-value="price-high-low">Price: High to Low</button>
                            <button
                                class="sort-option w-full text-left px-4 py-2 text-sm text-primary hover:bg-gray-50 transition-colors duration-200"
                                data-value="best-selling">Best Selling</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Grid Section -->
    <section class="pb-8 sm:pb-12 md:pb-16 pt-5 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <!-- Products Grid -->
        <?php
        if (!empty($products)) {
            displayProductGrid($products);
        } else {
            echo '<div class="text-center text-gray-500 py-8">No products available in this category at the moment</div>';
        }
        ?>

        <!-- Load More Button -->
        <div class="text-center mt-12">
            <button id="load-more-btn"
                class="bg-primary text-white px-8 py-3 text-sm font-medium hover:bg-evora-brown transition-colors duration-200 uppercase tracking-wide">
                LOAD MORE
            </button>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Custom JavaScript -->
    <script src="assets/js/script.js"></script>

    <!-- Category Page JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter functionality
            const filterBtns = document.querySelectorAll('.filter-btn');
            const productCards = document.querySelectorAll('.product-card');
            const productsGrid = document.getElementById('products-grid');

            // Set active filter button based on URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const currentCategory = urlParams.get('category') || '<?php echo strtolower($category['name']); ?>';
            const currentSort = urlParams.get('sort') || 'featured';

            filterBtns.forEach(btn => {
                if (btn.getAttribute('data-filter') === currentCategory) {
                    btn.classList.add('active');
                }
            });

            // Set active sort option
            const sortSelected = document.getElementById('sort-selected');
            const sortOptions = document.querySelectorAll('.sort-option');
            sortOptions.forEach(option => {
                if (option.getAttribute('data-value') === currentSort) {
                    sortSelected.textContent = option.textContent;
                }
            });

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');

                    // Update URL with category parameter
                    const url = new URL(window.location);
                    if (filter === 'all') {
                        url.searchParams.delete('category');
                    } else {
                        url.searchParams.set('category', filter);
                    }
                    window.location.href = url.toString();
                });
            });

            // Sort dropdown functionality
            const sortDropdownBtn = document.getElementById('sort-dropdown-btn');
            const sortDropdown = document.getElementById('sort-dropdown');

            // Toggle dropdown
            sortDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                sortDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!sortDropdownBtn.contains(e.target) && !sortDropdown.contains(e.target)) {
                    sortDropdown.classList.add('hidden');
                }
            });

            // Handle sort option selection
            sortOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    const text = this.textContent;

                    // Update selected text
                    sortSelected.textContent = text;

                    // Hide dropdown
                    sortDropdown.classList.add('hidden');

                    // Sort products based on selection
                    sortProducts(value);
                });
            });

            // Sort products function
            function sortProducts(sortBy) {
                // Update URL with sort parameter
                const url = new URL(window.location);
                url.searchParams.set('sort', sortBy);
                window.location.href = url.toString();
            }

            // Load more functionality
            const loadMoreBtn = document.getElementById('load-more-btn');
            let currentPage = 1;

            loadMoreBtn.addEventListener('click', function() {
                // Simulate loading more products
                this.textContent = 'LOADING...';
                this.disabled = true;

                setTimeout(() => {
                    // Here you would typically fetch more products from the server
                    this.textContent = 'LOAD MORE';
                    this.disabled = false;
                    currentPage++;

                    if (currentPage >= 3) {
                        this.style.display = 'none';
                    }
                }, 1000);
            });
        });
    </script>
</body>

</html>