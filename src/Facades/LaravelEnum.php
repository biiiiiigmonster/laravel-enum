<?php

namespace BiiiiiigMonster\\LaravelEnum\LaravelEnum\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BiiiiiigMonster\\LaravelEnum\LaravelEnum\LaravelEnum
 */
class LaravelEnum extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BiiiiiigMonster\\LaravelEnum\LaravelEnum\LaravelEnum::class;
    }
}
