<?php

namespace BiiiiiigMonster\LaravelEnum\Rules;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Concerns\Meta;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class EnumMeta implements ValidationRule
{
    public function __construct(protected string $enum, protected ?string $meta = null)
    {
        if (! enum_exists($this->enum)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
        if (! in_array(EnumTraits::class, trait_uses_recursive($this->enum))) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
        if ($this->meta && ! is_subclass_of($this->meta, Meta::class)) {
            throw new InvalidArgumentException("Cannot validate against the enum, the class {$this->enum} doesn't exist.");
        }
    }

    public function passes(string $attribute, mixed $value): bool
    {
        if ($this->meta) {
            $value = new $this->meta($value);
            $attribute = null;
        }

        return ! is_null($this->enum::tryFromMeta($value, $attribute));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->passes($attribute, $value)) {
            $fail('laravelEnum::validation.enum_meta')->translate();
        }
    }
}
