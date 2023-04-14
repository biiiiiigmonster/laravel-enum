<?php

namespace BiiiiiigMonster\LaravelEnum\Rules;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;
use UnitEnum;

class EnumName implements ValidationRule
{
    public function __construct(protected string $enum)
    {
        if (! is_subclass_of($this->enum, UnitEnum::class)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
        if (! in_array(EnumTraits::class, class_uses($this->enum))) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
    }

    public function passes($attribute, $value): bool
    {
        return ! is_null($this->enum::tryFromName($value));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->passes($attribute, $value)) {
            $fail('laravelEnum::validation.enum_name')->translate();
        }
    }
}
