# Category Management System

## Overview

The ÉVORA website now features a fully admin-controlled category management system that allows administrators to create, edit, and manage website categories dynamically without touching the code.

## Features

### Admin Panel Integration

- **Category Management Page**: Accessible via `/admin/category-manager.php`
- **Dashboard Integration**: Category management is integrated into the main admin dashboard
- **Statistics**: Category count is displayed in the admin dashboard stats

### Category Management Features

- **Add New Categories**: Create categories with name, description, image, and sort order
- **Edit Categories**: Modify existing category details including images
- **Delete Categories**: Remove categories from the system
- **Active/Inactive Status**: Toggle category visibility
- **Sort Order**: Control the display order of categories
- **Image Upload**: Upload category images with automatic file naming

### Dynamic Frontend Display

- **Automatic Category Grid**: Categories are displayed dynamically on the homepage
- **Dynamic Category Pages**: Single `category.php` page handles all category listings
- **SEO-Friendly URLs**: Category pages use clean URLs (e.g., `/category.php?slug=rings`)
- **Responsive Design**: Category grid adapts to different screen sizes

## Database Structure

### Categories Table

```sql
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(500),
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## File Structure

### New Files Created

- `admin/category-manager.php` - Category management interface
- `includes/category-functions.php` - Category-related PHP functions
- `category.php` - Dynamic category listing page
- `CATEGORY_MANAGEMENT_README.md` - This documentation

### Modified Files

- `config/database.php` - Added categories table creation
- `admin/index.php` - Added category management link and stats
- `index.php` - Updated to use dynamic category display

## Usage

### For Administrators

1. **Access Category Management**:

   - Login to admin panel at `/admin/`
   - Click "Manage Categories" button

2. **Add New Category**:

   - Fill in category name, description, and sort order
   - Upload a category image (recommended: 400x400px)
   - Set active status
   - Click "Add Category"

3. **Edit Category**:

   - Click "Edit" button next to any category
   - Modify details in the popup modal
   - Click "Update Category"

4. **Delete Category**:
   - Click "Delete" button next to any category
   - Confirm deletion

### For Developers

1. **Display Categories on Frontend**:

   ```php
   include 'includes/category-functions.php';
   displayCategoryGrid();
   ```

2. **Get Categories Programmatically**:

   ```php
   $categories = getActiveCategories();
   $category = getCategoryById($id);
   $category = getCategoryByName($name);
   ```

3. **Custom Category Grid**:
   ```php
   displayCategoryGridWithClass('custom-grid-class');
   ```

## URL Structure

### Category Pages

- **Format**: `/category.php?slug=category-name`
- **Examples**:
  - `/category.php?slug=rings`
  - `/category.php?slug=bracelets`
  - `/category.php?slug=necklaces`

### Admin Pages

- **Category Management**: `/admin/category-manager.php`
- **Admin Dashboard**: `/admin/`

## Default Categories

The system automatically creates these default categories on first run:

1. RINGS
2. BRACELETS
3. NECKLACES
4. EARRINGS
5. WRIST BANDS
6. ANKLETS
7. OFFERS
8. COMBOS

## Image Management

### Category Images

- **Location**: `images/category/`
- **Naming**: `cat_[timestamp].[extension]`
- **Formats**: JPG, JPEG, PNG, GIF, WebP
- **Recommended Size**: 400x400px
- **Storage**: Images are stored with timestamp-based names to avoid conflicts

### Image Upload Process

1. File validation for allowed extensions
2. Automatic directory creation if needed
3. Timestamp-based filename generation
4. File moved to category images directory
5. Path stored in database

## Security Features

- **File Upload Validation**: Only allowed image formats accepted
- **SQL Injection Prevention**: Prepared statements used throughout
- **XSS Prevention**: HTML escaping on output
- **Authentication Required**: Admin login required for management

## Error Handling

- **Database Errors**: Logged to error log
- **File Upload Errors**: User-friendly error messages
- **Missing Categories**: Graceful fallback to homepage
- **Invalid URLs**: Automatic redirect to homepage

## Future Enhancements

Potential improvements for the category system:

- Category-specific banners
- Category SEO meta fields
- Category-specific product filters
- Category hierarchy (parent/child categories)
- Category-specific pricing rules
- Category analytics and reporting

## Troubleshooting

### Common Issues

1. **Categories Not Displaying**:

   - Check if categories table exists
   - Verify categories are marked as active
   - Check database connection

2. **Image Upload Fails**:

   - Verify directory permissions (755)
   - Check file size limits
   - Ensure allowed file types

3. **Category Pages Not Working**:
   - Verify .htaccess configuration
   - Check PHP error logs
   - Ensure category-functions.php is included

### Database Reset

To reset categories to defaults:

```sql
DELETE FROM categories;
-- Then refresh the page to trigger auto-creation
```

## Support

For technical support or questions about the category management system, refer to the main project documentation or contact the development team.
