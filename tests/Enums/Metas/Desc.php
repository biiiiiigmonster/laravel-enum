<?php

namespace BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas;

use Attribute;
use BiiiiiigMonster\LaravelEnum\Concerns\Meta;

#[Attribute]
class Desc extends Meta
{
    public static string $alias = 'description';
}
