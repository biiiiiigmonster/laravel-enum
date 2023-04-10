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

    public static function options(): array
    {
        if (! is_subclass_of(static::class, BackedEnum::class)) {
            return static::names();
        }

        return array_column(static::cases(), 'value', 'name');
    }

    public static function tables(...$metas): array
    {
        return collect(static::cases())->map(function (UnitEnum $case) use ($metas) {
            $table = ['name' => $case->name];
            if ($case instanceof BackedEnum) {
                $table['value'] = $case->value;
            }
            self::caseMetaAttributes($case)
                ->filter(fn (Meta $attr) => empty($metas) || in_array($attr::class, $metas))
                ->map(function (Meta $attr) use (&$table) {
                    $table[$attr::method()] = self::localized($attr->value);
                });

            return $table;
        })->all();
    }

    public static function fromName(string $name): static
    {
        return static::tryFromName($name) ?? throw new ValueError(
            '"'.$name.'" is not a valid name for enum "'.static::class.'"'
        );
    }

    public static function tryFromName(string $name): ?static
    {
        return collect(static::cases())->first(fn (UnitEnum $case) => $case->name === $name);
    }

    public static function fromMeta(Meta $meta): static
    {
        return static::tryFromMeta($meta) ?? throw new ValueError(
            'Enum '.static::class.' does not have a case with a meta property "'.
            $meta::class.'" of value "'.$meta->value.'"'
        );
    }

    public static function tryFromMeta(Meta $meta): ?static
    {
        return collect(static::cases())
            ->first(fn (UnitEnum $case) => self::caseMetaAttributes($case)
                ->filter(fn (Meta $attr) => $attr::class === $meta::class)
                ->contains(fn (Meta $attr) => $attr->value === $meta->value)
            );
    }

    public function __call(string $property, $arguments): mixed
    {
        $meta = self::caseMetaAttributes($this)
            ->first(fn (Meta $attr) => $attr::method() === $property)
            ?? throw new ValueError(
                'Enum '.$this::class.' does not have a case with a meta property "'.$property.'"'
            );

        return self::localized($meta);
    }

    private static function localized(Meta $meta): mixed
    {
        if ($meta instanceof Localizable) {
            return Lang::get($meta->value);
        }

        return $meta->value;
    }

    private static function caseMetaAttributes(UnitEnum $case): Collection
    {
        $reflection = new ReflectionEnumUnitCase($case, $case->name);

        return collect($reflection->getAttributes())
            ->map(fn (ReflectionAttribute $refAttr) => $refAttr->newInstance())
            ->filter(fn ($attr) => $attr instanceof Meta);
    }

    public static function random(): static
    {
        return array_rand(static::cases());
    }
}
