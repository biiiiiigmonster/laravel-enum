<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Status;

it('can return an array of options from a backed enum', function () {
    expect(Status::options())
        ->toBe([
            1 => 'Pending',
            2 => 'Done',
        ]);
});

it('can return an array of options from a pure enum', function () {
    expect(Role::options())
        ->toBe([
            'ADMIN' => 'Admin',
            'GUEST' => 'Guest',
        ]);
});
