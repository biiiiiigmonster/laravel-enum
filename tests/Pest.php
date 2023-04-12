<?php

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Concerns\Meta;
use BiiiiiigMonster\LaravelEnum\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

#[Attribute]
class Color extends Meta
{
}

#[Attribute]
class Desc extends Meta
{
    public static function method(): string
    {
        return 'description';
    }
}

enum Status: int
{
    use EnumTraits;

    case PENDING = 0;

    case DONE = 1;
}

enum Role
{
    use EnumTraits;

    case ADMIN;

    case GUEST;
}
