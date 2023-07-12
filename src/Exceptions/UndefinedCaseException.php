<?php

declare(strict_types=1);

namespace BiiiiiigMonster\LaravelEnum\Exceptions;

use Exception;

class UndefinedCaseException extends Exception
{
    public function __construct(string $enum, string $case)
    {
        // Matches the error message of invalid Foo::BAR access
        parent::__construct("Undefined constant $enum::$case");
    }
}
