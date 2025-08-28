# ÉVORA Dynamic Product Management System

## Overview

This system provides a complete dynamic product management solution for the ÉVORA luxury jewelry website. It allows administrators to manage products, banners, and content through a web-based admin interface.

## Features

### 🛍️ Product Management
- **Add/Edit/Delete Products**: Full CRUD operations for products
- **Product Categories**: Organize products by categories (rings, necklaces, bracelets, etc.)
- **Status Control**: Set product status (NEW, SOLD OUT, REFILL, NORMAL)
- **Best Sellers & New Arrivals**: Mark products as bestsellers or new arrivals
- **Image Management**: Upload main and hover images for products
- **Pricing**: Set current price and original price for discount display
- **Sort Order**: Control product display order

### 🎨 Banner Management
- **Dynamic Banners**: Manage website banners and sliders
- **Image Upload**: Upload banner images with automatic resizing
- **Link Management**: Set banner links and button text
- **Active/Inactive**: Toggle banner visibility
- **Sort Order**: Control banner display order

### 🌐 Frontend Integration
- **Dynamic Best Sellers**: Automatically displays products marked as bestsellers
- **Dynamic New Arrivals**: Shows products marked as new arrivals
- **Product Filtering**: Filter products by category
- **Product Sorting**: Sort by price, newest, best-selling, etc.
- **Dynamic Product Details**: Individual product pages with related products
- **Status Badges**: Visual indicators for NEW, SOLD OUT, REFILL status

## File Structure

```
evora/
├── admin/
│   ├── index.php              # Admin dashboard
│   ├── banner-manager.php     # Banner management interface
│   └── product-manager.php    # Product management interface
├── config/
│   └── database.php           # Database configuration and initialization
├── includes/
│   ├── banner-functions.php   # Banner display functions
│   └── product-functions.php  # Product display functions
├── images/
│   ├── banners/              # Banner images
│   └── products/             # Product images
├── index.php                 # Homepage with dynamic sections
├── bestsellers.php          # Product listing page
├── product-details.php      # Individual product page
└── README.md                # This file
```

## Database Schema

### Products Table
```sql
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2),
    category VARCHAR(100) NOT NULL,
    status ENUM('NEW', 'SOLD OUT', 'REFILL', 'NORMAL') DEFAULT 'NORMAL',
    image_main VARCHAR(500) NOT NULL,
    image_hover VARCHAR(500),
    is_bestseller BOOLEAN DEFAULT FALSE,
    is_new_arrival BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Banners Table
```sql
CREATE TABLE banners (
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
);
```

## Installation & Setup

### 1. Database Setup
1. Ensure you have MySQL/MariaDB installed
2. Update database credentials in `config/database.php`
3. The system will automatically create tables and sample data on first run

### 2. File Permissions
Ensure the following directories are writable:
```bash
chmod 755 images/banners/
chmod 755 images/products/
```

### 3. Admin Access
- **URL**: `http://yourdomain.com/admin/`
- **Default Password**: `admin123`
- **Security**: Change the password in admin files for production use

## Usage Guide

### Admin Dashboard
Access the admin dashboard at `/admin/` to:
- View statistics (total products, banners, bestsellers, new arrivals)
- Navigate to banner or product management
- Quick access to common actions

### Product Management
1. **Add Product**:
   - Fill in product details (name, description, price)
   - Select category and status
   - Upload main image (required) and hover image (optional)
   - Set flags for bestseller/new arrival
   - Set sort order

2. **Edit Product**:
   - Click "Edit" on any product
   - Modify details in the popup modal
   - Save changes

3. **Delete Product**:
   - Click "Delete" on any product
   - Confirm deletion (images will be removed from server)

### Banner Management
1. **Add Banner**:
   - Upload banner image
   - Set title, description, and link
   - Configure button text and sort order

2. **Edit/Delete**: Similar to product management

### Status Management
- **NEW**: Yellow badge, indicates new arrivals
- **SOLD OUT**: Red badge, disables purchase buttons
- **REFILL**: Green badge, indicates restocking
- **NORMAL**: No badge, standard product

## Frontend Features

### Dynamic Sections
- **Best Sellers**: Automatically populated from products marked as bestsellers
- **New Arrivals**: Shows products marked as new arrivals
- **Product Grid**: Displays all active products with filtering and sorting

### Product Details Page
- Individual product pages with full details
- Related products from same category
- Status badges and pricing information
- Responsive design

### Filtering & Sorting
- **Categories**: Filter by product category
- **Sorting**: By price (low/high), newest, best-selling, featured
- **URL Parameters**: Filters persist in URL for sharing/bookmarking

## Security Considerations

### Production Deployment
1. **Change Default Password**: Update `admin123` in all admin files
2. **Secure File Uploads**: Implement additional validation for uploaded images
3. **Database Security**: Use strong database passwords
4. **HTTPS**: Enable SSL for admin access
5. **Input Validation**: Add additional validation for user inputs

### File Upload Security
- Only image files are accepted
- Files are renamed with timestamps
- Upload directory permissions are restricted

## Customization

### Adding New Categories
1. Update the category options in `admin/product-manager.php`
2. Add corresponding filter buttons in `bestsellers.php`
3. Update category display logic in `includes/product-functions.php`

### Styling
- The system uses Tailwind CSS for styling
- Custom styles are in `assets/css/styles.css`
- Status badges can be customized in `includes/product-functions.php`

### Status Badges
Modify the `getStatusBadge()` function in `includes/product-functions.php` to change badge styling.

## Troubleshooting

### Common Issues
1. **Images Not Uploading**: Check directory permissions
2. **Database Connection**: Verify database credentials
3. **Products Not Showing**: Ensure products are marked as active
4. **Admin Access**: Verify password and session settings

### Error Logging
Check your server's error logs for detailed error messages. The system logs errors for:
- Database operations
- File uploads
- Product operations

## Support

For technical support or customization requests, please refer to the code comments and this documentation. The system is designed to be self-contained and easily maintainable.

## Version History

- **v1.0**: Initial release with product and banner management
- Dynamic bestsellers and new arrivals sections
- Admin dashboard with statistics
- Complete CRUD operations for products and banners
