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

### Invokable

This helper lets you get the value of a backed enum, or the name of a pure enum, by "invoking" it — either statically (`MyEnum::FOO()` instead of `MyEnum::FOO`), or as an instance (`$enum()`).

That way, you can use enums as array keys:
```php
'statuses' => [
    TaskStatus::INCOMPLETE() => ['some configuration'],
    TaskStatus::COMPLETED() => ['some configuration'],
],
```

Or access the underlying primitives for any other use cases:
```php
public function updateStatus(int $status): void;

$task->updateStatus(TaskStatus::COMPLETED());
```

The main point: this is all without [having to append](https://twitter.com/archtechx/status/1495158237137494019) `->value` to everything.

This approach also has *decent* IDE support. You get autosuggestions while typing, and then you just append `()`:
```php
MyEnum::FOO; // => MyEnum instance
MyEnum::FOO(); // => 1
```

#### Apply the trait on your enum
```php
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;

// backed enum.
enum TaskStatus: int
{
    use EnumTraits;

    case INCOMPLETE = 0;
    case COMPLETED = 1;
    case CANCELED = 2;
}

// pure enum.
enum Role
{
    use EnumTraits;

    case ADMINISTRATOR;
    case SUBSCRIBER;
    case GUEST;
}
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

```php
TaskStatus::options(); // [0 => 'Incomplete', 1 => 'Completed', 2 => 'Canceled']
Role::options(); // ['ADMINISTRATOR' => 'Administrator', 'SUBSCRIBER' => 'Subscriber', 'GUEST' => 'Guest']
```

#### Tables

```php
TaskStatus::tables(); // [['name' => 'INCOMPLETE', 'value' => 0], ['name' => 'COMPLETED', 'value' => 1], ['name' => 'CANCELED', 'value' => 2]]
Role::tables(); // [['name' => 'ADMINISTRATOR'], ['name' => 'SUBSCRIBER'], ['name' => 'GUEST']]
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

```php
TaskStatus::random(); // TaskStatus::COMPLETED
Role::random(); // Role::GUEST
```

### Meta

This feature lets you add meta data to enum cases.

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

#### Creating meta properties

Each meta property (= attribute used on a case) needs to exist as a class.

```php
use BiiiiiigMonster\LaravelEnum\Concerns\Meta;

#[Attribute]
class Color extends Meta {}

#[Attribute]
class Description extends Meta {}
```

Inside the class, you can customize a few things. For instance, you may want to use a different method name than the one derived from the class name (`Description` becomes `description()` by default). To do that, define the `alias` static property on the meta:
```php
#[Attribute]
class Description extends Meta
{
    public static string $alias = 'note';
}
```

With the code above, the description of a case will be accessible as `TaskStatus::INCOMPLETE->note()`.

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

```php
TaskStatus::INCOMPLETE->description(); // 'Incomplete Task'
TaskStatus::COMPLETED->color(); // 'green'
```

```php
TaskStatus::tables(); 
/*
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
*/
```

#### Use the `fromMeta()` method
```php
TaskStatus::fromMeta(Color::make('green')); // TaskStatus::COMPLETED
TaskStatus::fromMeta(Color::make('blue')); // Error: ValueError
```

#### Use the `tryFromMeta()` method
```php
TaskStatus::tryFromMeta(Color::make('green')); // TaskStatus::COMPLETED
TaskStatus::tryFromMeta(Color::make('blue')); // null
```

## Validation

### Array Validation

#### Enumerate

Additionally, you can validate that a parameter is an instance of a given enum.

```php
use BiiiiiigMonster\LaravelEnum\Rules\Enumerate;

public function store(Request $request)
{
    $this->validate($request, [
        'statuses' => ['required', new Enumerate(TaskStatus::class)],
    ]);
}
```

#### Enum meta

```php
use BiiiiiigMonster\LaravelEnum\Rules\EnumMeta;

public function store(Request $request)
{
    $this->validate($request, [
        'color' => ['required', new EnumMeta(TaskStatus::class, Color::class)],
    ]);
}
```

### Pipe Validation

You can also use the 'pipe' syntax for rules.

**enumerate**:_enum_class_
**enum_meta**_:enum_class,[meta_attribute]_

```php
'statuses' => 'required|enumerate:' . TaskStatus::class,
'color' => 'required|enum_meta:' . TaskStatus::class . ',' . Color::class,
```

## Localization

### Validation messages

Run the following command to publish the language files to your `lang` folder.

```
php artisan vendor:publish --provider="BiiiiiigMonster\LaravelEnum\EnumServiceProvider" --tag="translations"
```

### Enum labels

You can translate the strings returned by the `label()` method using Laravel's built-in [localization](https://laravel.com/docs/localization) features.

Add a new `enums.php` keys file for each of your supported languages. In this example there is one for English and one for Spanish.

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

Now, you just need to make sure that your enum implements the `Localized` interface as demonstrated below:

```php
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Contracts\Localized;

enum TaskStatus: int implements Localized
{
    // ...
}
```

The `label()` method will now look for the value in your localization files.
> Tips: `options()` method returned array's value also be translated

## Artisan Command

If you want your IDE to autocomplete the static instantiation helpers, you can generate PHPDoc annotations through an artisan command.

By default, all Enums in `app/Enums` will be annotated (you can change the folder by passing a path to `--folder`)

```bash
php artisan enum:annotate
```

You can annotate a single class by specifying the class name

```bash
php artisan enum:annotate "App\Enums\TaskStatus"
```

```php
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use App\Enums\Meta\{Description, Color};

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

    #[Description('Incomplete Task')] #[Color('red')]
    case INCOMPLETE = 0;

    #[Description('Completed Task')] #[Color('green')]
    case COMPLETED = 1;

    #[Description('Canceled Task')] #[Color('gray')]
    case CANCELED = 2;
}
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
