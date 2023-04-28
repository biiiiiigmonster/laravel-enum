<?php

namespace BiiiiiigMonster\LaravelEnum\Tests\Enums;

use BiiiiiigMonster\LaravelEnum\Concerns\EnumTraits;
use BiiiiiigMonster\LaravelEnum\Contracts\Localized;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Instructions;

enum Week: int implements Localized
{
    use EnumTraits;

    #[Desc('FirstDay of week')]
    #[Instructions('Not work')]
    case SUNDAY = 0;

    #[Desc('LastDay of week')]
    #[Instructions('Not work too')]
    case SATURDAY = 6;
}
