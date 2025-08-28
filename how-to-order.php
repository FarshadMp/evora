<?php
require_once 'includes/cart-functions.php';
require_once 'includes/product-functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Order - ÉVORA Luxury Jewelry</title>
    <meta name="description" content="Learn how to order jewelry from ÉVORA Luxury Jewelry. Step-by-step guide in English and Malayalam.">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

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
        <div class="flex items-center space-x-2 text-xs text-primary">
            <a href="index.php" class="text-primary hover:text-evora-brown transition-colors duration-200">HOME</a>
            <span class="text-primary">/</span>
            <span class="text-primary font-medium">How to Order</span>
        </div>
    </section>

    <!-- How to Order Section -->
    <section class="py-8 sm:py-12 md:py-16 px-4 sm:px-6 md:px-8 bg-evora-beige">
        <div class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl sm:text-4xl md:text-4xl lg:text-4xl font-light text-primary mb-4">HOW TO ORDER
                </h1>
                <p class="text-sm text-primary font-light">
                    Step-by-step guide to ordering from ÉVORA Luxury Jewelry
                </p>
            </div>

            <!-- Language Toggle -->
            <div class="flex justify-center mb-8">
                <div class="bg-white rounded-lg shadow-sm border p-1">
                    <button onclick="showLanguage('english')" id="english-btn" class="language-btn active px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        English
                    </button>
                    <button onclick="showLanguage('malayalam')" id="malayalam-btn" class="language-btn px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200">
                        മലയാളം
                    </button>
                </div>
            </div>

            <!-- English Content -->
            <div id="english-content" class="language-content">
                <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
                    <h2 class="text-2xl font-light text-primary mb-6">Shopping Cart Guide</h2>

                    <div class="space-y-8">
                        <!-- Step 1 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">Browse Products</h3>
                                <p class="text-gray-700 mb-3">Explore our collection of luxury jewelry including rings, necklaces, bracelets, earrings, and more. You can browse by category or view our bestsellers and new arrivals.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>Tip:</strong> Click on any product image to view detailed information, multiple product images, and pricing.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">2</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">Add to Cart</h3>
                                <p class="text-gray-700 mb-3">When you find a product you like, click the "ADD TO CART" button. The item will be added to your shopping cart and you'll see a confirmation message.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>Note:</strong> You can add multiple quantities of the same item or different products to your cart.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">3</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">View Your Cart</h3>
                                <p class="text-gray-700 mb-3">Click on the cart icon in the header to view your shopping cart. Here you can see all items you've added, adjust quantities, or remove items.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>Features:</strong> Update quantities using + and - buttons, remove items with the X button, and see your total order value.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">4</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">Fill Contact Information</h3>
                                <p class="text-gray-700 mb-3">On the cart page, you'll find a contact information form. Fill in all required fields including your name, phone number, and complete shipping address.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>Required Fields:</strong> First Name, Country, Street Address, City, State, PIN Code, and Phone Number.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">5</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">Checkout via WhatsApp</h3>
                                <p class="text-gray-700 mb-3">Click the "Checkout via WhatsApp" button. This will open WhatsApp with a pre-filled message containing your order details and contact information.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>What's Included:</strong> Product details, quantities, prices, your contact information, and shipping address.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 6 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">6</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">Complete Your Order</h3>
                                <p class="text-gray-700 mb-3">Send the WhatsApp message to our team. We'll review your order and contact you to confirm details, discuss payment options, and arrange delivery.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>Next Steps:</strong> We'll confirm your order, discuss payment methods, and provide delivery timeline.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-2xl font-light text-primary mb-6">Important Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-primary mb-3">Payment Options</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li>• Cash on Delivery (COD)</li>
                                <li>• Bank Transfer</li>
                                <li>• UPI Payment</li>
                                <li>• Credit/Debit Cards</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-primary mb-3">Delivery Information</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li>• Free shipping on orders above Rs. 500</li>
                                <li>• Standard delivery: 3-5 business days</li>
                                <li>• Express delivery available</li>
                                <li>• Secure packaging guaranteed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Malayalam Content -->
            <div id="malayalam-content" class="language-content hidden">
                <div class="bg-white rounded-lg shadow-sm border p-8 mb-8">
                    <h2 class="text-2xl font-light text-primary mb-6">ഷോപ്പിംഗ് കാർട്ട് ഗൈഡ്</h2>

                    <div class="space-y-8">
                        <!-- Step 1 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">ഉൽപ്പന്നങ്ങൾ ബ്രൗസ് ചെയ്യുക</h3>
                                <p class="text-gray-700 mb-3">വളകൾ, ഹാരങ്ങൾ, ബ്രേസ്ലറ്റുകൾ, കാത്സരങ്ങൾ എന്നിവ ഉൾപ്പെടെയുള്ള ലക്ഷ്മി ആഭരണങ്ങളുടെ ശേഖരം പര്യവേക്ഷണം ചെയ്യുക. നിങ്ങൾക്ക് വിഭാഗം അനുസരിച്ച് ബ്രൗസ് ചെയ്യാം അല്ലെങ്കിൽ ഞങ്ങളുടെ ബെസ്റ്റ് സെല്ലറുകളും പുതിയ ആഗമനങ്ങളും കാണാം.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>നുറുങ്ങ്:</strong> വിശദമായ വിവരങ്ങൾ, ഒന്നിലധികം ഉൽപ്പന്ന ചിത്രങ്ങൾ, വില എന്നിവ കാണാൻ ഏതെങ്കിലും ഉൽപ്പന്ന ചിത്രത്തിൽ ക്ലിക്ക് ചെയ്യുക.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">2</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">കാർട്ടിലേക്ക് ചേർക്കുക</h3>
                                <p class="text-gray-700 mb-3">നിങ്ങൾക്ക് ഇഷ്ടമായ ഒരു ഉൽപ്പന്നം കണ്ടാൽ "കാർട്ടിലേക്ക് ചേർക്കുക" ബട്ടൺ ക്ലിക്ക് ചെയ്യുക. ആയത് നിങ്ങളുടെ ഷോപ്പിംഗ് കാർട്ടിലേക്ക് ചേർക്കപ്പെടും, ഒരു സ്ഥിരീകരണ സന്ദേശവും കാണാം.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>ശ്രദ്ധിക്കുക:</strong> നിങ്ങൾക്ക് ഒരേ ഉൽപ്പന്നത്തിന്റെ ഒന്നിലധികം അളവുകൾ അല്ലെങ്കിൽ വ്യത്യസ്ത ഉൽപ്പന്നങ്ങൾ കാർട്ടിലേക്ക് ചേർക്കാം.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">3</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">നിങ്ങളുടെ കാർട്ട് കാണുക</h3>
                                <p class="text-gray-700 mb-3">നിങ്ങളുടെ ഷോപ്പിംഗ് കാർട്ട് കാണാൻ ഹെഡറിലെ കാർട്ട് ഐക്കണിൽ ക്ലിക്ക് ചെയ്യുക. ഇവിടെ നിങ്ങൾ ചേർത്ത എല്ലാ ഇനങ്ങളും കാണാം, അളവുകൾ മാറ്റാം, അല്ലെങ്കിൽ ഇനങ്ങൾ നീക്കം ചെയ്യാം.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>സവിശേഷതകൾ:</strong> +, - ബട്ടണുകൾ ഉപയോഗിച്ച് അളവുകൾ അപ്ഡേറ്റ് ചെയ്യുക, X ബട്ടൺ ഉപയോഗിച്ച് ഇനങ്ങൾ നീക്കം ചെയ്യുക, നിങ്ങളുടെ മൊത്തം ഓർഡർ മൂല്യം കാണുക.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">4</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">ബന്ധപ്പെടൽ വിവരങ്ങൾ നൽകുക</h3>
                                <p class="text-gray-700 mb-3">കാർട്ട് പേജിൽ, നിങ്ങൾക്ക് ഒരു ബന്ധപ്പെടൽ വിവര ഫോം കാണാം. നിങ്ങളുടെ പേര്, ഫോൺ നമ്പർ, പൂർണ്ണമായ ഷിപ്പിംഗ് വിലാസം എന്നിവ ഉൾപ്പെടെയുള്ള എല്ലാ ആവശ്യമായ ഫീൽഡുകളും പൂരിപ്പിക്കുക.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>ആവശ്യമായ ഫീൽഡുകൾ:</strong> ആദ്യ പേര്, രാജ്യം, തെരുവ് വിലാസം, നഗരം, സംസ്ഥാനം, പിൻ കോഡ്, ഫോൺ നമ്പർ.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">5</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">വാട്സ്ആപ്പ് വഴി ചെക്കൗട്ട്</h3>
                                <p class="text-gray-700 mb-3">"വാട്സ്ആപ്പ് വഴി ചെക്കൗട്ട്" ബട്ടൺ ക്ലിക്ക് ചെയ്യുക. ഇത് നിങ്ങളുടെ ഓർഡർ വിവരങ്ങളും ബന്ധപ്പെടൽ വിവരങ്ങളും ഉൾപ്പെടെയുള്ള മുൻകൂട്ടി പൂരിപ്പിച്ച സന്ദേശവുമായി വാട്സ്ആപ്പ് തുറക്കും.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>ഉൾപ്പെടുത്തിയിരിക്കുന്നത്:</strong> ഉൽപ്പന്ന വിവരങ്ങൾ, അളവുകൾ, വിലകൾ, നിങ്ങളുടെ ബന്ധപ്പെടൽ വിവരങ്ങൾ, ഷിപ്പിംഗ് വിലാസം.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 6 -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm font-medium">6</div>
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-2">നിങ്ങളുടെ ഓർഡർ പൂർത്തിയാക്കുക</h3>
                                <p class="text-gray-700 mb-3">വാട്സ്ആപ്പ് സന്ദേശം ഞങ്ങളുടെ ടീമിലേക്ക് അയയ്ക്കുക. ഞങ്ങൾ നിങ്ങളുടെ ഓർഡർ അവലോകനം ചെയ്ത് വിവരങ്ങൾ സ്ഥിരീകരിക്കാൻ, പേയ്മെന്റ് ഓപ്ഷനുകൾ ചർച്ച ചെയ്യാൻ, ഡെലിവറി ക്രമീകരിക്കാൻ നിങ്ങളെ ബന്ധപ്പെടും.</p>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600"><strong>അടുത്ത ഘട്ടങ്ങൾ:</strong> ഞങ്ങൾ നിങ്ങളുടെ ഓർഡർ സ്ഥിരീകരിക്കും, പേയ്മെന്റ് രീതികൾ ചർച്ച ചെയ്യും, ഡെലിവറി സമയക്രമം നൽകും.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white rounded-lg shadow-sm border p-8">
                    <h2 class="text-2xl font-light text-primary mb-6">പ്രധാന വിവരങ്ങൾ</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-primary mb-3">പേയ്മെന്റ് ഓപ്ഷനുകൾ</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li>• ഡെലിവറിയിൽ പണം (COD)</li>
                                <li>• ബാങ്ക് ട്രാൻസ്ഫർ</li>
                                <li>• UPI പേയ്മെന്റ്</li>
                                <li>• ക്രെഡിറ്റ്/ഡെബിറ്റ് കാർഡുകൾ</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-primary mb-3">ഡെലിവറി വിവരങ്ങൾ</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li>• Rs. 500-ന് മുകളിൽ ഓർഡറുകൾക്ക് സൗജന്യ ഷിപ്പിംഗ്</li>
                                <li>• സ്റ്റാൻഡേർഡ് ഡെലിവറി: 3-5 ബിസിനസ് ദിവസങ്ങൾ</li>
                                <li>• എക്സ്പ്രസ് ഡെലിവറി ലഭ്യമാണ്</li>
                                <li>• സുരക്ഷിത പാക്കേജിംഗ് ഗ്യാരന്റി</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Custom JavaScript -->
    <script src="assets/js/script.js"></script>

    <script>
        function showLanguage(language) {
            // Hide all language content
            document.querySelectorAll('.language-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all buttons
            document.querySelectorAll('.language-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-primary', 'text-white');
                btn.classList.add('text-gray-600', 'hover:text-primary');
            });

            // Show selected language content
            document.getElementById(language + '-content').classList.remove('hidden');

            // Add active class to selected button
            const activeBtn = document.getElementById(language + '-btn');
            activeBtn.classList.add('active', 'bg-primary', 'text-white');
            activeBtn.classList.remove('text-gray-600', 'hover:text-primary');
        }

        // Initialize with English
        document.addEventListener('DOMContentLoaded', function() {
            showLanguage('english');
        });
    </script>

    <style>
        .language-btn.active {
            background-color: #5A3E28;
            color: white;
        }

        .language-btn:not(.active) {
            color: #6B7280;
        }

        .language-btn:not(.active):hover {
            color: #5A3E28;
        }
    </style>
</body>

</html>