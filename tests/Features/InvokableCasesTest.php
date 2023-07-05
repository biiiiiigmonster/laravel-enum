<?php

use BiiiiiigMonster\LaravelEnum\Exceptions\UndefinedCaseException;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can be used as a static method with backed enums', function () {
    expect(Status::PENDING())->toBe(1)
        ->and(Status::DONE())->toBe(2);
});

it('can be used as a static method with pure enums', function () {
    expect(Role::ADMIN())->toBe('ADMIN')
        ->and(Role::GUEST())->toBe('GUEST');
});

it('can be invoked as an instance as a backed enum', function () {
    $status = Status::PENDING;

    expect($status())->toBe(1)
        ->and($status())->toBe($status->value);
});

it('can be invoked as an instance as a pure enum', function () {
    $role = Role::ADMIN;

    expect($role())->toBe('ADMIN');
});

it('throws an error when a nonexistent case is being used for backed enums', function () {
    Status::INVALID();
})->throws(UndefinedCaseException::class);

it('throws an error when a nonexistent case is being used for pure enums', function () {
    Role::INVALID();
})->throws(UndefinedCaseException::class);
