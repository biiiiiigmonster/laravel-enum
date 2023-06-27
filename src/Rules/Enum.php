<?php

namespace BiiiiiigMonster\LaravelEnum\Rules;

use BiiiiiigMonster\LaravelEnum\EnumServiceProvider;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Enum implements ValidationRule
{
    public function __construct(protected string $enum)
    {
    }

    public function passes(string $attribute, mixed $value): bool
    {
        if (! enum_exists($this->enum)) {
            return false;
        }

        if ($value instanceof $this->enum) {
            return true;
        }

        if (! method_exists($this->enum, 'tryFrom')) {
            return false;
        }

        return ! is_null($this->enum::tryFrom($value));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->passes($attribute, $value)) {
            $fail(EnumServiceProvider::LANG_NAMESPACE.'::message.enum')->translate();
        }
    }
}
