<?php

namespace BiiiiiigMonster\LaravelEnum\Concerns;

use BackedEnum;
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
        return $this instanceof BackedEnum ? $this->value : $this->name;
    }

    public static function __callStatic($name, $args)
    {
        $cases = static::cases();

        foreach ($cases as $case) {
            if ($case->name === $name) {
                return $case instanceof BackedEnum ? $case->value : $case->name;
            }
        }

        throw new ValueError('"'.$name.'" is not a valid name for enum "'.static::class.'"');
    }

    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(static::cases(),
            static::class instanceof BackedEnum ? 'value' : 'name');
    }

    public static function options(): array
    {
        $cases = static::cases();

        return static::class instanceof BackedEnum
            ? array_column($cases, 'value', 'name')
            : array_column($cases, 'name');
    }

    public static function from(string $case): static
    {
        return static::fromName($case);
    }

    public static function tryFrom(string $case): ?static
    {
        return static::tryFromName($case);
    }

    public static function fromName(string $case): static
    {
        return static::tryFromName($case) ?? throw new ValueError('"'.$case.'" is not a valid name for enum "'.static::class.'"');
    }

    public static function tryFromName(string $case): ?static
    {
        $cases = array_filter(
            static::cases(),
            fn ($c) => $c->name === $case
        );

        return array_values($cases)[0] ?? null;
    }

    public static function tryFromMeta(MetaProperty $metaProperty): ?static
    {
        return collect(static::cases())->first(function (UnitEnum $case) use ($metaProperty) {
            $reflection = new ReflectionEnumUnitCase($case, $case->name);

            return collect($reflection->getAttributes())
                ->map(fn (ReflectionAttribute $attr) => $attr->newInstance())
                ->filter(fn ($attr) => $attr instanceof MetaProperty)
                ->first(fn (MetaProperty $attr) => $attr->value === $metaProperty->value);
        });
    }

    public static function fromMeta(MetaProperty $metaProperty): static
    {
        return static::tryFromMeta($metaProperty) ?? throw new ValueError(
            'Enum '.static::class.' does not have a case with a meta property "'.
            $metaProperty::class.'" of value "'.$metaProperty->value.'"'
        );
    }

    public function __call(string $property, $arguments): mixed
    {
        $reflection = new ReflectionEnumUnitCase($this, $this->name);

        return collect($reflection->getAttributes())
            ->map(fn (ReflectionAttribute $attr) => $attr->newInstance())
            ->filter(fn ($attr) => $attr instanceof MetaProperty)
            ->first(fn (MetaProperty $attr) => $attr::method() === $property);
    }
}
