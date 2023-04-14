<?php

namespace BiiiiiigMonster\LaravelEnum\Tests\Enums;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;

enum Role
{
    use EnumTraits;

    case ADMIN;

    case GUEST;
}
