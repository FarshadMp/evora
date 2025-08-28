<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ÉVORA - Luxury Jewelry</title>
    <meta name="description"
        content="Discover exquisite luxury jewelry at ÉVORA. Handcrafted pieces for every occasion.">

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

    <!-- Dynamic Banner Section -->
    <?php
    include 'includes/banner-functions.php';
    displayBannerCarousel();
    ?>

    <!-- Best Sellers Section -->
    <?php
    include 'includes/product-functions.php';
    $bestsellerProducts = getBestsellerProducts(6);
    ?>
    <section class="py-8 sm:py-12 md:py-16 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <div>
            <!-- Section Header -->
            <div class="flex items-end justify-between mb-8 sm:mb-10 md:mb-12">
                <div class="text-left">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-light text-primary mb-2">BEST SELLERS</h2>
                    <p class="text-xs sm:text-sm text-primary font-light">Curated edits of bestselling luxury jewelry
                    </p>
                </div>
                <a href="bestsellers.php"
                    class="text-sm text-evora-brown hover:text-primary transition-colors duration-200 underline">
                    View All
                </a>
            </div>

            <!-- Product Carousel -->
            <div class="relative">

                <!-- Carousel Container -->
                <div class="flex gap-3 sm:gap-4 md:gap-6 overflow-x-auto scrollbar-hide pb-4" id="bestseller-carousel">
                    <?php
                    if (!empty($bestsellerProducts)) {
                        foreach ($bestsellerProducts as $product) {
                            displayProductCard($product);
                        }
                    } else {
                        echo '<div class="text-center text-gray-500 py-8">No bestseller products available</div>';
                    }
                    ?>
                </div>

                <!-- Scroll Progress Bar -->
                <div class="w-full bg-gray-200 h-0.5 mt-8">
                    <div class="bg-evora-brown h-0.5 transition-all duration-300" id="scroll-progress"
                        style="width: 0%"></div>
                </div>
            </div>
        </div>
    </section>



    <!-- Category Section -->
    <?php
    include 'includes/category-functions.php';
    ?>
    <section class="pb-8 sm:pb-12 md:pb-16 px-4 sm:px-6 md:px-8 bg-evora-beige category-section">
        <div>
            <!-- Category Grid -->
            <?php displayCategoryGrid(); ?>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-16 sm:py-16 md:py-16 bg-[#5A3E28]">
        <div class="">

            <!-- Reviews Carousel -->
            <div class="relative overflow-hidden">
                <!-- Reviews Container -->
                <div class="flex gap-8 sm:gap-12 md:gap-16" id="reviews-carousel">
                    <!-- Original Reviews -->
                    <!-- Review 1 -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Review 2 -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Review 3 -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Review 4 -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Review 5 -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Duplicated Reviews for Seamless Loop -->
                    <!-- Review 1 (Duplicate) -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Review 2 (Duplicate) -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Review 3 (Duplicate) -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Review 4 (Duplicate) -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                    <!-- Review 5 (Duplicate) -->
                    <div class="flex-shrink-0 w-80 p-4">
                        <!-- Review Text -->
                        <p class="text-white text-sm mb-4 text-center">"I loveeee the <em>Multi Stone
                                Bracelet</em> in
                            the summer. I layer this with other pieces and it gives me the most beautiful layered
                            look with the perfect amount of sparkle. It's amazing for my style."</p>
                        <!-- Reviewer -->
                        <p class="text-white/80 text-xs font-light text-center">RADHIKA, FASHION BLOGGER</p>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <!-- New Arrivals Section -->
    <?php
    $newArrivalProducts = getNewArrivalProducts(6);
    ?>
    <section class="py-8 sm:py-12 md:py-16 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <div>
            <!-- Section Header -->
            <div class="flex items-center justify-between mb-8 sm:mb-10 md:mb-12">
                <div class="text-left">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-light text-primary mb-2">NEW ARRIVALS</h2>
                    <p class="text-xs sm:text-sm text-primary font-light">Discover our latest luxury jewelry pieces</p>
                </div>
                <a href="new-arrivals.php"
                    class="text-sm text-evora-brown hover:text-primary transition-colors duration-200 underline">
                    View All
                </a>
            </div>

            <!-- Product Carousel -->
            <div class="relative">
                <!-- Carousel Container -->
                <div class="flex gap-3 sm:gap-4 md:gap-6 overflow-x-auto scrollbar-hide pb-4" id="newarrivals-carousel">
                    <?php
                    if (!empty($newArrivalProducts)) {
                        foreach ($newArrivalProducts as $product) {
                            displayProductCard($product);
                        }
                    } else {
                        echo '<div class="text-center text-gray-500 py-8">No new arrival products available</div>';
                    }
                    ?>
                </div>

            </div>



            <!-- Scroll Progress Bar -->
            <div class="w-full bg-gray-200 h-0.5 mt-8">
                <div class="bg-evora-brown h-0.5 transition-all duration-300" id="newarrivals-scroll-progress"
                    style="width: 0%"></div>
            </div>
        </div>
        </div>
    </section>

    <!-- Evolving Traditions Section -->
    <section class="relative h-screen bg-cover bg-center flex items-center justify-center"
        style="background-image: url('images/banners/sm_ban.png');">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative z-10 px-4 sm:px-6 md:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-light text-white mb-3 sm:mb-4">Evolving
                Traditions</h2>
            <p class="text-sm sm:text-base md:text-lg text-white/90 mb-6 sm:mb-8 font-light">Curated edits of
                bestselling clean beauty</p>
            <button
                class="bg-white text-primary px-4 sm:px-5 py-2 hover:bg-gray-100 transition-all duration-300 font-medium text-xs sm:text-sm uppercase tracking-wide">
                SHOP NOW
            </button>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>


    <!-- Custom JavaScript -->
    <script src="assets/js/script.js"></script>
</body>

</html>