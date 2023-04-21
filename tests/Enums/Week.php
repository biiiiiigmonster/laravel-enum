<?php

namespace BiiiiiigMonster\LaravelEnum\Tests\Enums;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Instructions;

/**
 * @method static int SUNDAY()
 * @method static int SATURDAY()
 * @method mixed description()
 * @method string help()
 */
enum Week: int
{
    use EnumTraits;

    #[Desc('FirstDay of week')]
    #[Instructions('Not work')]
    case SUNDAY = 0;

    #[Desc('LastDay of week')]
    #[Instructions('Not work too')]
    case SATURDAY = 1;
}
