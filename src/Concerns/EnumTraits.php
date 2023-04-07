<?php

namespace BiiiiiigMonster\LaravelEnum\Concerns;

use BackedEnum;
use BiiiiiigMonster\LaravelEnum\Contracts\Localizable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use ReflectionAttribute;
use ReflectionEnumUnitCase;
use UnitEnum;
use ValueError;

/**
 * @mixin UnitEnum
 */
trait EnumTraits
{
    public function __invoke(): int|string
    {
        return self::call($this);
    }

    public static function __callStatic($name, $args): int|string
    {
        $case = collect(static::cases())
            ->first(fn (UnitEnum $case) => $case->name === $name)
            ?? throw new ValueError('"'.$name.'" is not a valid name for enum "'.static::class.'"');

        return self::call($case);
    }

    private static function call(UnitEnum $case): int|string
    {
        return $case instanceof BackedEnum ? $case->value : $case->name;
    }

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

    public static function options(?string $meta = null): array
    {
        if (! is_subclass_of(static::class, BackedEnum::class)) {
            return static::names();
        }

        if (is_null($meta)) {
            return array_column(static::cases(), 'value', 'name');
        }

        return collect(static::cases())
            ->map(fn (UnitEnum $case) => [
                'name' => $case->name,
                'meta' => self::localized(
                    self::caseMetaProperties($case)
                        ->first(fn (MetaProperty $attr) => $attr::class === $meta)
                ),
            ])
            ->pluck('meta', 'name');
    }

    public static function fromName(string $name): static
    {
        return static::tryFromName($name) ?? throw new ValueError('"'.$name.'" is not a valid name for enum "'.static::class.'"');
    }

    public static function tryFromName(string $name): ?static
    {
        return collect(static::cases())->first(fn (UnitEnum $case) => $case->name === $name);
    }

    public static function fromMeta(MetaProperty $metaProperty): static
    {
        return static::tryFromMeta($metaProperty) ?? throw new ValueError(
            'Enum '.static::class.' does not have a case with a meta property "'.
            $metaProperty::class.'" of value "'.$metaProperty->value.'"'
        );
    }

    public static function tryFromMeta(MetaProperty $metaProperty): ?static
    {
        return collect(static::cases())
            ->first(fn (UnitEnum $case) => self::caseMetaProperties($case)
                ->filter(fn (MetaProperty $attr) => $attr::class === $metaProperty::class)
                ->contains(fn (MetaProperty $attr) => $attr->value === $metaProperty->value)
            );
    }

    public function __call(string $property, $arguments): mixed
    {
        $attr = self::caseMetaProperties($this)
            ->first(fn (MetaProperty $attr) => $attr::method() === $property)
            ?? throw new ValueError(
                'Enum '.$this::class.' does not have a case with a meta property "'.$property.'"'
            );

        return self::localized($attr);
    }

    private static function localized(MetaProperty $attr): mixed
    {
        if ($attr instanceof Localizable) {
            return Lang::get($attr->value);
        }

        return $attr->value;
    }

    private static function caseMetaProperties(UnitEnum $case): Collection
    {
        $reflection = new ReflectionEnumUnitCase($case, $case->name);

        return collect($reflection->getAttributes())
            ->map(fn (ReflectionAttribute $refAttr) => $refAttr->newInstance())
            ->filter(fn ($attr) => $attr instanceof MetaProperty);
    }

    public static function random(): static
    {
        return array_rand(static::cases());
    }

    public static function coerce(mixed $value): ?static
    {
        if (is_null($value)) {
            return null;
        }

        $case = null;

        if (is_subclass_of($value, MetaProperty::class)) {
            $case = self::tryFromMeta($value);
        }

        if (is_null($case) && is_subclass_of(static::class, BackedEnum::class)) {
            $case = static::tryFrom($value);
        }

        if (is_null($case) && is_string($value)) {
            $case = self::tryFromName($value);
        }

        return $case;
    }
}
