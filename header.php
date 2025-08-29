<!-- Header -->
<header class="header-bg py-2 px-4 sm:px-6 md:px-8 lg:px-12 relative">
    <div class="mx-auto flex items-center justify-between">
        <!-- Mobile Menu Button -->
        <button class="md:hidden text-primary hover:text-evora-brown transition-colors duration-200"
            id="mobile-menu-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>

        <!-- Left Navigation -->
        <nav class="hidden md:flex space-x-6 lg:space-x-8 xl:space-x-12">
            <!-- SHOP Dropdown -->
            <div class="group">
                <a href="#"
                    class="text-primary nav-text text-sm font-light hover:text-evora-brown transition-colors duration-200 flex items-center">
                    SHOP
                    <svg class="w-3 h-3 ml-1 transition-transform duration-200 group-hover:rotate-180 dropdown-arrow"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </a>

                <!-- Mega Menu -->
                <div
                    class="absolute top-full left-1/2 transform -translate-x-1/2 w-screen max-w-screen-xl bg-evora-beige opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 mega-menu">
                    <div class="py-6 md:py-8 lg:py-12 px-4 md:px-8 lg:pl-12 lg:pr-8">
                        <div
                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 md:gap-8 lg:gap-12">
                            <!-- Column 1: FEATURED -->
                            <div class="col-span-1 md:col-span-1 lg:col-span-1 xl:col-span-1">
                                <h3 class="text-xs font-semibold text-primary tracking-widest uppercase mb-4 md:mb-6">
                                    FEATURED</h3>
                                <ul class="space-y-2 md:space-y-4">
                                    <li><a href="new-arrivals.php"
                                            class="text-xs md:text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">New
                                            Arrivals</a></li>
                                    <li><a href="bestsellers.php"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Best
                                            Sellers</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Back
                                            In Stock</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Unexpected
                                            Proposal</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Vintage</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Serena
                                            Baguette Tennis</a></li>
                                </ul>
                            </div>

                            <!-- Column 2: CATEGORIES -->
                            <div class="col-span-1">
                                <h3 class="text-xs font-semibold text-primary tracking-widest uppercase mb-6">
                                    CATEGORIES</h3>
                                <ul class="space-y-4">
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Shop
                                            All</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Bracelets</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Necklaces</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Rings</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Earrings</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Charms
                                            & Pendants</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Gift
                                            Cards</a></li>
                                </ul>
                            </div>

                            <!-- Column 3: COLLECTIONS -->
                            <div class="col-span-1">
                                <h3 class="text-xs font-semibold text-primary tracking-widest uppercase mb-6">
                                    COLLECTIONS</h3>
                                <ul class="space-y-4">
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Tennis
                                            Collection</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Stacked
                                            Collection</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Men's
                                            Edit</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Pinky
                                            Rings</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Silver
                                            925</a></li>
                                </ul>
                            </div>

                            <!-- Column 4: CUSTOMIZE -->
                            <div class="col-span-1">
                                <h3 class="text-xs font-semibold text-primary tracking-widest uppercase mb-6">
                                    CUSTOMIZE</h3>
                                <ul class="space-y-4">
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Engraveable</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Personalize</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Birthstones</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Column 5: Image Previews -->
                            <div class="col-span-1">
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Left Image -->
                                    <div class="group cursor-pointer">
                                        <div class="relative overflow-hidden mb-2">
                                            <img src="images/products/pro_1.jpg" alt="Tennis Bracelets"
                                                class="w-full h-32 object-cover object-center group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <p class="text-xs font-semibold text-primary tracking-widest uppercase">
                                            TENNIS BRACELETS</p>
                                    </div>

                                    <!-- Right Image -->
                                    <div class="group cursor-pointer">
                                        <div class="relative overflow-hidden mb-2">
                                            <img src="images/products/pro_2.jpg" alt="New Arrivals"
                                                class="w-full h-32 object-cover object-center group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <p class="text-xs font-semibold text-primary tracking-widest uppercase">NEW
                                            ARRIVALS</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DISCOVER Dropdown -->
            <div class="group">
                <a href="#"
                    class="text-primary nav-text text-sm font-light hover:text-evora-brown transition-colors duration-200 flex items-center">
                    DISCOVER
                    <svg class="w-3 h-3 ml-1 transition-transform duration-200 group-hover:rotate-180 dropdown-arrow"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </a>

                <!-- Mega Menu -->
                <div
                    class="absolute top-full left-1/2 transform -translate-x-1/2 w-screen max-w-screen-xl bg-evora-beige opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 mega-menu">
                    <div class="py-6 md:py-8 lg:py-12 px-4 md:px-8 lg:pl-12 lg:pr-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 lg:gap-12">
                            <!-- Column 1: ABOUT -->
                            <div class="col-span-1">
                                <h3 class="text-xs font-semibold text-primary tracking-widest uppercase mb-6">
                                    ABOUT</h3>
                                <ul class="space-y-4">
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Our
                                            Story</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Designers</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Sustainability</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Press</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Column 2: SERVICES -->
                            <div class="col-span-1">
                                <h3 class="text-xs font-semibold text-primary tracking-widest uppercase mb-6">
                                    SERVICES</h3>
                                <ul class="space-y-4">
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Jewelry
                                            Care</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Sizing
                                            Guide</a></li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Repairs</a>
                                    </li>
                                    <li><a href="#"
                                            class="text-sm text-primary hover:text-evora-brown transition-colors duration-200 menu-item">Appraisals</a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Column 3: Image Preview -->
                            <div class="col-span-1">
                                <div class="group cursor-pointer">
                                    <div class="relative overflow-hidden mb-2">
                                        <img src="images/products/pro_3.jpg" alt="Our Story"
                                            class="w-full h-32 object-cover object-center group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <p class="text-xs font-semibold text-primary tracking-widest uppercase">OUR STORY
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Center Logo -->
        <div class="flex-1 md:flex-none flex justify-center">
            <a href="index.php" class="flex items-center">
                <img src="images/logo.svg" alt="ÉVORA Logo" class="h-12 sm:h-14 md:h-16 w-auto">
            </a>
        </div>

        <!-- Right Navigation -->
        <nav class="flex items-center space-x-4 sm:space-x-6 md:space-x-8 lg:space-x-12">
            <a href="contact.php"
                class="hidden lg:block text-primary nav-text text-sm font-light hover:text-evora-brown transition-colors duration-200">CONTACT</a>
            <?php
            // Include cart functions for cart icon
            if (file_exists('includes/cart-functions.php')) {
                include_once 'includes/cart-functions.php';
                displayCartIcon();
            } else {
                // Fallback cart icon if functions file is not available
                echo '<a href="cart.php" class="cart-icon relative text-primary hover:text-evora-brown transition-colors duration-200">';
                echo '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>';
                echo '</svg>';
                echo '</a>';
            }
            ?>
        </nav>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden md:hidden">
        <div
            class="bg-evora-beige h-full w-80 max-w-[80vw] transform -translate-x-full transition-transform duration-300 ease-in-out">
            <div class="p-6">
                <div class="flex justify-between items-center mb-8">
                    <img src="images/logo.svg" alt="ÉVORA Logo" class="h-8 w-auto">
                    <button id="mobile-menu-close"
                        class="text-primary hover:text-evora-brown transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <!-- Mobile Navigation -->
                <nav class="space-y-6">
                    <!-- SHOP Section -->
                    <div>
                        <h3 class="text-sm font-semibold text-primary tracking-widest uppercase mb-4">SHOP</h3>
                        <ul class="space-y-3">
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">New
                                    Arrivals</a></li>
                            <li><a href="bestsellers.php"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Best
                                    Sellers</a></li>
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Bracelets</a>
                            </li>
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Necklaces</a>
                            </li>
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Rings</a>
                            </li>
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Earrings</a>
                            </li>
                        </ul>
                    </div>
                    <!-- DISCOVER Section -->
                    <div>
                        <h3 class="text-sm font-semibold text-primary tracking-widest uppercase mb-4">DISCOVER</h3>
                        <ul class="space-y-3">
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Our
                                    Story</a></li>
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Designers</a>
                            </li>
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Jewelry
                                    Care</a></li>
                        </ul>
                    </div>
                    <!-- SUPPORT Section -->
                    <div>
                        <h3 class="text-sm font-semibold text-primary tracking-widest uppercase mb-4">SUPPORT</h3>
                        <ul class="space-y-3">
                            <li><a href="how-to-order.php"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">How to Order</a>
                            </li>
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">Contact</a>
                            </li>
                            <li><a href="#"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">FAQ</a>
                            </li>
                        </ul>
                    </div>
                    <!-- CART Section -->
                    <div>
                        <h3 class="text-sm font-semibold text-primary tracking-widest uppercase mb-4">CART</h3>
                        <ul class="space-y-3">
                            <li><a href="cart.php"
                                    class="text-sm text-primary hover:text-evora-brown transition-colors duration-200">View Cart</a>
                            </li>
                        </ul>
                    </div>

                </nav>
            </div>
        </div>
    </div>
</header>