# ÉVORA Dynamic Banner Management System

This system allows you to dynamically manage website banners through a web-based admin interface. You can add, edit, delete, and reorder banners without touching any code.

## Features

- ✅ **Dynamic Banner Display**: Banners are loaded from database and displayed on the website
- ✅ **Admin Interface**: Web-based admin panel for managing banners
- ✅ **Image Upload**: Upload banner images directly through the admin interface
- ✅ **Banner Carousel**: Automatic carousel functionality for multiple banners
- ✅ **Sort Order**: Control the display order of banners
- ✅ **Active/Inactive Status**: Enable or disable banners without deleting them
- ✅ **Link URLs**: Add clickable links to banners
- ✅ **Custom Button Text**: Customize call-to-action buttons
- ✅ **Responsive Design**: Works on all device sizes
- ✅ **Fallback System**: Shows default banner if no active banners exist

## Setup Instructions

### 1. Database Setup

The system will automatically create the required database and tables when you first access the admin panel. Make sure you have:

- XAMPP/WAMP/MAMP running
- MySQL server active
- PHP with PDO extension enabled

### 2. File Structure

Ensure the following files are in place:

```
evora/
├── config/
│   └── database.php
├── admin/
│   └── banner-manager.php
├── includes/
│   └── banner-functions.php
├── images/
│   └── banners/
└── index.php (updated)
```

### 3. Database Configuration

Edit `config/database.php` if you need to change database settings:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'evora_banners');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Admin Access

1. Navigate to `http://localhost/evora/admin/banner-manager.php`
2. Login with password: `admin123` (change this in the admin file for security)
3. Start managing your banners!

## How to Use

### Accessing the Admin Panel

**Option 1: Direct URL**

- Go to: `http://localhost/evora/admin/banner-manager.php`

**Option 2: Navigation Menu**

- Click "ADMIN" in the desktop navigation
- Or use the mobile menu → ADMIN → Banner Manager

### Adding a New Banner

1. **Fill in the form:**

   - **Title**: Banner title (displayed as overlay)
   - **Description**: Banner description (displayed as overlay)
   - **Link URL**: Where the banner should link to (optional)
   - **Button Text**: Call-to-action button text (optional)
   - **Sort Order**: Display order (lower numbers appear first)
   - **Banner Image**: Upload your banner image
   - **Active**: Check to enable the banner

2. **Image Requirements:**

   - Recommended size: 1920x600px or similar aspect ratio
   - Supported formats: JPG, PNG, GIF
   - Maximum file size: 10MB

3. **Click "Add Banner"**

### Editing a Banner

1. Click "Edit" next to any banner in the table
2. Modify the fields in the popup modal
3. Click "Update Banner"

### Deleting a Banner

1. Click "Delete" next to any banner (except the default banner)
2. Confirm the deletion
3. The banner image file will also be deleted from the server

### Managing Banner Order

1. Set the "Sort Order" field when adding/editing banners
2. Lower numbers appear first
3. Banners with the same sort order are ordered by creation date

## Banner Display Features

### Single Banner

- If only one active banner exists, it displays as a static banner
- No navigation arrows or dots

### Multiple Banners

- Automatically creates a carousel
- Navigation arrows on left/right
- Dot indicators at the bottom
- Auto-play every 5 seconds
- Click arrows or dots to navigate manually

### Banner Content Overlay

- Title, description, and button text appear as overlay
- Semi-transparent black background for readability
- Responsive text sizing
- Optional call-to-action button

## Security Considerations

### Change Default Password

Edit `admin/banner-manager.php` and change this line:

```php
$admin_password = "admin123"; // Change this to a secure password
```

### File Upload Security

- Only image files are accepted
- Files are renamed with timestamps to prevent conflicts
- Upload directory is restricted to images/banners/

### Database Security

- Uses PDO with prepared statements to prevent SQL injection
- Input validation and sanitization
- Error logging for debugging

## Troubleshooting

### Banner Not Displaying

1. Check if banner is marked as "Active"
2. Verify image file exists in images/banners/
3. Check browser console for JavaScript errors
4. Ensure database connection is working

### Admin Panel Not Loading

1. Verify PHP and MySQL are running
2. Check file permissions on admin directory
3. Ensure config/database.php exists and is accessible
4. Check error logs for specific issues

### Image Upload Issues

1. Verify images/banners/ directory exists and is writable
2. Check file size limits in PHP configuration
3. Ensure proper file permissions (755 for directories, 644 for files)

### Database Connection Issues

1. Verify MySQL server is running
2. Check database credentials in config/database.php
3. Ensure PDO extension is enabled in PHP
4. Check if database exists and is accessible

## Customization

### Styling

- Banner styles are in the banner-functions.php file
- Uses Tailwind CSS classes for styling
- Can be customized to match your brand colors

### Carousel Behavior

- Auto-play interval: 5 seconds (change in banner-functions.php)
- Transition duration: 500ms
- Navigation arrows and dots can be styled

### Database Schema

The banners table structure:

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

## Support

For issues or questions:

1. Check the troubleshooting section above
2. Verify all files are in the correct locations
3. Ensure proper file permissions
4. Check PHP and MySQL error logs

## Future Enhancements

Potential improvements:

- User authentication system
- Banner scheduling (start/end dates)
- A/B testing capabilities
- Analytics tracking
- Multiple banner zones
- Banner templates
- Bulk operations
