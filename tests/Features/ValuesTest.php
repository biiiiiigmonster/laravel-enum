<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can return an array of case values from a backed enum')
    ->expect(Status::values())
    ->toBe([0, 1]);

it('can return an array of case names from a pure enum')
    ->expect(Role::values())
    ->toBe(['ADMIN', 'GUEST']);
