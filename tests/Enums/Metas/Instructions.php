<?php

namespace BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas;

use Attribute;
use BiiiiiigMonster\LaravelEnum\Concerns\Meta;

#[Attribute]
class Instructions extends Meta
{
    public static string $alias = 'help';

    protected function transform(mixed $value): string
    {
        return 'Help: '.$value;
    }
}
