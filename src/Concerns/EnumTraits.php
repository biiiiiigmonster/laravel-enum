<?php

namespace BiiiiiigMonster\LaravelEnum\Concerns;

use BackedEnum;
use BiiiiiigMonster\LaravelEnum\Contracts\Localizable;
use BiiiiiigMonster\LaravelEnum\Exceptions\MetaValueError;
use BiiiiiigMonster\LaravelEnum\Exceptions\UndefinedCaseException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use ReflectionEnumUnitCase;
use UnitEnum;
use ValueError;

/**
 * @mixin UnitEnum
 */
trait EnumTraits
{
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        if (! is_subclass_of(static::class, BackedEnum::class)) {
            return static::names();
        }

        return array_column(static::cases(), 'value');
    }

    public static function options(): array
    {
        $keys = collect(static::cases())
            ->map(fn (UnitEnum $case) => /** @var static $case */ $case());

        $values = collect(static::cases())
            ->map(fn (UnitEnum $case) => /** @var static $case */ $case->label());

        return $keys->combine($values)->all();
    }

    public static function tables(): array
    {
        $tables = collect(static::cases())
            ->map(fn (UnitEnum $case) => /** @var static $case */ $case->maps());

        $allKeys = $tables->collapse()->map(fn () => null);

        return $tables
            ->map(fn ($map) => $allKeys->merge($map)->all())
            ->all();
    }

    public static function from($name): static
    {
        return static::fromName((string) $name);
    }

    public static function tryFrom($name): ?static
    {
        return static::tryFromName((string) $name);
    }

    public static function fromName(string $name): static
    {
        return static::tryFromName($name) ?? throw new ValueError(
            '"'.$name.'" is not a valid name for enum "'.static::class.'"'
        );
    }

    public static function tryFromName(string $name): ?static
    {
        return collect(static::cases())
            ->first(fn (UnitEnum $case) => $case->name === $name);
    }

    public static function fromMeta(mixed $value, string $method = null): static
    {
        return static::tryFromMeta($value, $method)
            ?? throw new MetaValueError(static::class, $value, $method);
    }

    public static function tryFromMeta(mixed $value, string $method = null): ?static
    {
        if ($value instanceof Meta) {
            $method = $value::method();
            $value = $value->value;
        }

        return collect(static::cases())
            ->first(fn (UnitEnum $case) =>
                /** @var static $case */
                collect($case->metas())
                    ->contains(fn (Meta $attr) => $attr::method() === $method
                        && $attr->value === $value)
            );
    }

    public static function random(): ?static
    {
        if (empty($cases = static::cases())) {
            return null;
        }

        return Arr::random($cases);
    }

    public static function default(): ?static
    {
        return collect(static::cases())
            ->first(fn (UnitEnum $case) => /** @var static $case */ $case->isDefault());
    }

    /**
     * @return Meta[]
     */
    public function metas(): array
    {
        $metas = [];

        $rfe = new ReflectionEnumUnitCase($this, $this->name);
        foreach ($rfe->getAttributes() as $attribute) {
            $instance = $attribute->newInstance();
            if ($instance instanceof Meta) {
                $metas[] = $instance;
            }
        }

        return $metas;
    }

    public function maps(): array
    {
        return collect($this->metas())
            ->flatMap(fn (Meta $meta) => [$meta::method() => $meta->value])
            ->merge(['name' => $this->name])
            ->when($this instanceof BackedEnum,
                fn (Collection $collection) => $collection
                    ->merge(['value' => $this->value])
            )
            ->all();
    }

    public function getLocalizationKey(): string
    {
        return 'enums.'.static::class.'.'.$this();
    }

    public function label(): string
    {
        return $this instanceof Localizable
            ? trans($this->getLocalizationKey())
            : str($this->name)->lower()->studly();
    }

    public function isDefault(): bool
    {
        $rfe = new ReflectionEnumUnitCase($this, $this->name);
        foreach ($rfe->getAttributes() as $attribute) {
            if ($attribute->getName() === DefaultCase::class) {
                return true;
            }
        }

        return false;
    }

    public function __invoke(): int|string
    {
        return $this instanceof BackedEnum ? $this->value : $this->name;
    }

    public function __call(string $property, $arguments): mixed
    {
        return collect($this->metas())
            ->first(fn (Meta $attr) => $attr::method() === $property)
            ?->value;
    }

    /**
     * @throws UndefinedCaseException
     */
    public static function __callStatic($name, $args): int|string
    {
        $case = collect(static::cases())
            ->first(fn (UnitEnum $case) => $case->name === $name)
            ?? throw new UndefinedCaseException(static::class, $name);

        return $case();
    }
}
