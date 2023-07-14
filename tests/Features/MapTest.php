<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;

it('can get map array by one case from the enum', function () {
    expect(Role::ADMIN->map())
        ->toBeArray()
        ->toHaveKeys(['name', 'color', 'description', 'help']);
});
