<?php

use BiiiiiigMonster\LaravelEnum\Rules\EnumName;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;
use Illuminate\Support\Facades\Validator;

$param = [
    'role' => 'ADMIN',
    'today' => 'MONDAY',
];

test('can validate value by enum case name match', function () use ($param) {
    $ruler = [
        'role' => ['required', new EnumName(Role::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

test('can validate value by enum case name match, using string pipe validation', function () use ($param) {
    $ruler = [
        'role' => 'required|enum_name:'.Role::class,
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

test('validate value failed when the enum case name not match', function () use ($param) {
    $ruler = [
        'today' => ['required', new EnumName(Week::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeFalse();
});
