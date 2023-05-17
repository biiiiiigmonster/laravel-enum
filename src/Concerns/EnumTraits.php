<?php

namespace BiiiiiigMonster\LaravelEnum\Concerns;

use BackedEnum;
use BiiiiiigMonster\LaravelEnum\Contracts\Localizable;
use BiiiiiigMonster\LaravelEnum\Exceptions\MetaValueError;
use BiiiiiigMonster\LaravelEnum\Exceptions\UndefinedCaseError;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
        return collect(static::cases())
            ->flatMap(fn (UnitEnum $case) =>
                /** @var static $case */
                [$case() => $case->label()]
            )
            ->all();
    }

    public static function tables(): array
    {
        $allMetaMethods = collect(static::cases())
            ->flatMap(fn (UnitEnum $case) => $case->metas())
            ->map(fn (Meta $meta) => $meta::method())
            ->unique()
            ->all();

        return collect(static::cases())
            ->map(function (UnitEnum $case) use ($allMetaMethods) {
                /** @var static $case */
                $map = collect($case->metas())
                    ->flatMap(fn (Meta $meta) => [$meta::method() => $meta->value])
                    ->merge(['name' => $case->name])
                    ->when($case instanceof BackedEnum,
                        fn (Collection $collection) => $collection
                            ->merge(['value' => $case->value])
                    )
                    ->all();

                foreach ($allMetaMethods as $method) {
                    $map[$method] = $map[$method] ?? null;
                }

                return $map;
            })
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

    public static function random(): static
    {
        return Arr::random(static::cases());
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

    public function getLocalizationKey(): string
    {
        return 'enums.'.$this::class.'.'.$this();
    }

    public function label(): string
    {
        return $this instanceof Localizable
            ? trans($this->getLocalizationKey())
            : Str::of($this->name)->lower()->studly();
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

    public static function __callStatic($name, $args): int|string
    {
        $case = collect(static::cases())
            ->first(fn (UnitEnum $case) => $case->name === $name)
            ?? throw new UndefinedCaseError(static::class, $name);

        return $case();
    }
}
