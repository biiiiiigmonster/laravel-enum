<?php

use BiiiiiigMonster\LaravelEnum\Exceptions\MetaValueError;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Color;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can select a case by name with from() for pure enums', function () {
    expect(Role::from('ADMIN'))
        ->toBe(Role::ADMIN);
});

it('can select a case by name with from() for backed enums', function () {
    expect(Status::from(1))
        ->toBe(Status::PENDING);
});

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

it('enums can be instantiated from meta', function () {
    expect(Role::fromMeta(Color::make('indigo')))->toBe(Role::ADMIN)
        ->and(Role::fromMeta(Color::make('gray')))->toBe(Role::GUEST)
        ->and(Status::fromMeta(Desc::make('Incomplete task')))->toBe(Status::PENDING)
        ->and(Status::fromMeta(Desc::make('Completed task')))->toBe(Status::DONE);
});

it('enums can be instantiated from meta using tryFromMeta')
    ->expect(Role::tryFromMeta(Color::make('indigo')))
    ->toBe(Role::ADMIN);

it('fromMeta throws an exception when the enum cannot be instantiated', function () {
    Role::fromMeta(Color::make('foobar'));
})->throws(MetaValueError::class);

it('tryFromMeta silently fails when the enum cannot be instantiated')
    ->expect(Role::tryFromMeta(Color::make('foobar')))
    ->toBeNull();

it('enums can be instantiated from meta value with method', function () {
    expect(Role::fromMeta('indigo', 'color'))->toBe(Role::ADMIN)
        ->and(Role::fromMeta('gray', 'color'))->toBe(Role::GUEST)
        ->and(Status::fromMeta('Incomplete task', 'description'))->toBe(Status::PENDING)
        ->and(Status::fromMeta('Completed task', 'description'))->toBe(Status::DONE);
});

it('enums can be instantiated from meta value with method using tryFromMeta')
    ->expect(Role::tryFromMeta('indigo', 'color'))
    ->toBe(Role::ADMIN);

it('fromMeta throws an exception when the enum cannot be instantiated with method', function () {
    Role::fromMeta('foobar', 'color');
})->throws(MetaValueError::class);

it('tryFromMeta silently fails when the enum cannot be instantiated with method')
    ->expect(Role::tryFromMeta('foobar', 'color'))
    ->toBeNull();
