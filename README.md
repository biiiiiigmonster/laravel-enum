# laravel enum helper base php version 8.1

[![Latest Version on Packagist](https://img.shields.io/packagist/v/biiiiiigmonster/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/biiiiiigmonster/laravel-enum)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/biiiiiigmonster/laravel-enum/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/biiiiiigmonster/laravel-enum/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/biiiiiigmonster/laravel-enum/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/biiiiiigmonster/laravel-enum/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Coverage Status](https://coveralls.io/repos/github/biiiiiigmonster/laravel-enum/badge.svg?branch=main)](https://coveralls.io/github/biiiiiigmonster/laravel-enum?branch=main)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/biiiiiigmonster/laravel-enum.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/biiiiiigmonster/laravel-enum/)
[![Total Downloads](https://img.shields.io/packagist/dt/biiiiiigmonster/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/biiiiiigmonster/laravel-enum)

Enum helper for laravel based on the enum feature of php 8.1.

## Installation

You can install the package via composer:

```bash
composer require biiiiiigmonster/laravel-enum
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

## Credits

- [Yunfeng Lu](https://github.com/biiiiiigmonster)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
