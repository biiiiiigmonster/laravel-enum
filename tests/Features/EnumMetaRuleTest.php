<?php

use BiiiiiigMonster\LaravelEnum\Rules\EnumMeta;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Color;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Metas\Desc;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Role;
use BiiiiiigMonster\LaravelEnum\Tests\Enums\Week;
use Illuminate\Support\Facades\Validator;

$param = [
    'color' => 'gray',
    'desc' => 'Administrator',
];

it('can validate value by the enum case meta properties matches', function () use ($param) {
    $ruler = [
        'color' => ['required', new EnumMeta(Role::class, Color::class)],
        'desc' => ['required', new EnumMeta(Role::class, Desc::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

it('can validate value by the enum case meta properties matches, using string pipe validation', function () use ($param) {
    $ruler = [
        'color' => 'required|enum_meta:'.Role::class.','.Color::class,
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

it('can validate value by the enum case meta properties matches without meta class', function () use ($param) {
    $ruler = [
        'color' => ['required', new EnumMeta(Role::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

it('can validate value by the enum case meta properties matches without meta class, using string pipe validation', function () use ($param) {
    $ruler = [
        'color' => 'required|enum_meta:'.Role::class,
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeTrue();
});

it('validate value failed when the enum have not meta method match without meta class', function () use ($param) {
    $ruler = [
        'desc' => 'required|enum_meta:'.Role::class,
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeFalse();
});

it('validate value failed when the enum have not meta class match', function () use ($param) {
    $ruler = [
        'color' => ['required', new EnumMeta(Week::class, Color::class)],
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeFalse();
});

it('validate value failed when the enum have not meta class match, using string pipe validation', function () use ($param) {
    $ruler = [
        'color' => 'required|enum_meta:'.Week::class,
    ];

    expect(Validator::make($param, $ruler)->passes())->toBeFalse();
});
