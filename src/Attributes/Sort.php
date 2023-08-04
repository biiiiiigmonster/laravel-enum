<?php

namespace BiiiiiigMonster\LaravelEnum\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class Sort
{
    public function __construct(
        public string $value,
        public int    $options = SORT_REGULAR
    )
    {
    }
}
