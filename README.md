# Livewire SIWE Button (Sign In With Ethereum)

A Laravel Livewire button for authenticating users with Ethereum wallets using the SIWE (Sign In With Ethereum) protocol.

## Features

-   Easy integration with Laravel's authentication system
-   Secure verification of Ethereum signatures
-   Automatic user creation based on wallet address
-   Configurable redirect URLs and Ethereum chain IDs
-   Customizable user registration data

## Requirements

-   PHP 8.2 or higher
-   Laravel 12.x
-   Livewire 3.x
-   AppKit Project ID from [reown.xyz](https://reown.xyz)

## Installation

You can install the package via composer:

```bash
composer require scriptoshi/livewire-siwe
```

Then publish the package assets and configuration:

```bash
php artisan vendor:publish --provider="Scriptoshi\LivewireSiwe\LivewireSiweServiceProvider"
```

After publishing the assets, install the required dependencies:

```bash
npm install
```

## Configuration

After publishing the package assets, you can find the configuration file at `config/livewire-siwe.php`. You need to set your AppKit project ID and other configuration options:

```php
// In your .env file
APPKIT_PROJECT_ID=your-appkit-project-id
SIWE_CHAIN_ID=1 # Ethereum Mainnet
SIWE_REDIRECT_URL=/dashboard
```

## Database Setup

Your User model must have an `address` column to store the Ethereum address. You can create a migration like this:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('address')->nullable()->unique();
});
```

## Usage

Once installed, you can use the SIWE login component in your login/register blade view:

```blade
<livewire:siwe-login />
```

Or with Volt:

```php
<x-volt::siwe-login />
```

## JavaScript Setup

1. Import the SIWE module in your `resources/js/app.js` file:

```javascript
// Import the SIWE module
import { createSiwe } from "../vendor/scriptoshi/livewire-siwe/js/siwe.js";
createSiwe();
```

2. Make sure you include your app.js in your layout:

```blade
@vite(['resources/js/app.js'])
```

That's it! The SIWE functionality will be available on pages where the component is used.

## Customizing User Registration

You can customize how users are registered by defining a callback in your `AppServiceProvider`:

```php
// In AppServiceProvider.php
public function boot()
{
    config(['livewire-siwe.user_data_callback' => function ($address, $userData) {
        // Customize user data here
        $userData['name'] = 'ETH User: ' . substr($address, 0, 6);
        return $userData;
    }]);
}
```

## Dependencies

This package requires the following JavaScript dependencies in your frontend:

-   @reown/appkit
-   @reown/appkit-adapter-ethers
-   @reown/appkit-siwe

These dependencies are included in the package.json and will be installed when you run `npm install`.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
