<?php

namespace BiiiiiigMonster\LaravelEnum\Rules;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Concerns\Meta;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;
use UnitEnum;

class EnumMeta implements ValidationRule
{
    public function __construct(protected string $enum, protected ?string $meta = null)
    {
        if (! is_subclass_of($this->enum, UnitEnum::class)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
        if (! in_array(EnumTraits::class, class_uses($this->enum))) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
        if ($this->meta && ! is_subclass_of($this->meta, Meta::class)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
    }

    public function passes($attribute, $value): bool
    {
        return ! is_null($this->meta
                ? $this->enum::tryFromMeta(new $this->meta($value))
                : $this->enum::tryFromMetaMethod($value, $attribute)
            );
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->passes($attribute, $value)) {
            $fail('laravelEnum::validation.enum_meta')->translate();
        }
    }
}
