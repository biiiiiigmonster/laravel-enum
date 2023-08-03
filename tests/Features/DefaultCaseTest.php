<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can return null from an enum without the attribute')
    ->expect(Status::default())
    ->toBeNull();

it('can return the specified case from an enum with the attribute')
    ->expect(Role::default())
    ->toBe(Role::ADMIN);
