<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can select a case by name with fromName() for pure enums')
    ->expect(Role::fromName('ADMIN'))
    ->toBe(Role::ADMIN);

it('throws a value error when selecting a non-existent case by name with fromName() for pure enums', function () {
    Role::fromName('NOBODY');
})->throws(ValueError::class);

it('can select a case by name with tryFromName() for pure enums')
    ->expect(Role::tryFromName('GUEST'))
    ->toBe(Role::GUEST);

it('returns null when selecting a non-existent case by name with tryFromName() for pure enums')
    ->expect(Role::tryFromName('NOBODY'))
    ->toBe(null);

it('can select a case by name with fromName() for backed enums')
    ->expect(Status::fromName('PENDING'))
    ->toBe(Status::PENDING);

it('throws a value error when selecting a non-existent case by name with fromName() for backed enums', function () {
    Status::fromName('NOTHING');
})->throws(ValueError::class);

it('can select a case by name with tryFromName() for backed enums')
    ->expect(Status::tryFromName('DONE'))
    ->toBe(Status::DONE);

it('returns null when selecting a non-existent case by name with tryFromName() for backed enums')
    ->expect(Status::tryFromName('NOTHING'))
    ->toBeNull();
