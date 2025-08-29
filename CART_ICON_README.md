# Cart Icon System Documentation

## Overview

The cart icon system ensures that the shopping cart icon with item count is displayed consistently across all pages of the ÉVORA website.

## How It Works

### 1. Cart Icon Display

- The cart icon is implemented in `header.php` and includes the cart item count
- It uses the `displayCartIcon()` function from `includes/cart-functions.php`
- The icon has CSS classes `cart-icon` and `cart-badge` for styling and JavaScript targeting

### 2. Dynamic Updates

- JavaScript function `updateCartIcon()` in `assets/js/script.js` fetches the current cart count via AJAX
- The cart icon updates automatically when:
  - Page loads
  - Every 5 seconds (to keep in sync)
  - When cart operations are performed (add, remove, update)

### 3. Cross-Page Synchronization

- Uses `localStorage` to notify other tabs/windows when cart is updated
- `triggerCartUpdate()` function updates the icon and notifies other pages
- Storage event listener updates the icon when changes are detected

## Files Involved

### Core Files

- `header.php` - Contains the cart icon HTML
- `includes/cart-functions.php` - Cart functionality and icon display
- `assets/js/script.js` - JavaScript for dynamic updates
- `assets/css/styles.css` - Cart icon styling
- `cart-api.php` - AJAX endpoint for cart operations

### Pages That Include Cart Icon

All main pages include the header and therefore display the cart icon:

- `index.php` (Home)
- `bestsellers.php`
- `new-arrivals.php`
- `product-details.php`
- `category.php`
- `cart.php`
- `contact.php`
- `how-to-order.php`
- `jewelry-care-guide.php`
- `terms-of-service.php`

## CSS Classes

### `.cart-icon`

- Applied to the cart icon link
- Ensures proper positioning and display
- Target for JavaScript updates

### `.cart-badge`

- Applied to the cart count badge
- Styled as a red circle with white text
- Positioned absolutely over the cart icon

## JavaScript Functions

### `updateCartIcon()`

- Fetches current cart count from `cart-api.php`
- Updates the cart badge with current count
- Removes badge if cart is empty

### `triggerCartUpdate()`

- Calls `updateCartIcon()`
- Notifies other tabs/windows via `localStorage`
- Used after cart operations

## Testing

### Test Page

Use `test-cart-icon.php` to test the cart icon functionality:

- Add test items to cart
- Clear cart
- Navigate between pages to verify icon updates
- Test cross-tab synchronization

### Manual Testing

1. Add items to cart from product pages
2. Navigate to different pages
3. Verify cart count is displayed correctly
4. Test cart operations (add, remove, update)
5. Check cross-tab synchronization

## Troubleshooting

### Cart Icon Not Showing

1. Check if `header.php` is included on the page
2. Verify `includes/cart-functions.php` exists and is accessible
3. Check browser console for JavaScript errors
4. Ensure `assets/js/script.js` is loaded

### Cart Count Not Updating

1. Check if `cart-api.php` is accessible
2. Verify AJAX requests are working
3. Check browser network tab for failed requests
4. Ensure session is working properly

### Styling Issues

1. Check if `assets/css/styles.css` is loaded
2. Verify CSS classes are applied correctly
3. Check for CSS conflicts with other styles

## Browser Compatibility

- Modern browsers with ES6 support
- Requires JavaScript enabled
- Uses `localStorage` for cross-tab communication
- Fallback cart icon if JavaScript is disabled

## Performance Considerations

- Cart count updates every 5 seconds
- AJAX requests are lightweight
- CSS uses efficient selectors
- Minimal DOM manipulation
