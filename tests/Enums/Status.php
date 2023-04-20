<?php

namespace BiiiiiigMonster\LaravelEnum\Tests\Enums;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Color;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Instructions;

/**
 * @method static int PENDING()
 * @method static int DONE()
 * @method string color()
 * @method string description()
 * @method string help()
 */
enum Status: int
{
    use EnumTraits;

    #[Color('orange')] #[Desc('Incomplete task')]
    case PENDING = 0;

    #[Color('green')] #[Desc('Completed task')]
    #[Instructions('Illegal meta property — not enabled on the enum')]
    case DONE = 1;
}
