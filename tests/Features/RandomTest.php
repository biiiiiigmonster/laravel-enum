<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can return one of case from a backed enum')
    ->expect(Status::random())
    ->toBeInstanceOf(Status::class);

it('can return one of case from a pure enum')
    ->expect(Role::random())
    ->toBeInstanceOf(Role::class);
