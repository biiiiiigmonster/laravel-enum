<?php

namespace BiiiiiigMonster\LaravelEnum\Concerns;

use BackedEnum;
use BiiiiiigMonster\LaravelEnum\Contracts\Localized;
use BiiiiiigMonster\LaravelEnum\Exceptions\MetaValueError;
use BiiiiiigMonster\LaravelEnum\Exceptions\UndefinedCaseError;
use Illuminate\Support\Arr;
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
            ?? throw new UndefinedCaseError(static::class, $name);

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

    public static function tables(Meta ...$metas): array
    {
        return collect(static::cases())->map(function (UnitEnum $case) use ($metas) {
            $table = ['name' => $case->name];
            if ($case instanceof BackedEnum) {
                $table['value'] = $case->value;
            }
            /** @var static $case */
            collect($case->metas())
                ->filter(fn (Meta $attr) => empty($metas) || in_array($attr::class, $metas))
                ->map(function (Meta $attr) use (&$table) {
                    $table[$attr::method()] = $attr->value;
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

    public static function fromMeta(mixed $value, ?string $method = null): static
    {
        return static::tryFromMeta($value, $method)
            ?? throw new MetaValueError(static::class, $value, $method);
    }

    public static function tryFromMeta(mixed $value, ?string $method = null): ?static
    {
        if ($value instanceof Meta) {
            $method = $value::method();
            $value = $value->value;
        }

        return collect(static::cases())
            ->first(fn (UnitEnum $case) =>
                /** @var static $case */
                collect($case->metas())
                    ->filter(fn (Meta $attr) => $attr::method() === $method
                        && $attr->value === $value)
                    ->isNotEmpty()
            );
    }

    public function __call(string $property, $arguments): mixed
    {
        return collect($this->metas())
            ->first(fn (Meta $attr) => $attr::method() === $property)
            ?->value;
    }

    private static function localized(Meta $meta): mixed
    {
        if ($meta instanceof Localized) {
            return Lang::get($meta->getLocalizationKey($meta->value));
        }

        return $meta->value;
    }

    public function getLocalizationKey(mixed $value): string
    {
        return 'enums'.static::class.$value;
    }

    private static function caseMetaAttributes(UnitEnum $case): Collection
    {
        $reflection = new ReflectionEnumUnitCase($case, $case->name);

        return collect($reflection->getAttributes())
            ->map(fn (ReflectionAttribute $refAttr) => $refAttr->newInstance())
            ->filter(fn ($attr) => $attr instanceof Meta);
    }

    /**
     * @return Meta[]
     */
    public function metas(): array
    {
        /** @var UnitEnum $this */
        $rfe = new ReflectionEnumUnitCase($this, $this->name);
        $metas = [];
        foreach ($rfe->getAttributes() as $attribute) {
            $instance = $attribute->newInstance();
            if ($instance instanceof Meta) {
                $metas[] = $instance;
            }
        }

        return $metas;
    }

    public static function random(): static
    {
        return Arr::random(static::cases());
    }
}
