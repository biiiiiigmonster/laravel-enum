<?php

use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;

it('can get string label by one case from the enum', function () {
    expect(Role::ADMIN->label())
        ->toBeString()
        ->toBe('Admin');
});

it('can get local string label by one case from the enum implements Localizable interface', function () {
    $week = Week::SUNDAY;
    expect($week->label())
        ->toBeString()
        ->toBe(trans('enums.'.Week::class.'.'.$week()))
        ->toBe('enums.'.Week::class.'.'.$week());
});
