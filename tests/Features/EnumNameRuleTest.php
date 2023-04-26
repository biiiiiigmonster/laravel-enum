<?php

use BiiiiiigMonster\LaravelEnum\Rules\EnumName;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;
use Illuminate\Support\Facades\Validator;

$param = [
    'role' => 'ADMIN',
    'today' => 'MONDAY',
];

it('can validate value by the enum case name match', function () use ($param) {
    $ruler = [
        'role' => ['required', new EnumName(Role::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

it('can validate value by the enum case name match, using string pipe validation', function () use ($param) {
    $ruler = [
        'role' => 'required|enum_name:'.Role::class,
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

it('validate value failed when the enum case name not match', function () use ($param) {
    $ruler = [
        'today' => ['required', new EnumName(Week::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeFalse();
});
