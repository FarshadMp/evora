# Shopping Cart System

## Overview

The ÉVORA website now features a complete shopping cart system that allows users to add products to their cart, manage quantities, and proceed to checkout. The system is built with PHP sessions for cart storage and includes AJAX functionality for seamless user experience.

## Features

### Core Cart Functionality

- **Add to Cart**: Add products to cart from product detail pages
- **Cart Management**: View, update quantities, and remove items
- **Cart Persistence**: Cart data persists across browser sessions
- **Real-time Updates**: AJAX-powered cart updates without page refresh
- **Cart Icon**: Visual cart indicator with item count in header

### User Interface

- **Cart Page**: Dedicated cart page with item list and order summary
- **Quantity Controls**: +/- buttons and direct input for quantity changes
- **Order Summary**: Real-time calculation of totals
- **Empty Cart State**: User-friendly empty cart message
- **Responsive Design**: Works on all device sizes

### Cart Operations

- **Add Items**: Add products with quantity
- **Update Quantities**: Change item quantities
- **Remove Items**: Remove individual items
- **Clear Cart**: Remove all items at once
- **Cart Validation**: Prevents adding sold-out items

## File Structure

### New Files Created

- `includes/cart-functions.php` - Core cart functionality
- `cart-api.php` - AJAX API endpoint for cart operations
- `cart.php` - Main cart page
- `CART_SYSTEM_README.md` - This documentation

### Modified Files

- `header.php` - Added cart icon and mobile menu link
- `product-details.php` - Added "Add to Cart" functionality

## Database Integration

The cart system integrates with the existing product database:

- Uses `getProductById()` function to fetch product details
- Validates product availability (SOLD OUT status)
- Stores product information in cart session

## Session Management

### Cart Session Structure

```php
$_SESSION['cart'] = [
    'product_id' => [
        'id' => 1,
        'name' => 'Product Name',
        'price' => 150.00,
        'image' => 'images/products/product.jpg',
        'quantity' => 2,
        'status' => 'NORMAL'
    ]
];
```

### Session Security

- Cart data is stored in PHP sessions
- No sensitive data stored in cart
- Session-based cart persistence

## API Endpoints

### Cart API (`cart-api.php`)

Handles AJAX requests for cart operations:

- **POST /cart-api.php**
  - `action=add&product_id=X&quantity=Y` - Add item to cart
  - `action=update&product_id=X&quantity=Y` - Update item quantity
  - `action=remove&product_id=X` - Remove item from cart
  - `action=clear` - Clear entire cart
  - `action=get_count` - Get cart item count
  - `action=get_total` - Get cart total

### Response Format

```json
{
    "success": true/false,
    "message": "Operation result message",
    "count": 5,  // for get_count action
    "total": 750.00  // for get_total action
}
```

## Usage

### For Users

1. **Adding Items to Cart**:

   - Navigate to any product detail page
   - Click "Add to Cart" button
   - Item is added to cart with visual feedback

2. **Viewing Cart**:

   - Click cart icon in header
   - Or navigate to `/cart.php`

3. **Managing Cart**:

   - Use +/- buttons to change quantities
   - Click remove button to delete items
   - Use "Update Cart" to save changes
   - Use "Clear Cart" to remove all items

4. **Checkout**:
   - Review order summary
   - Click "Proceed to Checkout" (placeholder for future implementation)

### For Developers

1. **Include Cart Functions**:

   ```php
   require_once 'includes/cart-functions.php';
   ```

2. **Add Cart Icon to Header**:

   ```php
   displayCartIcon();
   ```

3. **Get Cart Data**:

   ```php
   $cartItems = getCartItems();
   $cartTotal = getCartTotal();
   $itemCount = getCartItemCount();
   ```

4. **Add to Cart Button**:
   ```html
   <button onclick="addToCart(productId)">Add to Cart</button>
   ```

## JavaScript Functions

### Core Functions

- `addToCart(productId)` - Add product to cart
- `updateQuantity(productId, change, newValue)` - Update item quantity
- `removeItem(productId)` - Remove item from cart
- `updateCartCount()` - Update cart count in header
- `proceedToCheckout()` - Placeholder for checkout functionality

### AJAX Integration

All cart operations use fetch API for seamless updates:

- No page refresh required
- Real-time cart count updates
- Error handling with user feedback

## Styling

### Cart Icon

- Shopping bag icon with item count badge
- Red badge for items > 0
- Hover effects and transitions
- Responsive design

### Cart Page

- Clean, modern design matching site theme
- Sticky order summary sidebar
- Responsive grid layout
- Consistent with ÉVORA brand colors

## Error Handling

### Validation

- Product availability check
- Quantity validation (minimum 1)
- Session validation
- Database connection error handling

### User Feedback

- Success/error messages
- Loading states for buttons
- Confirmation dialogs for destructive actions
- Visual feedback for cart updates

## Future Enhancements

### Planned Features

- **Checkout System**: Complete checkout process
- **Payment Integration**: Payment gateway integration
- **Cart Persistence**: Database storage for logged-in users
- **Wishlist**: Save items for later
- **Cart Abandonment**: Email reminders for abandoned carts
- **Inventory Management**: Real-time stock checking
- **Discount Codes**: Coupon and promotion system
- **Shipping Calculator**: Real-time shipping cost calculation

### Technical Improvements

- **Cart Database Table**: Store cart data in database
- **User Accounts**: Link carts to user accounts
- **Cart Expiration**: Automatic cart cleanup
- **Analytics**: Cart abandonment tracking
- **Performance**: Cart data caching

## Security Considerations

### Current Security

- Session-based cart storage
- Input validation and sanitization
- SQL injection prevention
- XSS prevention with HTML escaping

### Recommended Security

- CSRF protection for cart operations
- Rate limiting for cart API
- Input validation on server side
- Secure session management

## Troubleshooting

### Common Issues

1. **Cart Not Updating**:

   - Check browser console for JavaScript errors
   - Verify cart-api.php is accessible
   - Check session configuration

2. **Items Not Adding**:

   - Verify product exists in database
   - Check product status (not SOLD OUT)
   - Ensure JavaScript is enabled

3. **Cart Count Not Showing**:
   - Check if cart functions are included
   - Verify cart count element exists
   - Check AJAX response format

### Debug Mode

Enable debug mode by adding to cart-api.php:

```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Support

For technical support or questions about the cart system:

- Check browser console for JavaScript errors
- Verify PHP error logs
- Test cart API endpoints directly
- Ensure all required files are present

The cart system is designed to be robust and user-friendly while maintaining the luxury aesthetic of the ÉVORA brand.
