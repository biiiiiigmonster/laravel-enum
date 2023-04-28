# laravel enum helper base php version 8.1

[![Latest Version on Packagist](https://img.shields.io/packagist/v/biiiiiigmonster/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/biiiiiigmonster/laravel-enum)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/biiiiiigmonster/laravel-enum/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/biiiiiigmonster/laravel-enum/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/biiiiiigmonster/laravel-enum/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/biiiiiigmonster/laravel-enum/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/biiiiiigmonster/laravel-enum.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/biiiiiigmonster/laravel-enum/)
[![Total Downloads](https://img.shields.io/packagist/dt/biiiiiigmonster/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/biiiiiigmonster/laravel-enum)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-enum.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-enum)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require biiiiiigmonster/laravel-enum
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-enum-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-enum-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-enum-views"
```

## Usage

```php
$laravelEnum = new BiiiiiigMonster\\LaravelEnum\LaravelEnum();
echo $laravelEnum->echoPhrase('Hello, BiiiiiigMonster\\LaravelEnum!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [陆云峰](https://github.com/biiiiiigmonster)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
