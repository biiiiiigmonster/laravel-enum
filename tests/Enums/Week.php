<?php

namespace BiiiiiigMonster\LaravelEnum\Tests\Enums;

use BiiiiiigMonster\LaravelEnum\Attributes\Order;
use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Contracts\Localizable;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Instructions;

enum Week: int implements Localizable
{
    use EnumTraits;

    #[Desc('FirstDay of week')]
    #[Instructions('Not work')]
    #[Order(0)]
    case SUNDAY = 0;

    #[Desc('LastDay of week')]
    #[Instructions('Not work too')]
    #[Order(2)]
    case SATURDAY = 6;

    #[Desc('First work day of week')]
    #[Instructions('Need work')]
    #[Order(1)]
    case MONDAY = 1;
}
