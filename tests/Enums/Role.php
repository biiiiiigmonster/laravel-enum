<?php

namespace BiiiiiigMonster\LaravelEnum\Tests\Enums;

use BiiiiiigMonster\LaravelEnum\Attributes\DefaultCase;
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Color;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Instructions;

/**
 * @method static string ADMIN()
 * @method static string GUEST()
 * @method mixed color()
 * @method mixed description()
 * @method string help()
 */
enum Role
{
    use EnumTraits;

    #[Color('indigo')]
    #[Desc('Administrator')]
    #[Instructions('Administrators can manage the entire account')]
    #[DefaultCase]
    case ADMIN;

    #[Color('gray')]
    #[Desc('Read-only guest')]
    #[Instructions('Guest users can only view the existing records')]
    case GUEST;
}
