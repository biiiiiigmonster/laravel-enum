<?php

namespace BiiiiiigMonster\LaravelEnum\Concerns;

use BackedEnum;
use Illuminate\Support\Collection;
use ReflectionAttribute;
use ReflectionEnumUnitCase;
use UnitEnum;
use ValueError;

/**
 * @mixin UnitEnum
 */
trait EnumTraits
{
    public function __invoke()
    {
        return self::call($this);
    }

    public static function __callStatic($name, $args)
    {
        $case = collect(static::cases())->first(fn(UnitEnum $case) => $case->name === $name)
            ?? throw new ValueError('"' . $name . '" is not a valid name for enum "' . static::class . '"');

        return self::call($case);
    }

    private static function call(UnitEnum $case): mixed
    {
        return $case instanceof BackedEnum ? $case->value : $case->name;
    }

    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        if (!static::class instanceof BackedEnum) {
            return static::names();
        }

        return array_column(static::cases(), 'value');
    }

    public static function options(): array
    {
        if (!static::class instanceof BackedEnum) {
            return static::names();
        }

        return array_column(static::cases(), 'value', 'name');
    }

    public static function fromName(string $name): static
    {
        return static::tryFromName($name) ?? throw new ValueError('"' . $name . '" is not a valid name for enum "' . static::class . '"');
    }

    public static function tryFromName(string $name): ?static
    {
        return collect(static::cases())->first(fn(UnitEnum $case) => $case->name === $name);
    }

    public static function fromMeta(MetaProperty $metaProperty): static
    {
        return static::tryFromMeta($metaProperty) ?? throw new ValueError(
            'Enum ' . static::class . ' does not have a case with a meta property "' .
            $metaProperty::class . '" of value "' . $metaProperty->value . '"'
        );
    }

    public static function tryFromMeta(MetaProperty $metaProperty): ?static
    {
        return collect(static::cases())
            ->first(fn(UnitEnum $case) => self::caseMeta($case)
                ->filter(fn(MetaProperty $attr) => $attr::class === $metaProperty::class)
                ->contains(fn(MetaProperty $attr) => $attr->value === $metaProperty->value)
            );
    }

    public function __call(string $property, $arguments): mixed
    {
        $attr = self::caseMeta($this)
            ->first(fn(MetaProperty $attr) => $attr::method() === $property);

        return $attr?->value;
    }

    private static function caseMeta(UnitEnum $case): Collection
    {
        $reflection = new ReflectionEnumUnitCase($case, $case->name);

        return collect($reflection->getAttributes())
            ->map(fn(ReflectionAttribute $refAttr) => $refAttr->newInstance())
            ->filter(fn($attr) => $attr instanceof MetaProperty);
    }
}
