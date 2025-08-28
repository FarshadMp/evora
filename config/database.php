<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'evora_banners');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection without database first
try {
    // Use socket for XAMPP on macOS
    $pdo = new PDO("mysql:host=" . DB_HOST . ";unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to create database and tables if they don't exist
function initializeDatabase()
{
    global $pdo;

    try {
        // Create database if it doesn't exist
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        $pdo->exec("USE " . DB_NAME);

        // Create banners table
        $sql = "CREATE TABLE IF NOT EXISTS banners (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image_path VARCHAR(500) NOT NULL,
            link_url VARCHAR(500),
            button_text VARCHAR(100),
            is_active BOOLEAN DEFAULT TRUE,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $pdo->exec($sql);

        // Create categories table
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            image_path VARCHAR(500),
            is_active BOOLEAN DEFAULT TRUE,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $pdo->exec($sql);

        // Create products table
        $sql = "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            price DECIMAL(10,2) NOT NULL,
            original_price DECIMAL(10,2) NULL,
            category VARCHAR(100) NOT NULL,
            status ENUM('NEW', 'SOLD OUT', 'REFILL', 'NORMAL') DEFAULT 'NORMAL',
            image_main VARCHAR(500) NOT NULL,
            image_hover VARCHAR(500) NULL,
            is_bestseller BOOLEAN DEFAULT FALSE,
            is_new_arrival BOOLEAN DEFAULT FALSE,
            is_active BOOLEAN DEFAULT TRUE,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $pdo->exec($sql);

        // Create product_images table for multiple images
        $sql = "CREATE TABLE IF NOT EXISTS product_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            image_path VARCHAR(500) NOT NULL,
            image_order INT DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )";

        $pdo->exec($sql);

        // Insert default banner if table is empty
        $stmt = $pdo->query("SELECT COUNT(*) FROM banners");
        if ($stmt->fetchColumn() == 0) {
            $defaultBanner = "INSERT INTO banners (title, description, image_path, link_url, button_text, is_active, sort_order) 
                             VALUES ('ÉVORA Luxury Jewelry', 'Discover exquisite luxury jewelry at ÉVORA', 'images/banners/banner.jpg', '#', 'SHOP NOW', 1, 1)";
            $pdo->exec($defaultBanner);
        }

        // Insert default categories if table is empty
        $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
        if ($stmt->fetchColumn() == 0) {
            $defaultCategories = [
                "INSERT INTO categories (name, description, image_path, is_active, sort_order) VALUES 
                ('RINGS', 'Exquisite collection of handcrafted rings', 'images/category/cat_1.jpg', 1, 1)",

                "INSERT INTO categories (name, description, image_path, is_active, sort_order) VALUES 
                ('BRACELETS', 'Stylish bracelet collection for every occasion', 'images/category/cat_2.jpg', 1, 2)",

                "INSERT INTO categories (name, description, image_path, is_active, sort_order) VALUES 
                ('NECKLACES', 'Elegant necklaces to complement your style', 'images/category/cat_3.jpg', 1, 3)",

                "INSERT INTO categories (name, description, image_path, is_active, sort_order) VALUES 
                ('EARRINGS', 'Beautiful earrings for every look', 'images/category/cat_4.jpg', 1, 4)",

                "INSERT INTO categories (name, description, image_path, is_active, sort_order) VALUES 
                ('WRIST BANDS', 'Premium wrist bands for a sophisticated look', 'images/category/cat_5.jpg', 1, 5)",

                "INSERT INTO categories (name, description, image_path, is_active, sort_order) VALUES 
                ('ANKLETS', 'Elegant anklet designs for a complete look', 'images/category/cat_6.jpg', 1, 6)",

                "INSERT INTO categories (name, description, image_path, is_active, sort_order) VALUES 
                ('OFFERS', 'Special offers and deals on luxury jewelry', 'images/category/cat_7.jpg', 1, 7)",

                "INSERT INTO categories (name, description, image_path, is_active, sort_order) VALUES 
                ('COMBOS', 'Perfect jewelry combinations for any occasion', 'images/category/cat_8.jpg', 1, 8)"
            ];

            foreach ($defaultCategories as $category) {
                $pdo->exec($category);
            }
        }

        // Insert sample products if table is empty
        $stmt = $pdo->query("SELECT COUNT(*) FROM products");
        if ($stmt->fetchColumn() == 0) {
            $sampleProducts = [
                "INSERT INTO products (name, description, price, original_price, category, status, image_main, image_hover, is_bestseller, is_new_arrival, sort_order) VALUES 
                ('Azalea Multi Stone Bar Necklace', 'Exquisite multi-stone bar necklace', 150.00, 300.00, 'necklaces', 'NEW', 'images/products/pro_1.jpg', 'images/products/pro_2.jpg', 1, 1, 1)",

                "INSERT INTO products (name, description, price, original_price, category, status, image_main, image_hover, is_bestseller, is_new_arrival, sort_order) VALUES 
                ('Diamond Ring Collection', 'Beautiful diamond ring', 150.00, 300.00, 'rings', 'SOLD OUT', 'images/products/pro_2.jpg', 'images/products/pro_3.jpg', 1, 0, 2)",

                "INSERT INTO products (name, description, price, original_price, category, status, image_main, image_hover, is_bestseller, is_new_arrival, sort_order) VALUES 
                ('Elegant Bracelet Set', 'Stylish bracelet collection', 150.00, 300.00, 'bracelets', 'REFILL', 'images/products/pro_3.jpg', 'images/products/pro_4.jpg', 1, 0, 3)",

                "INSERT INTO products (name, description, price, original_price, category, status, image_main, image_hover, is_bestseller, is_new_arrival, sort_order) VALUES 
                ('Pearl Earrings', 'Classic pearl earrings', 150.00, 300.00, 'earrings', 'NEW', 'images/products/pro_4.jpg', 'images/products/pro_5.jpg', 0, 1, 4)",

                "INSERT INTO products (name, description, price, original_price, category, status, image_main, image_hover, is_bestseller, is_new_arrival, sort_order) VALUES 
                ('Luxury Wrist Band', 'Premium wrist band', 150.00, 300.00, 'wrist-bands', 'SOLD OUT', 'images/products/pro_5.jpg', 'images/products/pro_1.jpg', 0, 1, 5)",

                "INSERT INTO products (name, description, price, original_price, category, status, image_main, image_hover, is_bestseller, is_new_arrival, sort_order) VALUES 
                ('Anklet Collection', 'Elegant anklet designs', 150.00, 300.00, 'anklets', 'REFILL', 'images/products/pro_1.jpg', 'images/products/pro_2.jpg', 0, 1, 6)"
            ];

            foreach ($sampleProducts as $product) {
                $pdo->exec($product);
            }
        }

        // Update existing table structure if needed (migration)
        try {
            // Check if original_price column allows NULL
            $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'original_price'");
            $column = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($column && $column['Null'] === 'NO') {
                // Update column to allow NULL
                $pdo->exec("ALTER TABLE products MODIFY COLUMN original_price DECIMAL(10,2) NULL");
            }

            // Check if image_hover column allows NULL
            $stmt = $pdo->query("SHOW COLUMNS FROM products LIKE 'image_hover'");
            $column = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($column && $column['Null'] === 'NO') {
                // Update column to allow NULL
                $pdo->exec("ALTER TABLE products MODIFY COLUMN image_hover VARCHAR(500) NULL");
            }
        } catch (PDOException $e) {
            // Ignore migration errors, table might not exist yet
        }
    } catch (PDOException $e) {
        die("Database initialization failed: " . $e->getMessage());
    }
}

// Initialize database on first run
initializeDatabase();
