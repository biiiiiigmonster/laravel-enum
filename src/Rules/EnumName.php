<?php

namespace BiiiiiigMonster\LaravelEnum\Rules;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class EnumName implements ValidationRule
{
    public function __construct(protected string $enum)
    {
        if (! enum_exists($this->enum)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
        if (! in_array(EnumTraits::class, trait_uses_recursive($this->enum))) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
    }

    public function passes(string $attribute, mixed $value): bool
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
