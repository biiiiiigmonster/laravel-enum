<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\EmptyCase;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can return an array of case values from a backed enum')
    ->expect(Status::values())
    ->toBe([1, 2]);

it('can return an array of case values from a pure enum')
    ->expect(Role::values())
    ->toBe(['ADMIN', 'GUEST']);

it('can return an empty values array from an enum of empty case')
    ->expect(EmptyCase::values())
    ->toBeEmpty();
