<?php

namespace BiiiiiigMonster\LaravelEnum\Concerns;

abstract class Meta
{
    const ALIAS = null;

    final public function __construct(
        public mixed $value,
    ) {
        $this->value = $this->transform($value);
    }

    public static function make(mixed $value): static
    {
        return new static($value);
    }

    protected function transform(mixed $value): mixed
    {
        // Feel free to override this to transform the value during instantiation

        return $value;
    }

    final public static function method(): string
    {
        if (property_exists(static::class, 'alias')) {
            return static::${'alias'};
        }

        return str(static::class)->afterLast('\\')->lcfirst();
    }

    public static function getLocalizationKey(): string
    {
        return 'enums.'.static::class;
    }
}
