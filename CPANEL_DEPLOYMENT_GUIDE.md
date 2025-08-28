# cPanel Deployment Guide for ÉVORA Luxury Jewelry Website

## Overview

This guide will help you deploy the ÉVORA Luxury Jewelry website to cPanel hosting. The main issue is that the current database configuration uses XAMPP-specific settings that don't work on cPanel.

## Step 1: Database Setup in cPanel

### 1.1 Create Database

1. Log into your cPanel account
2. Go to **MySQL Databases**
3. Create a new database:
   - Database name: `yourusername_evora_banners` (replace `yourusername` with your cPanel username)
   - Note down the full database name

### 1.2 Create Database User

1. In the same MySQL Databases section
2. Create a new user:
   - Username: `yourusername_evora_user` (replace `yourusername` with your cPanel username)
   - Password: Create a strong password
   - Note down the username and password

### 1.3 Assign User to Database

1. Add the user to the database with **ALL PRIVILEGES**
2. This will create the full database name and username

## Step 2: Update Database Configuration

### 2.1 Replace Database File

1. **Rename** the current `config/database.php` to `config/database-local.php` (for local development)
2. **Rename** `config/database-cpanel.php` to `config/database.php` (for cPanel)

### 2.2 Update Database Credentials

Edit `config/database.php` and update these lines:

```php
// Update these values with your cPanel database details
define('DB_HOST', 'localhost');
define('DB_NAME', 'yourusername_evora_banners'); // Your full database name
define('DB_USER', 'yourusername_evora_user');    // Your database username
define('DB_PASS', 'your_database_password');     // Your database password
```

**Example:**

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'john_evora_banners');
define('DB_USER', 'john_evora_user');
define('DB_PASS', 'MySecurePassword123!');
```

## Step 3: Upload Files to cPanel

### 3.1 File Upload

1. Go to **File Manager** in cPanel
2. Navigate to `public_html` (or your domain's root directory)
3. Upload all website files to this directory

### 3.2 File Structure

Your cPanel file structure should look like this:

```
public_html/
├── admin/
├── assets/
├── config/
├── images/
├── includes/
├── index.php
├── cart.php
├── product-details.php
├── how-to-order.php
└── ... (other files)
```

## Step 4: Set File Permissions

### 4.1 Directory Permissions

Set these directories to **755**:

- `images/products/`
- `images/banners/`
- `images/category/`

### 4.2 File Permissions

Set these files to **644**:

- All `.php` files
- All `.css` files
- All `.js` files

## Step 5: Test the Website

### 5.1 Initial Test

1. Visit your domain: `https://yourdomain.com`
2. The website should load without database errors
3. Check if the database tables are created automatically

### 5.2 Admin Panel Test

1. Visit: `https://yourdomain.com/admin/`
2. Login with password: `admin123`
3. Test adding a product or banner

## Step 6: Common Issues and Solutions

### 6.1 Database Connection Error

**Error:** "Connection failed: SQLSTATE[HY000] [2002] Can't connect to local server"
**Solution:**

- Verify database credentials in `config/database.php`
- Ensure database user has proper privileges
- Check if database name includes your cPanel username prefix

### 6.2 Permission Denied Error

**Error:** "Permission denied" when uploading images
**Solution:**

- Set `images/` directory permissions to 755
- Ensure PHP has write permissions to upload directories

### 6.3 File Not Found Error

**Error:** "404 Not Found" for images or CSS
**Solution:**

- Verify all files are uploaded to correct directories
- Check file paths in the code
- Ensure case sensitivity matches (Linux servers are case-sensitive)

## Step 7: Security Recommendations

### 7.1 Change Admin Password

1. Go to `admin/product-manager.php`
2. Find this line: `$admin_password = "admin123";`
3. Change to a strong password

### 7.2 Secure Database

1. Use a strong database password
2. Consider using environment variables for database credentials
3. Regularly backup your database

### 7.3 File Permissions

1. Set sensitive files to 600 or 644
2. Ensure upload directories are writable but secure

## Step 8: Post-Deployment Checklist

- [ ] Website loads without errors
- [ ] Database tables created successfully
- [ ] Admin panel accessible and functional
- [ ] Image uploads working
- [ ] Cart system functional
- [ ] WhatsApp checkout working
- [ ] All pages loading correctly
- [ ] Mobile responsiveness working
- [ ] SSL certificate active (if applicable)

## Step 9: Backup Strategy

### 9.1 Database Backup

1. Use cPanel's **phpMyAdmin** to export database
2. Set up automatic backups if available
3. Keep regular backups of your database

### 9.2 File Backup

1. Download all website files regularly
2. Keep backups of configuration files
3. Document any custom changes made

## Support

If you encounter issues:

1. Check cPanel error logs
2. Verify database credentials
3. Test with a simple PHP file first
4. Contact your hosting provider if needed

## Files Modified for cPanel

1. **`config/database.php`** - Updated for cPanel compatibility
2. **Database credentials** - Need to be updated with your cPanel details
3. **File permissions** - May need adjustment for server security

---

**Note:** Always test the website thoroughly after deployment and keep backups of your local development version.
