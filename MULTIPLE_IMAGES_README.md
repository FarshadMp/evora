# Multiple Image Support for ÉVORA Jewelry Website

## Overview

The ÉVORA jewelry website now supports multiple images per product, allowing admins to upload 2-5 images (or more) for each product. This enhances the shopping experience by providing customers with multiple views of each jewelry item.

## Features

### ✅ **Multiple Image Upload**

- Admins can upload 2-5 additional images per product
- Support for JPG, PNG, GIF, and WebP formats
- Automatic image optimization and storage

### ✅ **Image Gallery Display**

- Dynamic image carousel on product detail pages
- Thumbnail navigation for desktop
- Dot navigation for mobile devices
- Smooth transitions between images

### ✅ **Admin Management**

- Dedicated Product Image Manager page
- Add/remove images for existing products
- Image ordering and organization
- Visual preview of all product images

### ✅ **Database Structure**

- New `product_images` table for additional images
- Maintains existing `image_main` and `image_hover` fields
- Foreign key relationships with cascade delete

## Database Schema

### New Table: `product_images`

```sql
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(500) NOT NULL,
    image_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

### Existing Table: `products` (unchanged)

- `image_main` - Primary product image
- `image_hover` - Hover/alternative image

## File Structure

```
admin/
├── product-manager.php          # Enhanced with multiple image upload
├── product-image-manager.php    # New dedicated image management page
└── product-images-api.php       # API for image operations

includes/
└── product-functions.php        # Enhanced with image management functions

config/
└── database.php                 # Updated with new table creation
```

## Admin Usage

### 1. Adding Products with Multiple Images

1. Go to **Admin → Product Manager**
2. Fill in product details
3. Upload main image (required)
4. Upload hover image (optional)
5. Add additional images (2-5 recommended)
   - Click "+ Add Another Image" to add more
   - Remove unwanted images with the X button
6. Submit the form

### 2. Managing Images for Existing Products

1. Go to **Admin → Product Manager**
2. Click "Images" link next to any product
3. Or go directly to **Admin → Image Manager**
4. Select a product from the dropdown
5. View current images
6. Add new images or delete existing ones

### 3. Image Management Features

- **Add Images**: Upload new images for any product
- **Delete Images**: Remove additional images (main/hover images protected)
- **View Order**: See the display order of images
- **Preview**: Visual preview of all images

## Frontend Display

### Product Detail Page Gallery

The product detail page now displays:

1. **Main Image** (first in gallery)
2. **Hover Image** (if different from main)
3. **Additional Images** (in order of upload)

### Gallery Features

- **Desktop**: Thumbnail navigation on the left
- **Mobile**: Dot navigation below the main image
- **Responsive**: Adapts to different screen sizes
- **Smooth Transitions**: CSS transitions between images
- **Zoom Indicator**: Click to zoom functionality (placeholder)

## API Endpoints

### Product Images API (`admin/product-images-api.php`)

- `POST /admin/product-images-api.php`
  - `action=add_image` - Add new image
  - `action=delete_image` - Delete image
  - `action=update_order` - Reorder images
  - `action=get_images` - Get all images for product

## Functions Added

### New Functions in `includes/product-functions.php`

```php
// Get additional images for a product
getProductImages($productId)

// Add new image to product
addProductImage($productId, $imagePath, $imageOrder)

// Delete image from product
deleteProductImage($imageId)

// Update image display order
updateProductImageOrder($imageId, $newOrder)

// Get all images (main, hover, additional)
getAllProductImages($productId)
```

## Image Storage

### File Structure

```
images/
└── products/
    ├── product_main_[timestamp].jpg      # Main images
    ├── product_hover_[timestamp].png     # Hover images
    └── product_additional_[timestamp]_[index].jpg  # Additional images
```

### File Naming Convention

- **Main Images**: `product_main_[timestamp].[ext]`
- **Hover Images**: `product_hover_[timestamp].[ext]`
- **Additional Images**: `product_additional_[timestamp]_[index].[ext]`

## Security Features

- **File Type Validation**: Only JPG, PNG, GIF, WebP allowed
- **Admin Authentication**: Session-based admin access
- **File Size Limits**: Server-side validation
- **Unique Filenames**: Timestamp + unique ID to prevent conflicts
- **Directory Permissions**: Proper file permissions for uploads

## Browser Compatibility

- **Modern Browsers**: Full support for all features
- **Mobile Devices**: Responsive design with touch support
- **Image Formats**: WebP support for modern browsers, fallback to JPG/PNG

## Performance Considerations

- **Image Optimization**: Consider implementing image compression
- **Lazy Loading**: Images load as needed
- **Caching**: Browser caching for static images
- **CDN Ready**: Structure supports CDN integration

## Future Enhancements

### Planned Features

- [ ] Image compression and optimization
- [ ] Drag-and-drop reordering
- [ ] Image cropping and editing
- [ ] Bulk image upload
- [ ] Image alt text management
- [ ] Lightbox/zoom functionality
- [ ] Image lazy loading
- [ ] CDN integration

### Technical Improvements

- [ ] Image resizing for different screen sizes
- [ ] WebP conversion for better performance
- [ ] Image metadata extraction
- [ ] Backup and restore functionality

## Troubleshooting

### Common Issues

1. **Images not uploading**

   - Check file permissions on `images/products/` directory
   - Verify file size limits in PHP configuration
   - Ensure valid image format

2. **Images not displaying**

   - Check file paths in database
   - Verify file exists on server
   - Check browser console for errors

3. **Gallery not working**
   - Ensure JavaScript is enabled
   - Check for JavaScript errors in console
   - Verify product has images in database

### Debug Information

Enable error logging in PHP to track issues:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Support

For technical support or feature requests, please refer to the main project documentation or contact the development team.

---

**Version**: 1.0  
**Last Updated**: December 2024  
**Compatibility**: PHP 7.4+, MySQL 5.7+

