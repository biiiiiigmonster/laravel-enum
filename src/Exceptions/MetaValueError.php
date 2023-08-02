<?php

namespace BiiiiiigMonster\LaravelEnum\Exceptions;

use BiiiiiigMonster\LaravelEnum\Concerns\Meta;
use ValueError;

class MetaValueError extends ValueError
{
    public function __construct(string $enum, mixed $value, string $method = null)
    {
        if ($value instanceof Meta) {
            $method = $value::class;
            $value = $value->value;
        }

        // Matches the error message of invalid Foo::BAR access
        parent::__construct('Enum '.$enum.' does not have a case with a meta property "'.
            $method.'" of value "'.$value.'"');
    }
}
