<?php
require_once 'config/database.php';
require_once 'includes/product-functions.php';

// Get product ID from URL
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header('Location: index.php');
    exit;
}

// Get product details
$product = getProductById($product_id);

if (!$product) {
    header('Location: index.php');
    exit;
}

// Get all product images
$productImages = getAllProductImages($product_id);

// Get related products (same category, excluding current product)
$relatedProducts = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? AND is_active = 1 ORDER BY sort_order ASC, created_at DESC LIMIT 4");
    $stmt->execute([$product['category'], $product_id]);
    $relatedProducts = $stmt->fetchAll();
} catch (Exception $e) {
    error_log("Error fetching related products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - ÉVORA Luxury Jewelry</title>
    <meta name="description" content="<?php echo htmlspecialchars($product['description']); ?>">

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

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Breadcrumb Navigation -->
    <section class="py-4 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <div class="space-x-2 text-xs text-primary">
            <a href="index.php" class="breadcrumb-link text-primary hover:text-evora-brown transition-colors duration-200 uppercase">HOME</a>
            <span class="text-primary">/</span>
            <a href="bestsellers.php" class="breadcrumb-link text-primary hover:text-evora-brown transition-colors duration-200 uppercase">PRODUCTS</a>
            <span class="text-primary">/</span>
            <span class="text-primary font-medium uppercase"><?php echo strtoupper(htmlspecialchars($product['name'])); ?></span>
        </div>
    </section>

    <!-- Product Details Section -->
    <section class="product-details-container py-8 sm:py-12 md:py-16 px-4 sm:px-6 md:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-8">

                <!-- Product Images Gallery -->
                <div class="lg:col-span-3">
                    <div class="flex gap-4">
                        <!-- Thumbnail Navigation -->
                        <div class="hidden lg:flex flex-col space-y-3 w-20">
                            <?php foreach ($productImages as $index => $image): ?>
                                <button class="thumbnail-item aspect-square overflow-hidden border-1 <?php echo $index === 0 ? 'border-primary' : 'border-gray-200 hover:border-primary'; ?> transition-all duration-200 group <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
                                    <img src="<?php echo htmlspecialchars($image['image_path']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?> - View <?php echo $index + 1; ?>"
                                        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-200">
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Main Image Display -->
                        <div class="flex-1">
                            <div class="relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                <div class="product-carousel flex transition-transform duration-500 ease-in-out" id="product-carousel">
                                    <?php foreach ($productImages as $index => $image): ?>
                                        <div class="product-slide flex-shrink-0 w-full relative">
                                            <img src="<?php echo htmlspecialchars($image['image_path']); ?>"
                                                alt="<?php echo htmlspecialchars($product['name']); ?> - View <?php echo $index + 1; ?>"
                                                class="w-full h-96 lg:h-[600px] object-cover object-center cursor-zoom-in related-product-image">
                                            <div class="zoom-indicator">Click to zoom</div>
                                            <?php if ($product['status'] !== 'NORMAL'): ?>
                                                <div class="absolute top-6 right-6">
                                                    <?php echo getStatusBadge($product['status']); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Mobile Dot Navigation -->
                            <?php if (count($productImages) > 1): ?>
                                <div class="flex justify-center space-x-2 mt-4 lg:hidden">
                                    <?php foreach ($productImages as $index => $image): ?>
                                        <button class="thumbnail-dot w-2 h-2 rounded-full <?php echo $index === 0 ? 'bg-primary' : 'bg-gray-300'; ?> transition-all duration-200 <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <h1 class="product-title text-2xl sm:text-3xl md:text-4xl font-light mb-2">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </h1>
                        <p class="text-sm text-gray-600 mb-4">
                            Category: <span class="text-primary font-medium"><?php echo ucfirst(htmlspecialchars($product['category'])); ?></span>
                        </p>
                    </div>

                    <div class="space-y-4">
                        <p class="text-sm text-primary leading-relaxed">
                            <?php echo htmlspecialchars($product['description']); ?>
                        </p>
                    </div>

                    <!-- Price Section -->
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <span class="price-display text-2xl font-semibold">
                                Rs. <?php echo number_format($product['price'], 2); ?>
                            </span>
                            <?php if ($product['original_price']): ?>
                                <span class="text-lg text-gray-500 line-through">
                                    Rs. <?php echo number_format($product['original_price'], 2); ?>
                                </span>
                                <?php
                                $discount = round((($product['original_price'] - $product['price']) / $product['original_price']) * 100);
                                if ($discount > 0):
                                ?>
                                    <span class="discount-badge text-sm px-3 py-1 rounded-full font-medium">
                                        <?php echo $discount; ?>% OFF
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Product Status -->
                    <?php if ($product['status'] !== 'NORMAL'): ?>
                        <div class="status-info-box p-4">
                            <p class="text-sm text-gray-700">
                                <strong>Status:</strong>
                                <span class="font-medium">
                                    <?php
                                    switch ($product['status']) {
                                        case 'NEW':
                                            echo 'This is a new arrival!';
                                            break;
                                        case 'SOLD OUT':
                                            echo 'This item is currently sold out.';
                                            break;
                                        case 'REFILL':
                                            echo 'This item is being restocked.';
                                            break;
                                    }
                                    ?>
                                </span>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Product Features -->
                    <div class="space-y-3">
                        <h3 class="text-lg font-medium text-primary">Product Features</h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="feature-item">High-quality materials</li>
                            <li class="feature-item">Handcrafted with precision</li>
                            <li class="feature-item">Elegant design</li>
                            <li class="feature-item">Perfect for any occasion</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4 pt-6">
                        <?php if ($product['status'] === 'SOLD OUT'): ?>
                            <button disabled class="action-button w-full text-white py-3 px-6 font-medium uppercase text-sm tracking-wide">
                                SOLD OUT
                            </button>
                        <?php else: ?>
                            <button onclick="addToCart(<?php echo $product['id']; ?>)"
                                class="action-button w-full text-white py-3 px-6 font-medium uppercase text-sm tracking-wide">
                                ADD TO CART
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Product Tags -->
                    <div class="flex flex-wrap gap-2">
                        <?php if ($product['is_bestseller']): ?>
                            <span class="product-tag bestseller text-xs px-3 py-1 rounded-full font-medium">Best Seller</span>
                        <?php endif; ?>
                        <?php if ($product['is_new_arrival']): ?>
                            <span class="product-tag new-arrival text-xs px-3 py-1 rounded-full font-medium">New Arrival</span>
                        <?php endif; ?>
                        <span class="product-tag text-xs px-3 py-1 rounded-full font-medium"><?php echo ucfirst(htmlspecialchars($product['category'])); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products Section -->
    <?php if (!empty($relatedProducts)): ?>
        <section class="py-8 sm:py-12 md:py-16 px-4 sm:px-6 md:px-8 bg-evora-beige border-t border-gray-200">
            <div class="max-w-7xl mx-auto">
                <div class="text-left mb-8">
                    <h2 class="product-title text-2xl sm:text-3xl md:text-4xl font-light mb-2">Related Products</h2>
                    <p class="text-sm text-primary font-light">You might also like these products</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
                    <?php foreach ($relatedProducts as $relatedProduct): ?>
                        <a href="product-details.php?id=<?php echo $relatedProduct['id']; ?>" class="product-card group cursor-pointer block">
                            <div class="relative overflow-hidden mb-4 aspect-square">
                                <img src="<?php echo htmlspecialchars($relatedProduct['image_main']); ?>"
                                    alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>"
                                    class="w-full h-full object-cover object-center transition-opacity duration-300 product-image-main">
                                <?php
                                $hoverImage = $relatedProduct['image_hover'] ? $relatedProduct['image_hover'] : $relatedProduct['image_main'];
                                ?>
                                <img src="<?php echo htmlspecialchars($hoverImage); ?>"
                                    alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>"
                                    class="absolute inset-0 w-full h-full object-cover object-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 product-image-hover">
                                <?php if ($relatedProduct['status'] !== 'NORMAL'): ?>
                                    <div class="absolute top-3 left-3">
                                        <?php echo getStatusBadge($relatedProduct['status']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h3 class="text-sm font-medium text-primary mb-2"><?php echo htmlspecialchars($relatedProduct['name']); ?></h3>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold text-primary">Rs. <?php echo number_format($relatedProduct['price'], 2); ?></span>
                                <?php if ($relatedProduct['original_price']): ?>
                                    <span class="text-sm text-gray-500 line-through">Rs. <?php echo number_format($relatedProduct['original_price'], 2); ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Custom JavaScript -->
    <script src="assets/js/script.js"></script>

    <!-- Add to Cart JavaScript -->
    <script>
        function addToCart(productId) {
            // Show loading state
            const button = event.target;
            const originalText = button.textContent;
            button.textContent = 'Adding...';
            button.disabled = true;

            // Send AJAX request to add item to cart
            fetch('cart-api.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=add&product_id=${productId}&quantity=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        button.textContent = 'Added to Cart!';
                        button.classList.remove('action-button');
                        button.classList.add('action-button', 'success');

                        // Update cart count in header
                        updateCartCount();

                        // Trigger cart update for other pages
                        if (typeof triggerCartUpdate === 'function') {
                            triggerCartUpdate();
                        }

                        // Reset button after 2 seconds
                        setTimeout(() => {
                            button.textContent = originalText;
                            button.disabled = false;
                            button.classList.remove('success');
                        }, 2000);
                    } else {
                        // Show error message
                        alert('Error: ' + data.message);
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error adding item to cart. Please try again.');
                    button.textContent = originalText;
                    button.disabled = false;
                });
        }

        function updateCartCount() {
            // Use the global updateCartIcon function if available
            if (typeof updateCartIcon === 'function') {
                updateCartIcon();
            } else {
                // Fallback to the original method
                fetch('cart-api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=get_count'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update cart count in header if it exists
                            const cartCountElement = document.querySelector('.cart-count');
                            if (cartCountElement) {
                                if (data.count > 0) {
                                    cartCountElement.textContent = data.count > 99 ? '99+' : data.count;
                                    cartCountElement.style.display = 'flex';
                                } else {
                                    cartCountElement.style.display = 'none';
                                }
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error updating cart count:', error);
                    });
            }
        }
    </script>

    <!-- Product Gallery JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('product-carousel');
            if (!carousel) return;

            const slides = carousel.querySelectorAll('.product-slide');
            const prevBtn = document.querySelector('.carousel-prev');
            const nextBtn = document.querySelector('.carousel-next');
            const dots = document.querySelectorAll('.thumbnail-dot');
            const thumbnails = document.querySelectorAll('.thumbnail-item');

            let currentSlide = 0;
            const totalSlides = slides.length;

            // Only initialize carousel if there are multiple slides
            if (totalSlides <= 1) return;

            function showSlide(index) {
                // Ensure index is within bounds
                if (index < 0) index = totalSlides - 1;
                if (index >= totalSlides) index = 0;

                // Update carousel position
                carousel.style.transform = `translateX(-${index * 100}%)`;
                currentSlide = index;

                // Update dots
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });

                // Update thumbnails
                thumbnails.forEach((thumb, i) => {
                    if (i < totalSlides) {
                        thumb.classList.toggle('active', i === index);
                    }
                });
            }

            // Navigation button event listeners
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    showSlide(currentSlide - 1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    showSlide(currentSlide + 1);
                });
            }

            // Dot navigation event listeners
            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    showSlide(index);
                });
            });

            // Thumbnail navigation event listeners
            thumbnails.forEach((thumb, index) => {
                if (index < totalSlides) {
                    thumb.addEventListener('click', () => {
                        showSlide(index);
                    });
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    showSlide(currentSlide - 1);
                } else if (e.key === 'ArrowRight') {
                    showSlide(currentSlide + 1);
                }
            });

            // Touch/swipe support for mobile
            let startX = 0;
            let endX = 0;

            carousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            });

            carousel.addEventListener('touchend', (e) => {
                endX = e.changedTouches[0].clientX;
                handleSwipe();
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = startX - endX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        // Swipe left - next slide
                        showSlide(currentSlide + 1);
                    } else {
                        // Swipe right - previous slide
                        showSlide(currentSlide - 1);
                    }
                }
            }

            // Image zoom functionality (placeholder for future implementation)
            carousel.addEventListener('click', function(e) {
                if (e.target.tagName === 'IMG') {
                    // Future: Implement lightbox/zoom functionality
                    console.log('Image clicked - zoom functionality can be implemented here');
                }
            });

            // Initialize first slide
            showSlide(0);
        });
    </script>
</body>

</html>