<?php

use BiiiiiigMonster\LaravelEnum\Concerns\Meta;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Color;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Instructions;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('pure enums can have meta on cases', function () {
    expect(Role::ADMIN->color())->toBe('indigo')
        ->and(Role::GUEST->color())->toBe('gray')
        ->and(Role::ADMIN->description())->toBe('Administrator')
        ->and(Role::GUEST->description())->toBe('Read-only guest')
        ->and(Role::ADMIN->help())->toBe('Help: Administrators can manage the entire account')
        ->and(Role::GUEST->help())->toBe('Help: Guest users can only view the existing records');

});

it('backed enums can have meta on cases', function () {
    expect(Status::DONE->color())->toBe('green')
        ->and(Status::PENDING->color())->toBe('orange')
        ->and(Status::PENDING->description())->toBe('Incomplete task')
        ->and(Status::DONE->description())->toBe('Completed task');

});

it('meta properties must be enabled on the enum to be usable on cases', function () {
    expect(Role::ADMIN->help())->not()->toBeNull() // enabled
        ->and(Status::PENDING->help())->toBeNull(); // not enabled
});

it('meta properties can transform arguments', function () {
    expect(
        Instructions::make('Administrators can manage the entire account')->value
    )->toStartWith('Help: ');
});

it('meta properties can customize the method name using a method', function () {
    expect(Desc::method())->toBe('description')
        ->and(Status::DONE->desc())->toBeNull()
        ->and(Status::DONE->description())->not()->toBeNull();
});

it('meta properties can customize the method name using a property', function () {
    expect(Instructions::method())->toBe('help')
        ->and(Role::ADMIN->instructions())->toBeNull()
        ->and(Role::ADMIN->help())->not()->toBeNull();
});

