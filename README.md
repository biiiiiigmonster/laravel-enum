# laravel enum helper base php version 8.1

[![Latest Version on Packagist](https://img.shields.io/packagist/v/biiiiiigmonster/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/biiiiiigmonster/laravel-enum)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/biiiiiigmonster/laravel-enum/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/biiiiiigmonster/laravel-enum/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/biiiiiigmonster/laravel-enum/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/biiiiiigmonster/laravel-enum/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Coverage Status](https://coveralls.io/repos/github/biiiiiigmonster/laravel-enum/badge.svg?branch=main)](https://coveralls.io/github/biiiiiigmonster/laravel-enum?branch=main)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/biiiiiigmonster/laravel-enum.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/biiiiiigmonster/laravel-enum/)
[![Total Downloads](https://img.shields.io/packagist/dt/biiiiiigmonster/laravel-enum.svg?style=flat-square)](https://packagist.org/packages/biiiiiigmonster/laravel-enum)

Enum helper for laravel10 based on the enum feature of php 8.1.

## Installation

You can install the package via composer:

```bash
composer require biiiiiigmonster/laravel-enum
```

## Usage

To get started, enums typically live in the `app\Enums` directory. You may use the `make:enum` Artisan command to generate a new enum:

```shell
php artisan make:enum TaskStatus
```

if you want to generate a [backed](https://www.php.net/manual/en/language.enumerations.backed.php) enum, you may use the `make:enum` Artisan command with `--type` options:

```shell
php artisan make:enum TaskStatus --type=int
```

and also you can apply the trait on your exists enum:

```php
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;

// pure enum.
enum Role
{
    use EnumTraits;

    case ADMINISTRATOR;
    case SUBSCRIBER;
    case GUEST;
}

// backed enum.
enum TaskStatus: int
{
    use EnumTraits;

    case INCOMPLETE = 0;
    case COMPLETED = 1;
    case CANCELED = 2;
}
```

### Invokable

This helper lets you get the primitive value of a backed enum, or the name of a pure enum, by "invoking" it — either statically (`MyEnum::FOO()` instead of `MyEnum::FOO`), or as an instance (`$enum()`).

That way, you can use enums as array keys:
```php
'statuses' => [
    TaskStatus::INCOMPLETE() => ['some configuration'],
    TaskStatus::COMPLETED() => ['other configuration'],
],
```

Or access the underlying primitives for any other use cases:
```php
public function updateStatus(int $status): void;

$task->updateStatus(TaskStatus::COMPLETED());
```

The main point: this is all without having to append `->value` to everything:
```php
TaskStatus::CANCELED; // => TaskStatus instance
TaskStatus::CANCELED(); // => 2
```

#### Use static calls to get the primitive value
```php
TaskStatus::INCOMPLETE(); // 0
TaskStatus::COMPLETED(); // 1
TaskStatus::CANCELED(); // 2
Role::ADMINISTRATOR(); // 'ADMINISTRATOR'
Role::SUBSCRIBER(); // 'SUBSCRIBER'
Role::GUEST(); // 'GUEST'
```

#### Invoke instances to get the primitive value
```php
public function updateStatus(TaskStatus $status, Role $role)
{
    $this->record->setStatus($status(), $role());
}
```

### Enhancement

Helper provide many static methods for you to enhance experience with enums.

#### Names

This helper returns a list of case *names* in the enum.

```php
TaskStatus::names(); // ['INCOMPLETE', 'COMPLETED', 'CANCELED']
Role::names(); // ['ADMINISTRATOR', 'SUBSCRIBER', 'GUEST']
```

#### Values

This helper returns a list of case *values* for backed enums, or a list of case *names* for pure enums (making this functionally equivalent to [`::names()`](#names) for pure Enums)

```php
TaskStatus::values(); // [0, 1, 2]
Role::values(); // ['ADMINISTRATOR', 'SUBSCRIBER', 'GUEST']
```

#### Options

This helper returns an array, that key is each instance invoke `()` return, and value is instance [`->label()`](#labels) returns.

```php
TaskStatus::options(); 
/*
    [
        0 => 'Incomplete', 
        1 => 'Completed', 
        2 => 'Canceled'
    ]
*/
Role::options(); 
/*
    [
        'ADMINISTRATOR' => 'Administrator', 
        'SUBSCRIBER' => 'Subscriber', 
        'GUEST' => 'Guest'
    ]
*/
```

#### Tables

This helper returns a list of case map array that each instance, if instance append attributes that extended [`Meta`](#meta), the map array including more.

```php
TaskStatus::tables(); 
/*
    [
        ['name' => 'INCOMPLETE', 'value' => 0], 
        ['name' => 'COMPLETED', 'value' => 1], 
        ['name' => 'CANCELED', 'value' => 2]
    ]
*/
Role::tables(); 
/*
    [
        ['name' => 'ADMINISTRATOR'], 
        ['name' => 'SUBSCRIBER'], 
        ['name' => 'GUEST']
    ]
*/
```

#### From

This helper adds `from()` and `tryFrom()` to pure enums, and adds `fromName()` and `tryFromName()` to all enums.

**Important Notes**:
* `BackedEnum` instances already implement their own `from()` and `tryFrom()` methods, which will not be overridden by this trait. Attempting to override those methods in a `BackedEnum` causes a fatal error.
* Pure enums only have named cases and not values, so the `from()` and `tryFrom()` methods are functionally equivalent to `fromName()` and `tryFromName()`

##### Use the `from()` method
```php
Role::from('ADMINISTRATOR'); // Role::ADMINISTRATOR
Role::from('NOBODY'); // Error: ValueError
```

##### Use the `tryFrom()` method
```php
Role::tryFrom('GUEST'); // Role::GUEST
Role::tryFrom('NEVER'); // null
```

##### Use the `fromName()` method
```php
TaskStatus::fromName('INCOMPLETE'); // TaskStatus::INCOMPLETE
TaskStatus::fromName('MISSING'); // Error: ValueError
Role::fromName('SUBSCRIBER'); // Role::SUBSCRIBER
Role::fromName('HACKER'); // Error: ValueError
```

##### Use the `tryFromName()` method
```php
TaskStatus::tryFromName('COMPLETED'); // TaskStatus::COMPLETED
TaskStatus::tryFromName('NOTHING'); // null
Role::tryFromName('GUEST'); // Role::GUEST
Role::tryFromName('TESTER'); // null
```

#### Random

This helper returns an instance of case by random.

```php
TaskStatus::random(); // TaskStatus::COMPLETED
Role::random(); // Role::GUEST
```

#### Default Case
Sometimes you may need to specify default case for your enum, which is easy as below: simply append the `#[DefaultCase]` attribute to the case:
```php
use BiiiiiigMonster\LaravelEnum\Concerns\DefaultCase;
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;

enum Role
{
    use EnumTraits;
    
    #[DefaultCase]
    case ADMIN;
    
    case GUEST;
}
```

Then use the `::default()` static method to get this case instance:
```php
Role::default(); // Role::ADMIN

Role::ADMIN->isDefault(); // true
```

### Meta

This feature lets you add metadata to enum cases, it's used by way of attributes.

```php
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use App\Enums\Metas\{Description, Color};

enum TaskStatus: int
{
    use EnumTraits;

    #[Description('Incomplete Task')] #[Color('red')]
    case INCOMPLETE = 0;

    #[Description('Completed Task')] #[Color('green')]
    case COMPLETED = 1;

    #[Description('Canceled Task')] #[Color('gray')]
    case CANCELED = 2;
}
```

#### Creating meta attributes

To generate a new meta attributes, you may use the `make:enumMeta` Artisan command:

```shell
php artisan make:enumMeta Color
```

meta attribute needs to exist as an Attribute.

```php
use BiiiiiigMonster\LaravelEnum\Concerns\Meta;
use Attribute;

#[Attribute]
class Color extends Meta {}

#[Attribute]
class Description extends Meta {}
```

Inside the attribute, you can customize a few things. For instance, you may want to use a different method name than the one derived from the class name (`Description` becomes `description()` by default). To do that, define the `alias` static property on the meta:

```php
#[Attribute]
class Description extends Meta
{
    public static string $alias = 'note';
}
```

With the code above, the `->description()` of a case will be accessible as `->note()`.

Another thing you can customize is the passed value. For instance, to wrap a color name like `text-{$color}-500`, you'd add the following `transform()` method:
```php
#[Attribute]
class Color extends Meta
{
    protected function transform(mixed $value): string
    {
        return "text-{$value}-500";
    }
}
```

And now the returned color will be correctly transformed:
```php
TaskStatus::COMPLETED->color(); // 'text-green-500'
```

#### Access the metadata

By accessing the attribute method name, you can get the meta value:

```php
TaskStatus::INCOMPLETE->description(); // 'Incomplete Task'
TaskStatus::COMPLETED->color(); // 'green'
```

Also, [`::tables()`](#tables) static method can return all meta attribute maps on each instance.

```php
$tables = TaskStatus::tables();

// $tables
[
    [
        'name' => 'INCOMPLETE', 
        'value' => 0, 
        'description' => 'Incomplete Task', 
        'color' => 'red'
    ], 
    [
        'name' => 'COMPLETED', 
        'value' => 1, 
        'description' => 'Completed Task', 
        'color' => 'green'
    ], 
    [
        'name' => 'CANCELED', 
        'value' => 2, 
        'description' => 'Canceled Task', 
        'color' => 'gray'
    ]
]
```

#### Use the `fromMeta()` method

Similarly, you can also get the enum case instance through the meta instance.

```php
$green = Color::make('green');// new Color('green');
$blue = Color::make('blue');// new Color('blue');

TaskStatus::fromMeta($green); // TaskStatus::COMPLETED
TaskStatus::fromMeta($blue); // Error: ValueError
```

#### Use the `tryFromMeta()` method
```php
TaskStatus::tryFromMeta($green); // TaskStatus::COMPLETED
TaskStatus::tryFromMeta($blue); // null
```

## Validation

Usually, we need limit your application's incoming data to a specified enums, laravel provides the basic rule, but here we have perfected it.

### Array Validation

You can use the 'array' syntax for rules.

#### Enum

Validate that a parameter is an instance of a given enum, it's similar to [`Enum Rules`](https://laravel.com/docs/10.x/validation#rule-enum) and can support pure enums.

```php
use BiiiiiigMonster\LaravelEnum\Rules\Enum;

public function store(Request $request)
{
    $this->validate($request, [
        'status' => ['required', new Enum(TaskStatus::class)],
        'role' => ['required', new Enum(Role::class)],
    ]);
}
```

#### EnumMeta

Additionally, validate that a parameter is an instance of the given meta in the given enum.

```php
use BiiiiiigMonster\LaravelEnum\Rules\EnumMeta;

public function store(Request $request)
{
    $this->validate($request, [
        'color' => ['required', new EnumMeta(TaskStatus::class, Color::class)],
    ]);
}
```

`EnumMeta` rule takes two parameters, the first is given enum, the second is given meta, if parameter name is same of meta method name, you can omit it:  

```php
'color' => ['required', new EnumMeta(TaskStatus::class)],
```

### Pipe Validation

You can also use the 'pipe' syntax for rules.

- **enumerate**: _enum_class_
- **enum_meta**: _enum_class,[meta_attribute]_

```php
'status' => 'required|enumerate:' . TaskStatus::class,
'color' => 'required|enum_meta:' . TaskStatus::class . ',' . Color::class,
```

### Validation messages

If needed, you can modify the error message when validated fails.

Run the following command to publish the language files to your `lang` folder:

```
php artisan vendor:publish --provider="BiiiiiigMonster\LaravelEnum\EnumServiceProvider" --tag="translations"
```

## Localization

### Labels

The enum instances are descriptive, and we have added translation capabilities for this.
You can translate the strings returned by the enum instance's `->label()` method using Laravel's built-in [localization](https://laravel.com/docs/localization) features.

Add a new `enums.php` keys file for each of your supported languages. In this example there is one for English and one for Spanish:

```php
// lang/en/enums.php
<?php declare(strict_types=1);

use App\Enums\TaskStatus;

return [

    TaskStatus::class => [
        TaskStatus::INCOMPLETE() => 'Incomplete',
        TaskStatus::COMPLETED() => 'Completed',
        TaskStatus::CANCELED() => 'Canceled',
    ],

];
```

```php
// lang/es/enums.php
<?php declare(strict_types=1);

use App\Enums\TaskStatus;

return [

    TaskStatus::class => [
        TaskStatus::INCOMPLETE() => 'Incompleto',
        TaskStatus::COMPLETED() => 'Completo',
        TaskStatus::CANCELED() => 'Cancelación',
    ],

];
```

Now, you just need to make sure that your enum implements the `Localizable` interface as demonstrated below:

```php
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Contracts\Localizable;

enum TaskStatus: int implements Localizable
{
    use EnumTraits;
    // ...
}
```

Alternatively, when creating with the `make:enum` Artisan command, add the `--local` option:

```shell
php artisan make:enum TaskStatus --local
```

The `->label()` method will now look for the value in your localization files:

```php
// en/enums.php
TaskStatus::CANCELED->label();// 'Canceled'

// es/enums.php
TaskStatus::CANCELED->label();// 'Cancelación'
```

and the [`::options()`](#options) static method returned array's value also be localized:

```php
// en/enums.php
TaskStatus::options();// [0 => 'Incomplete', 1 => 'Completed', 2 => 'Canceled']

// es/enums.php
TaskStatus::options();// [0 => 'Incompleto', 1 => 'Completo', 2 => 'Cancelación']
```

## Artisan Command

If you want your IDE to autocomplete the static instantiation helpers, you can generate PHPDoc annotations through an artisan command.

By default, all Enums in `app/Enums` will be annotated (you can change the folder by passing a path to `--folder`)

```bash
php artisan enum:phpdoc
```

Also, you can annotate a single class by specifying the class name

```bash
php artisan enum:phpdoc "App\Enums\TaskStatus"
```

```php
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use App\Enums\Metas\{Description, Color};

/**
 * @method static int INCOMPLETE()
 * @method static int COMPLETED()
 * @method static int CANCELED()
 * @method mixed description()
 * @method mixed color()
 */
enum TaskStatus: int
{
    use EnumTraits;
    // ...
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## References

- [archtechx/enums](https://github.com/archtechx/enums)
- [BenSampo/laravel-enum](https://github.com/BenSampo/laravel-enum)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
