<?php

namespace BiiiiiigMonster\LaravelEnum\Rules;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Concerns\Meta;
use BiiiiiigMonster\LaravelEnum\EnumServiceProvider;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnumMeta implements ValidationRule
{
    public function __construct(protected string $enum, protected ?string $meta = null)
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

        if (! in_array(EnumTraits::class, trait_uses_recursive($this->enum))) {
            return false;
        }

        if ($this->meta) {
            if (! is_subclass_of($this->meta, Meta::class)) {
                return false;
            }

            $value = new $this->meta($value);
        }

        return ! is_null($this->enum::tryFromMeta($value, $attribute));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->passes($attribute, $value)) {
            $fail(EnumServiceProvider::LANG_NAMESPACE.'::messages.enum_meta')->translate();
        }
    }
}
