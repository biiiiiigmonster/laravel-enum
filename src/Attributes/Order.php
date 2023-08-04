<?php

namespace BiiiiiigMonster\LaravelEnum\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Order
{
    public function __construct(
        public string $value
    ) {
    }
}
