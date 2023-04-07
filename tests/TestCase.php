<?php

namespace BiiiiiigMonster\LaravelEnum\Tests;

use BiiiiiigMonster\LaravelEnum\EnumServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            EnumServiceProvider::class,
        ];
    }
}
