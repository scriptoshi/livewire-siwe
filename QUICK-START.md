# Livewire SIWE Quick Start Guide

This guide will help you quickly set up Sign In With Ethereum in your Laravel application.

## Prerequisites

- Laravel 12+ with Livewire 3 installed
- An AppKit project ID from [reown.xyz](https://reown.xyz)
- Basic knowledge of Ethereum authentication

## Installation Steps

### 1. Install the package

```bash
composer require scriptoshi/livewire-siwe
```

### 2. Publish assets and config

```bash
php artisan vendor:publish --tag=livewire-siwe-config
php artisan vendor:publish --tag=livewire-siwe-migrations
```

### 3. Run migrations

```bash
php artisan migrate
```

> Note: This will add an `address` column to your users table for storing Ethereum wallet addresses.

### 4. Install JavaScript dependencies

```bash
npm install @reown/appkit @reown/appkit-adapter-ethers @reown/appkit-siwe
```

### 5. Configure your AppKit project ID

Add your AppKit project ID to your `.env` file:

```
APPKIT_PROJECT_ID=your-project-id-here
```

### 6. Add SIWE login to your login page

```blade
<livewire:siwe-login />
```

### 7. Import SIWE in your app.js file

```javascript
// resources/js/app.js
import { createSiwe } from "../vendor/scriptoshi/livewire-siwe/js/siwe.js";

// Initialize SIWE when the document is loaded
document.addEventListener('DOMContentLoaded', function() {
    createSiwe();
});
```

### 8. Include your app.js in your layout

```blade
@vite(['resources/js/app.js'])
```

## Testing the Integration

1. Visit your login page
2. Click the "Sign In With Ethereum" button
3. Connect your Ethereum wallet when prompted
4. Sign the message with your wallet
5. You will be logged in and redirected to the dashboard

## Troubleshooting

- **JavaScript errors**: Make sure you've installed the required npm packages
- **Authentication fails**: Check your AppKit project ID is correct
- **Missing wallet UI**: Ensure createSiwe() is being called after page load
- **Database errors**: Confirm your users table has the address column

## Next Steps

- Customize the user registration process
- Change the SIWE button styling
- Implement wallet disconnect functionality
- Add support for multiple blockchain networks

For more detailed information, check the [full documentation](README.md).
