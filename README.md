# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/thotam/thotam-hr.svg?style=flat-square)](https://packagist.org/packages/thotam/thotam-hr)
[![Build Status](https://img.shields.io/travis/thotam/thotam-hr/master.svg?style=flat-square)](https://travis-ci.org/thotam/thotam-hr)
[![Quality Score](https://img.shields.io/scrutinizer/g/thotam/thotam-hr.svg?style=flat-square)](https://scrutinizer-ci.com/g/thotam/thotam-hr)
[![Total Downloads](https://img.shields.io/packagist/dt/thotam/thotam-hr.svg?style=flat-square)](https://packagist.org/packages/thotam/thotam-hr)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require thotam/thotam-hr
```

## Usage

```php
edit Route::middleware(['web', 'auth', 'CheckAccount', 'CheckHr', 'CheckMail']) in App\Providers\RouteServiceProvider
```

```php
Add "hr_key" to fillable of User Models
```

```php
Add "Thotam\ThotamHr\Traits\HasHrTrait" to User Models
```

#### Add CheckHR Middleware

```php
Add 'CheckHr' => Thotam\ThotamHr\Http\Middleware\CheckHR::Class To App\Http\Kernel.php in $routeMiddleware
Add 'CheckInfo' => Thotam\ThotamHr\Http\Middleware\CheckInfo::Class To App\Http\Kernel.php in $routeMiddleware
```

### Add this to .env

```php
DropboxAppkey=""
DropboxAppsecret=""
DropboxRefreshToken=""
```

#### Next, you should migrate your database:

```php
php artisan migrate
```

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email thanhtamtqno1@gmail.com instead of using the issue tracker.

## Credits

-   [thotam](https://github.com/thotam)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
