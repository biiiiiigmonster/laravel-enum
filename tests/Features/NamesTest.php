<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\EmptyCase;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can return an array of case names from backed enums')
    ->expect(Status::names())
    ->toBe(['PENDING', 'DONE']);

it('can return an array of case names from pure enums')
    ->expect(Role::names())
    ->toBe(['ADMIN', 'GUEST']);

it('can return an empty names array from an enum of empty case')
    ->expect(EmptyCase::names())
    ->toBeEmpty();
