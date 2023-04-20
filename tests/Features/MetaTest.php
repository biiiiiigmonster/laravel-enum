<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Color;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Instructions;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

test('pure enums can have meta on cases', function () {
    expect(Role::ADMIN->color())->toBe('indigo')
        ->and(Role::GUEST->color())->toBe('gray')
        ->and(Role::ADMIN->description())->toBe('Administrator')
        ->and(Role::GUEST->description())->toBe('Read-only guest')
        ->and(Role::ADMIN->help())->toBe('Help: Administrators can manage the entire account')
        ->and(Role::GUEST->help())->toBe('Help: Guest users can only view the existing records');

});

test('backed enums can have meta on cases', function () {
    expect(Status::DONE->color())->toBe('green')
        ->and(Status::PENDING->color())->toBe('orange')
        ->and(Status::PENDING->description())->toBe('Incomplete task')
        ->and(Status::DONE->description())->toBe('Completed task');

});

test('meta properties must be enabled on the enum to be usable on cases', function () {
    expect(Role::ADMIN->help())->not()->toBeNull() // enabled
        ->and(Status::PENDING->help())->toBeNull(); // not enabled
});

test('meta properties can transform arguments', function () {
    expect(
        Instructions::make('Administrators can manage the entire account')->value
    )->toStartWith('Help: ');
});

test('meta properties can customize the method name using a method', function () {
    expect(Desc::method())->toBe('description')
        ->and(Status::DONE->desc())->toBeNull()
        ->and(Status::DONE->description())->not()->toBeNull();
});

test('meta properties can customize the method name using a property', function () {
    expect(Instructions::method())->toBe('help')
        ->and(Role::ADMIN->instructions())->toBeNull()
        ->and(Role::ADMIN->help())->not()->toBeNull();
});

test('enums can be instantiated from meta', function () {
    expect(Role::fromMeta(Color::make('indigo')))->toBe(Role::ADMIN)
        ->and(Role::fromMeta(Color::make('gray')))->toBe(Role::GUEST)
        ->and(Status::fromMeta(Desc::make('Incomplete task')))->toBe(Status::PENDING)
        ->and(Status::fromMeta(Desc::make('Completed task')))->toBe(Status::DONE);
});

test('enums can be instantiated from meta using tryFromMeta')
    ->expect(Role::tryFromMeta(Color::make('indigo')))
    ->toBe(Role::ADMIN);

test('fromMeta throws an exception when the enum cannot be instantiated', function () {
    Role::fromMeta(Color::make('foobar'));
})->throws(ValueError::class);

test('tryFromMeta silently fails when the enum cannot be instantiated')
    ->expect(Role::tryFromMeta(Color::make('foobar')))
    ->toBeNull();

test('enums can be instantiated from meta value with method', function () {
    expect(Role::fromMetaMethod('indigo', 'color'))->toBe(Role::ADMIN)
        ->and(Role::fromMetaMethod('gray', 'color'))->toBe(Role::GUEST)
        ->and(Status::fromMetaMethod('Incomplete task', 'description'))->toBe(Status::PENDING)
        ->and(Status::fromMetaMethod('Completed task', 'description'))->toBe(Status::DONE);
});

test('enums can be instantiated from meta value with method using tryFromMeta')
    ->expect(Role::tryFromMetaMethod('indigo', 'color'))
    ->toBe(Role::ADMIN);

test('fromMeta throws an exception when the enum cannot be instantiated with method', function () {
    Role::fromMetaMethod('foobar', 'color');
})->throws(ValueError::class);

test('tryFromMeta silently fails when the enum cannot be instantiated with method')
    ->expect(Role::tryFromMetaMethod('foobar', 'color'))
    ->toBeNull();
