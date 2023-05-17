<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;

it('can get string label by one case from the enum', function () {
    expect(Role::ADMIN->label())
        ->toBeString()
        ->toBe('Admin');
});

it('can get local string label by one case from the enum implements Localizable interface', function () {
    expect(Week::SUNDAY->label())
        ->toBeString()
        ->toBe(trans('enums.'.Week::class.'.0'))
        ->toBe('enums.'.Week::class.'.0');
});
