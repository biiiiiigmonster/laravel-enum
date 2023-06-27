<?php

use BiiiiiigMonster\LaravelEnum\Rules\Enum;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;
use Illuminate\Support\Facades\Validator;

$param = [
    'role' => 'ADMIN',
    'today' => 1,
];

it('can validate value by the enum case name match', function () use ($param) {
    $ruler = [
        'role' => ['required', new Enum(Role::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

it('can validate value by the enum case name match, using string pipe validation', function () use ($param) {
    $ruler = [
        'role' => 'required|enum:'.Role::class,
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

it('validate value failed when the enum case name not match', function () use ($param) {
    $ruler = [
        'today' => ['required', new Enum(Week::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeFalse();
});
