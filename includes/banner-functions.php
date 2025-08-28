<?php
require_once dirname(__DIR__) . '/config/database.php';

/**
 * Get all active banners ordered by sort order
 */
function getActiveBanners()
{
    global $pdo;

    try {
        // Make sure we're using the correct database
        $pdo->exec("USE " . DB_NAME);

        $stmt = $pdo->prepare("SELECT * FROM banners WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching banners: " . $e->getMessage());
        return [];
    } catch (Exception $e) {
        error_log("General error in getActiveBanners: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single banner by ID
 */
function getBannerById($id)
{
    global $pdo;

    try {
        // Make sure we're using the correct database
        $pdo->exec("USE " . DB_NAME);

        $stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching banner: " . $e->getMessage());
        return null;
    }
}

/**
 * Display banner carousel HTML
 */
function displayBannerCarousel()
{
    try {
        $banners = getActiveBanners();

        if (empty($banners)) {
            // Fallback to default banner
            echo '<section class="banner-section">
                    <img src="images/banners/banner.jpg" alt="ÉVORA Luxury Jewelry Banner"
                        class="w-full h-screen object-cover object-center">
                  </section>';
            return;
        }
    } catch (Exception $e) {
        // If there's any error, fallback to default banner
        error_log("Banner display error: " . $e->getMessage());
        echo '<section class="banner-section">
                <img src="images/banners/banner.jpg" alt="ÉVORA Luxury Jewelry Banner"
                    class="w-full h-screen sm:h-64 md:h-80 lg:h-96 xl:h-auto object-cover object-center">
              </section>';
        return;
    }

    if (count($banners) == 1) {
        // Single banner
        $banner = $banners[0];
        echo '<section class="banner-section">';
        if ($banner['link_url']) {
            echo '<a href="' . htmlspecialchars($banner['link_url']) . '">';
        }
        echo '<img src="' . htmlspecialchars($banner['image_path']) . '" 
                   alt="' . htmlspecialchars($banner['title']) . '"
                   class="w-full h-screen object-cover object-center">';
        if ($banner['link_url']) {
            echo '</a>';
        }
        echo '</section>';
        return;
    }

    // Multiple banners - create carousel
    echo '<section class="banner-section relative overflow-hidden">';
    echo '<div class="banner-carousel flex transition-transform duration-500 ease-in-out" id="banner-carousel">';

    foreach ($banners as $banner) {
        echo '<div class="banner-slide flex-shrink-0 w-full relative">';
        if ($banner['link_url']) {
            echo '<a href="' . htmlspecialchars($banner['link_url']) . '">';
        }
        echo '<img src="' . htmlspecialchars($banner['image_path']) . '" 
                   alt="' . htmlspecialchars($banner['title']) . '"
                   class="w-full h-screen object-cover object-center">';

        // Banner content overlay
        if ($banner['title'] || $banner['description'] || $banner['button_text']) {
            echo '<div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">';
            echo '<div class="text-center px-4 sm:px-6 md:px-8">';
            if ($banner['title']) {
                echo '<h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-light text-white mb-3 sm:mb-4">' . htmlspecialchars($banner['title']) . '</h2>';
            }
            if ($banner['description']) {
                echo '<p class="text-sm sm:text-base md:text-lg text-white/90 mb-6 sm:mb-8 font-light">' . htmlspecialchars($banner['description']) . '</p>';
            }
            if ($banner['button_text']) {
                echo '<button class="bg-white text-primary px-4 sm:px-5 py-2 hover:bg-gray-100 transition-all duration-300 font-medium text-xs sm:text-sm">' . htmlspecialchars($banner['button_text']) . '</button>';
            }
            echo '</div>';
            echo '</div>';
        }

        if ($banner['link_url']) {
            echo '</a>';
        }
        echo '</div>';
    }

    echo '</div>';

    // Dots indicator (only if multiple banners)
    if (count($banners) > 1) {
        echo '<div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">';
        foreach ($banners as $index => $banner) {
            $activeClass = $index === 0 ? 'bg-white' : 'bg-white/50';
            echo '<button class="banner-dot w-1 h-1 rounded-full ' . $activeClass . ' transition-all duration-200" data-slide="' . $index . '"></button>';
        }
        echo '</div>';
    }

    echo '</section>';

    // Add JavaScript for carousel functionality
    if (count($banners) > 1) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const carousel = document.getElementById("banner-carousel");
                const slides = carousel.querySelectorAll(".banner-slide");
                const dots = document.querySelectorAll(".banner-dot");
                let currentSlide = 0;
                
                function showSlide(index) {
                    carousel.style.transform = `translateX(-${index * 100}%)`;
                    dots.forEach((dot, i) => {
                        dot.classList.toggle("bg-white", i === index);
                        dot.classList.toggle("bg-white/50", i !== index);
                    });
                    currentSlide = index;
                }
                
                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                }
                
                function prevSlide() {
                    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                    showSlide(currentSlide);
                }
                
                // Event listeners
                dots.forEach((dot, index) => {
                    dot.addEventListener("click", () => showSlide(index));
                });
                
                // Auto-play
                setInterval(nextSlide, 5000);
            });
        </script>';
    }
}

/**
 * Display a single banner (for specific pages)
 */
function displaySingleBanner($bannerId = null)
{
    if ($bannerId) {
        $banner = getBannerById($bannerId);
    } else {
        $banners = getActiveBanners();
        $banner = !empty($banners) ? $banners[0] : null;
    }

    if (!$banner) {
        // Fallback to default banner
        echo '<section class="banner-section">
                <img src="images/banners/banner.jpg" alt="ÉVORA Luxury Jewelry Banner"
                    class="w-full h-screen object-cover object-center">
              </section>';
        return;
    }

    echo '<section class="banner-section">';
    if ($banner['link_url']) {
        echo '<a href="' . htmlspecialchars($banner['link_url']) . '">';
    }
    echo '<img src="' . htmlspecialchars($banner['image_path']) . '" 
               alt="' . htmlspecialchars($banner['title']) . '"
               class="w-full h-screen object-cover object-center">';

    // Banner content overlay
    if ($banner['title'] || $banner['description'] || $banner['button_text']) {
        echo '<div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">';
        echo '<div class="text-center px-4 sm:px-6 md:px-8">';
        if ($banner['title']) {
            echo '<h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-light text-white mb-3 sm:mb-4">' . htmlspecialchars($banner['title']) . '</h2>';
        }
        if ($banner['description']) {
            echo '<p class="text-sm sm:text-base md:text-lg text-white/90 mb-6 sm:mb-8 font-light">' . htmlspecialchars($banner['description']) . '</p>';
        }
        if ($banner['button_text']) {
            echo '<button class="bg-white text-primary px-4 sm:px-5 py-2 hover:bg-gray-100 transition-all duration-300 font-medium text-xs sm:text-sm">' . htmlspecialchars($banner['button_text']) . '</button>';
        }
        echo '</div>';
        echo '</div>';
    }

    if ($banner['link_url']) {
        echo '</a>';
    }
    echo '</section>';
}
